<x-app-layout :popularMovies="$popularMovies ?? []">
    <div class="max-w-2xl mx-auto space-y-8">

        <!-- 1. メイン編集カード -->
        <div class="bg-gray-900/90 rounded-2xl p-6 sm:p-8 border border-gray-800 shadow-xl space-y-6">

            <!-- タイトル -->
            <div class="text-center space-y-1">
                <h1 class="text-xl sm:text-2xl font-extrabold text-amber-500">アカウント編集</h1>
                <p class="text-xs text-gray-400">プロフィール情報やパスワードの変更が行えます</p>
            </div>

            <!-- エラー表示 -->
            @if ($errors->any())
                <div class="p-4 bg-red-900/40 border border-red-500/50 rounded-xl text-xs text-red-200 space-y-1.5">
                    <p class="font-bold text-red-400">入力内容をご確認ください：</p>
                    <ul class="list-disc list-inside space-y-1 text-red-300">
                        @php
                            $errorMessages = $errors->all();
                        @endphp
                        @foreach ($errorMessages as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 編集フォーム -->
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- アバター画像変更 -->
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="relative w-24 h-24 rounded-full border-2 border-amber-500 overflow-hidden bg-gray-800 flex items-center justify-center shadow-lg">
                        @php
                            $user = auth()->user();
                            $avatarPath = $user->avatar ?? $user->icon ?? $user->icon_path ?? null;
                        @endphp

                        @if($avatarPath)
                            <img id="avatar-preview"
                                src="{{ str_starts_with($avatarPath, 'http') ? $avatarPath : asset('storage/' . $avatarPath) }}"
                                class="w-full h-full object-cover"
                                onError="this.onerror=null; this.src='{{ asset($avatarPath) }}';">
                        @else
                            <svg id="avatar-placeholder" class="w-12 h-12 text-gray-400" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                        @endif
                    </div>

                    <label
                        class="cursor-pointer text-xs text-amber-400 hover:text-amber-300 font-bold bg-amber-500/10 hover:bg-amber-500/20 px-4 py-1.5 rounded-full border border-amber-500/30 transition">
                        画像を選択・変更
                        <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden"
                            onchange="previewAndValidateImage(event)">
                    </label>

                    <p id="size-error"
                        class="hidden text-xs text-red-400 font-bold bg-red-950/60 border border-red-800 px-3 py-1.5 rounded-lg">
                        ⚠️ 画像サイズが2MBを超えています。別の画像を選択してください。
                    </p>
                    <span id="size-notice" class="text-[10px] text-gray-500">※ 2MB以下の画像を選択してください</span>
                </div>

                <!-- ニックネーム -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-300">ニックネーム</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                        placeholder="ニックネーム"
                        class="w-full bg-gray-100 border-none focus:ring-2 focus:ring-amber-500 rounded-full px-5 py-3 text-xs text-gray-900 placeholder-gray-400">
                </div>

                <!-- メールアドレス -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-300">メールアドレス</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                        placeholder="メールアドレス"
                        class="w-full bg-gray-100 border-none focus:ring-2 focus:ring-amber-500 rounded-full px-5 py-3 text-xs text-gray-900 placeholder-gray-400">
                </div>

                <hr class="border-gray-800 my-4">

                <!-- パスワード変更セクション（再設定画面と揃えたデザイン） -->
                <div class="space-y-4">
                    <h2 class="text-sm font-bold text-gray-200">パスワードの変更 <span
                            class="text-xs text-gray-500 font-normal">（変更する場合のみ入力）</span></h2>

                    <!-- 新しいパスワード -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-300">新しいパスワード</label>
                        <input type="password" name="password" placeholder="新しいパスワード（8文字以上）"
                            class="w-full bg-gray-100 border-none focus:ring-2 focus:ring-amber-500 rounded-full px-5 py-3 text-xs text-gray-900 placeholder-gray-400">
                    </div>

                    <!-- 新しいパスワード（確認用） -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-300">新しいパスワード（確認）</label>
                        <input type="password" name="password_confirmation" placeholder="もう一度入力"
                            class="w-full bg-gray-100 border-none focus:ring-2 focus:ring-amber-500 rounded-full px-5 py-3 text-xs text-gray-900 placeholder-gray-400">
                    </div>
                </div>

                <!-- アクションボタン -->
                <div class="pt-6 flex items-center justify-between gap-4 border-t border-gray-800">
                    <a href="{{ route('mypage') }}" class="text-xs font-bold text-gray-400 hover:text-white transition">
                        キャンセル（戻る）
                    </a>

                    <button type="submit" id="submit-btn"
                        class="px-8 py-3 bg-amber-500 hover:bg-amber-400 text-black font-extrabold rounded-full text-xs transition shadow-lg">
                        変更を保存する
                    </button>
                </div>
            </form>

        </div>

        <!-- 2. アカウント削除カード -->
        <div class="bg-red-950/20 rounded-2xl p-6 sm:p-8 border border-red-900/50 shadow-xl space-y-4">
            <div class="space-y-1">
                <h2 class="text-base font-bold text-red-400">アカウントの削除</h2>
                <p class="text-xs text-gray-400">アカウントを削除すると、これまでのレビューや保存したデータがすべて消去され、復元できなくなります。</p>
            </div>

            <form action="{{ route('profile.destroy') }}" method="POST"
                onsubmit="return confirm('本当にアカウントを削除しますか？この操作は取り消せません。');" class="pt-2">
                @csrf
                @method('DELETE')

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-5 py-2.5 bg-red-600/80 hover:bg-red-600 text-white font-bold rounded-xl text-xs transition shadow-md">
                        アカウントを削除する
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
        function previewAndValidateImage(event) {
            const input = event.target;
            const file = input.files[0];
            const sizeError = document.getElementById('size-error');
            const sizeNotice = document.getElementById('size-notice');
            const submitBtn = document.getElementById('submit-btn');

            if (!file) return;

            const maxSize = 2 * 1024 * 1024;

            if (file.size > maxSize) {
                sizeError.classList.remove('hidden');
                sizeNotice.classList.add('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                input.value = '';
                return;
            }

            sizeError.classList.add('hidden');
            sizeNotice.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

            const reader = new FileReader();
            reader.onload = function () {
                const preview = document.getElementById('avatar-preview');
                const placeholder = document.getElementById('avatar-placeholder');

                if (preview) {
                    preview.src = reader.result;
                } else if (placeholder) {
                    placeholder.outerHTML = `<img id="avatar-preview" src="${reader.result}" class="w-full h-full object-cover">`;
                }
            }
            reader.readAsDataURL(file);
        }
    </script>
</x-app-layout>