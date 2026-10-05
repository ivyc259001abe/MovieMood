<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Review;
use App\Models\Watchlist;
use App\Models\Like;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * マイページ表示処理
     */
    public function show()
    {
        $user = auth()->user();

        // 1. ユーザーのウォッチリスト一覧を1ページあたり6件でページネーション取得
        $watchlist = Watchlist::where('user_id', $user->id)
            ->latest()
            ->paginate(6);

        // ページネーション内の各要素に対して評価値（vote_average）をセット
        $watchlist->getCollection()->transform(function ($item) {
            if (!isset($item->vote_average) && !isset($item->rating)) {
                $avgRating = Review::where('movie_id', $item->movie_id ?? $item->tmdb_id ?? $item->id)->avg('rating');
                $item->vote_average = $avgRating ? round($avgRating, 1) : null;
            }
            return $item;
        });

        // 2. 自分の投稿レビュー一覧を取得
        $myReviews = Review::where('user_id', $user->id)->latest()->get();

        // 3. いいねした投稿を取得
        $likedReviewIds = Like::where('user_id', $user->id)->pluck('review_id');
        $likedReviews = Review::whereIn('id', $likedReviewIds)->latest()->get();

        return view('mypage', compact('user', 'watchlist', 'myReviews', 'likedReviews'));
    }

    /**
     * プロフィール編集画面表示
     */
    public function edit(Request $request): View
    {
        $popularMovies = function_exists('getPopularMovies') ? getPopularMovies() : [];

        return view('profile.edit', [
            'user' => $request->user(),
            'popularMovies' => $popularMovies,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // バリデーション
        $request->validate([
            'nickname' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // ニックネーム（ユーザー名）更新
        if (Schema::hasColumn('users', 'nickname')) {
            $user->nickname = $request->nickname;
        }
        $user->name = $request->nickname;

        // メールアドレスに入力がある場合のみ更新
        if ($request->filled('email')) {
            $user->email = $request->email;

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
        }

        // アバター画像の保存処理
        if ($request->hasFile('avatar') || $request->hasFile('icon')) {
            $file = $request->file('avatar') ?? $request->file('icon');

            if ($file && $file->isValid()) {
                $filename = 'icon_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move(public_path('uploads'), $filename);

                $avatarPath = 'uploads/' . $filename;

                if ($user->avatar && file_exists(public_path($user->avatar))) {
                    @unlink(public_path($user->avatar));
                }
                if (isset($user->icon_path) && file_exists(public_path($user->icon_path))) {
                    @unlink(public_path($user->icon_path));
                }

                $user->avatar = $avatarPath;

                if (Schema::hasColumn('users', 'icon')) {
                    $user->icon = $avatarPath;
                }
                if (Schema::hasColumn('users', 'icon_path')) {
                    $user->icon_path = $avatarPath;
                }

                session(['user_icon' => $avatarPath]);
            } else {
                return back()->withErrors([
                    'avatar' => '画像ファイルが大きすぎる（2MB超え）か、選択されたファイルが壊れています。小さめの画像でお試しください。'
                ])->withInput();
            }
        }

        // パスワード変更（入力がある場合のみ更新）
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return Redirect::route('mypage')->with('status', 'プロフィール情報を更新しました。');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        if ($user->avatar && file_exists(public_path($user->avatar))) {
            @unlink(public_path($user->avatar));
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}