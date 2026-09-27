<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User; // ★ 追加

class PasswordController extends Controller
{
    /**
     * Update the user's password (マイページ等からの変更用).
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Reset the user's password (未ログイン時の再設定用).
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.required' => 'メールアドレスを入力してください。',
            'email.exists' => 'このメールアドレスは登録されていません。',
            'password.required' => '新しいパスワードを入力してください。',
            'password.min' => 'パスワードは6文字以上で入力してください。',
            'password.confirmed' => 'パスワードの確認が一致しません。',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/login')->with('status', 'パスワードを変更しました。新しいパスワードでログインしてください。');
    }
}