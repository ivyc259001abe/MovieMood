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
    public function show(): View
    {
        $user = Auth::user();

        $myReviews = method_exists($user, 'reviews')
            ? $user->reviews()->latest()->get()
            : ($user->reviews ?? []);

        $likedMovies = method_exists($user, 'likes')
            ? $user->likes()->latest()->get()
            : ($user->likedReviews ?? []);

        $watchlist = method_exists($user, 'watchlists')
            ? $user->watchlists()->latest()->get()
            : ($user->watchlist ?? []);

        $popularMovies = function_exists('getPopularMovies') ? getPopularMovies() : [];

        return view('mypage', compact('myReviews', 'likedMovies', 'watchlist', 'popularMovies'));
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
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:15'],
        ]);

        $user = $request->user();

        $user->fill($request->safe()->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
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

        // パスワード変更（入力がある場合のみ新しいパスワードの検証＆更新）
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

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