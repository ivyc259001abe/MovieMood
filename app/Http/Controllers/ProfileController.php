<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * マイページの表示
     */
    public function show()
    {
        $user = Auth::user();

        // 自分の投稿レビュー
        $myReviews = $user->reviews;

        // いいねしたレビュー
        $likedReviews = $user->likedReviews;

        // 🎬 ウォッチリスト（観たい映画）の取得
        $watchlist = $user->watchlist ?? [];

        // 🎬 web.php で定義された getPopularMovies() を呼び出して人気映画を取得
        $popularMovies = function_exists('getPopularMovies') ? getPopularMovies() : [];

        return view('mypage', compact('myReviews', 'likedReviews', 'watchlist', 'popularMovies'));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // 🌟 更新時に名前の文字数を15文字以内に直接チェック
        $request->validate([
            'name' => ['required', 'string', 'max:15'],
        ]);

        $user = $request->user();

        // name と email のみを取り出して fill（password が空で上書きされるのを防止）
        $user->fill($request->safe()->only(['name', 'email']));

        // メールアドレスが変更された場合、確認ステータスをリセット
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 🌟 画像送信チェックとエラー判定の明確化
        if ($request->hasFile('avatar') || $request->hasFile('icon')) {
            $file = $request->file('avatar') ?? $request->file('icon');

            if ($file && $file->isValid()) {
                $filename = 'icon_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // public/uploads フォルダに移動
                $file->move(public_path('uploads'), $filename);

                $avatarPath = 'uploads/' . $filename;

                // 古い画像があれば物理ファイルを削除
                if ($user->avatar && file_exists(public_path($user->avatar))) {
                    @unlink(public_path($user->avatar));
                }
                if (isset($user->icon_path) && file_exists(public_path($user->icon_path))) {
                    @unlink(public_path($user->icon_path));
                }

                // すべてのカラムに直接参照可能なパスをセット
                $user->avatar = $avatarPath;

                if (Schema::hasColumn('users', 'icon')) {
                    $user->icon = $avatarPath;
                }
                if (Schema::hasColumn('users', 'icon_path')) {
                    $user->icon_path = $avatarPath;
                }

                session(['user_icon' => $avatarPath]);
            } else {
                // 🌟 PHPの容量制限等でファイルが正常に受け取れなかった場合
                return back()->withErrors([
                    'avatar' => '画像ファイルが大きすぎる（2MB超え）か、選択されたファイルが壊れています。小さめの画像でお試しください。'
                ])->withInput();
            }
        }

        // 新しいパスワードが入力されている場合のみ、ハッシュ化して保存
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
        // 🌟 退会時はパスワード確認のみ行う（名前のチェックは削除）
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

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