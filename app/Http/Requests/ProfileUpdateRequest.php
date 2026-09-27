<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            // 🌟 10MB (10240KB) まで許可（タイポを修正しました）
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
        ];
    }

    /**
     * カスタムエラーメッセージの定義
     */
    public function messages(): array
    {
        return [
            'avatar.image' => 'アップロードされたファイルは画像ではありません。',
            'avatar.mimes' => '画像形式は JPG、PNG、GIF、WebP のみ対応しています。',
            'avatar.max' => '画像サイズが大きすぎます。10MB以下の画像を選択してください。',
            'name.required' => 'ニックネームを入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.unique' => 'このメールアドレスはすでに使用されています。',
        ];
    }
}