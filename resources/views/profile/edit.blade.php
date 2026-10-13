<x-app-layout :popularMovies="$popularMovies ?? []">
    @php
        $user = auth()->user();
        $avatarPath = $user->avatar ?? $user->icon ?? $user->icon_path ?? null;
        $hasAvatar = !empty($avatarPath);
    @endphp

    <div class="max-w-2xl mx-auto space-y-8">

        {{-- メイン編集カード --}}
        <div class="bg-gray-900/90 rounded-2xl p-6 sm:p-8 border border-gray-800 shadow-xl space-y-6">

            {{-- タイトル --}}
            <div class="text-center space-y-1">
                <h1 class="text-xl sm:text-2xl font-extrabold text-amber-500">
                    アカウント編集
                </h1>
                <p class="text-xs text-gray-400">
                    プロフィール情報やパスワードの変更が行えます
                </p>
            </div>

            {{-- エラー表示 --}}
            @if ($errors->any())
                <div class="p-4 bg-red-900/40 border border-red-500/50 rounded-xl text-xs text-red-200 space-y-1.5">
                    <p class="font-bold text-red-400">入力内容をご確認ください：</p>
                    <ul class="list-disc list-inside space-y-1 text-red-300">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- プロフィール編集フォーム --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5"
                id="profile-form">
                @csrf
                @method('PUT')

                {{-- プロフィール画像 --}}
                <div class="flex flex-col items-center gap-3">

                    <div id="avatar-container"
                        class="relative w-24 h-24 rounded-full border-2 border-amber-500 overflow-hidden bg-gray-800 flex items-center justify-center shadow-lg shrink-0">
                        <img id="avatar-preview"
                            src="{{ $hasAvatar ? (str_starts_with($avatarPath, 'http') ? $avatarPath : asset($avatarPath)) : '' }}"
                            alt="プロフィール画像" class="{{ $hasAvatar ? '' : 'hidden' }} w-full h-full object-cover">

                        <svg id="avatar-placeholder" class="{{ $hasAvatar ? 'hidden' : '' }} w-12 h-12 text-gray-400"
                            fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    </div>

                    <label for="avatar-input"
                        class="cursor-pointer text-xs text-amber-400 hover:text-amber-300 font-bold bg-amber-500/10 hover:bg-amber-500/20 px-4 py-2 rounded-full border border-amber-500/30 transition">
                        画像を選択・変更
                    </label>

                    <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden">

                    {{-- 削除する場合は1を送信 --}}
                    <input type="hidden" name="remove_avatar" id="remove-avatar" value="0">

                    <button type="button" id="remove-avatar-btn"
                        class="text-xs text-red-400 hover:text-red-300 font-bold bg-red-500/10 hover:bg-red-500/20 px-4 py-2 rounded-full border border-red-500/30 transition">
                        画像を削除
                    </button>

                    <p id="avatar-status" class="text-[11px] text-gray-400 text-center">
                        {{ $hasAvatar ? '現在の画像を使用しています' : '画像なしで保存できます' }}
                    </p>

                    <p id="size-error"
                        class="hidden text-xs text-red-400 font-bold bg-red-950/60 border border-red-800 px-3 py-1.5 rounded-lg text-center"
                        role="alert">
                        ⚠️ 画像サイズが2MBを超えています。2MB以下の画像を選択してください。
                    </p>

                    <span id="size-notice" class="text-[10px] text-gray-500">
                        ※ 画像を設定する場合は2MB以下の画像を選択してください
                    </span>
                </div>

                {{-- ニックネーム --}}
                <div class="space-y-1">
                    <label for="nickname" class="text-xs font-bold text-gray-300">
                        ニックネーム
                    </label>
                    <input type="text" id="nickname" name="nickname"
                        value="{{ old('nickname', $user->nickname ?? $user->name) }}" required placeholder="ニックネーム"
                        class="w-full bg-gray-100 border-none focus:ring-2 focus:ring-amber-500 rounded-full px-5 py-3 text-xs text-gray-900 placeholder-gray-400">
                </div>

                {{-- メールアドレス --}}
                <div class="space-y-1">
                    <label for="email" class="text-xs font-bold text-gray-300">
                        メールアドレス
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        placeholder="メールアドレス"
                        class="w-full bg-gray-100 border-none focus:ring-2 focus:ring-amber-500 rounded-full px-5 py-3 text-xs text-gray-900 placeholder-gray-400">
                </div>

                <hr class="border-gray-800 my-4">

                {{-- パスワード変更 --}}
                <div class="space-y-4">
                    <div>
                        <h2 class="text-sm font-bold text-gray-200">
                            パスワードの変更
                        </h2>
                        <p class="text-[11px] text-gray-500 mt-1">
                            変更しない場合は、何も入力しないでください。
                        </p>
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="text-xs font-bold text-gray-300">
                            新しいパスワード
                        </label>
                        <input type="password" id="password" name="password" autocomplete="new-password" minlength="8"
                            placeholder="新しいパスワード（8文字以上）"
                            class="w-full bg-gray-100 border-none focus:ring-2 focus:ring-amber-500 rounded-full px-5 py-3 text-xs text-gray-900 placeholder-gray-400">
                    </div>

                    <div class="space-y-1">
                        <label for="password_confirmation" class="text-xs font-bold text-gray-300">
                            新しいパスワード（確認）
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            autocomplete="new-password" minlength="8" placeholder="新しいパスワードをもう一度入力"
                            class="w-full bg-gray-100 border-none focus:ring-2 focus:ring-amber-500 rounded-full px-5 py-3 text-xs text-gray-900 placeholder-gray-400">
                    </div>
                </div>

                {{-- 保存・キャンセル --}}
                <div class="pt-6 flex items-center justify-between gap-4 border-t border-gray-800">
                    <a href="{{ route('mypage') }}" class="text-xs text-gray-400 hover:text-white transition">
                        キャンセル（戻る）
                    </a>

                    <button type="submit" id="submit-btn"
                        class="bg-amber-500 hover:bg-amber-400 text-gray-950 font-extrabold text-xs px-6 py-3 rounded-full shadow-lg transition">
                        変更を保存する
                    </button>
                </div>
            </form>
        </div>

        {{-- アカウント削除 --}}
        <div class="bg-red-950/20 rounded-2xl p-6 border border-red-900/40 space-y-4">
            <div class="space-y-1">
                <h2 class="text-sm font-extrabold text-red-400">
                    アカウントの削除
                </h2>
                <p class="text-xs text-gray-400 leading-relaxed">
                    アカウントを削除すると、登録情報などを復元できなくなる場合があります。
                    この操作は取り消せません。
                </p>
            </div>

            <form action="{{ route('profile.destroy') }}" method="POST"
                onsubmit="return confirm('本当にアカウントを削除しますか？この操作は取り消せません。');" class="pt-2">
                @csrf
                @method('DELETE')

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-500 text-white font-bold text-xs px-5 py-2.5 rounded-full transition">
                        アカウントを削除する
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- プロフィール画像のプレビュー・削除・サイズ確認 --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const avatarInput = document.getElementById('avatar-input');
            const avatarPreview = document.getElementById('avatar-preview');
            const avatarPlaceholder = document.getElementById('avatar-placeholder');
            const removeAvatarInput = document.getElementById('remove-avatar');
            const removeAvatarButton = document.getElementById('remove-avatar-btn');
            const avatarStatus = document.getElementById('avatar-status');
            const sizeError = document.getElementById('size-error');
            const sizeNotice = document.getElementById('size-notice');
            const submitButton = document.getElementById('submit-btn');

            const maxSize = 2 * 1024 * 1024;

            function showPlaceholder() {
                avatarPreview.src = '';
                avatarPreview.classList.add('hidden');
                avatarPlaceholder.classList.remove('hidden');
            }

            function showPreview(imageUrl) {
                avatarPreview.src = imageUrl;
                avatarPreview.classList.remove('hidden');
                avatarPlaceholder.classList.add('hidden');
            }

            function resetSizeError() {
                sizeError.classList.add('hidden');
                sizeNotice.classList.remove('hidden');
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            avatarInput.addEventListener('change', function () {
                const file = this.files && this.files[0];

                if (!file) {
                    return;
                }

                if (file.size > maxSize) {
                    this.value = '';
                    sizeError.classList.remove('hidden');
                    sizeNotice.classList.add('hidden');
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    avatarStatus.textContent = '2MB以下の画像を選び直してください';
                    return;
                }

                resetSizeError();

                // 新しい画像を選んだ場合は削除指示を解除
                removeAvatarInput.value = '0';
                avatarStatus.textContent = '新しい画像を選択しています';

                const reader = new FileReader();

                reader.onload = function (event) {
                    showPreview(event.target.result);
                };

                reader.readAsDataURL(file);
            });

            removeAvatarButton.addEventListener('click', function () {
                // 選択中の画像も解除
                avatarInput.value = '';
                removeAvatarInput.value = '1';

                showPlaceholder();
                resetSizeError();

                avatarStatus.textContent = '画像を削除して保存します';
            });
        });
    </script>
</x-app-layout>