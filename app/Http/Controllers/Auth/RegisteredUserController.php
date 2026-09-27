<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        // ViewComposerが自動でポスターデータを渡すため、シンプルな返却だけでOK
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. バリデーション
        $request->validate([
            'nickname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
        ], [
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'icon.image' => 'アップロードできるのは画像ファイルのみです。',
            'icon.max' => '画像サイズは10MB以下にしてください。',
        ]);

        // 2. アイコン画像の保存処理
        $iconPath = null;

        if ($request->hasFile('icon') && $request->file('icon')->isValid()) {
            $file = $request->file('icon');
            $filename = 'icon_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);

            $iconPath = 'uploads/' . $filename;
            session(['user_icon' => $iconPath]);
        }

        // 3. ユーザー作成
        $user = User::create([
            'name' => $request->nickname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $iconPath,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/')->with('success', '🎉 会員登録が完了しました！');
    }
}