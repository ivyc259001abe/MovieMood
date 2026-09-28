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
        return view(view()->exists('register') ? 'register' : 'auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. バリデーション（name, profile_photo で統一）
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
        ], [
            'name.required' => 'ニックネームを入力してください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'profile_photo.image' => 'アップロードできるのは画像ファイルのみです。',
            'profile_photo.max' => '画像サイズは10MB以下にしてください。',
        ]);

        // 2. アバター画像の保存処理（任意）
        $iconPath = null;

        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $file = $request->file('profile_photo');
            $filename = 'icon_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // uploads ディレクトリが存在しない場合は自動作成
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0755, true);
            }

            $file->move(public_path('uploads'), $filename);

            $iconPath = 'uploads/' . $filename;
            session(['user_icon' => $iconPath]);
        }

        // 3. ユーザー作成（name にニックネームを保存）
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $iconPath,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/')->with('success', '🎉 会員登録が完了しました！');
    }
}