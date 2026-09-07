<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
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
        $user = $request->user();

        // 入力された基本情報の詰め込み
        $user->fill($request->validated());

        // メールアドレスが変更された場合、確認ステータスをリセット
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 🌟 アイコン画像(avatar)のアップロード処理
        if ($request->hasFile('avatar')) {
            // 古いアバター画像があれば削除（容量節約のため）
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // 新しい画像を「public/avatars」フォルダに保存し、そのパスを取得
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // 🌟 新しいパスワードが入力されている場合のみ、ハッシュ化して保存
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // アカウント削除時、登録されていたアバター画像もサーバーから削除する
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}