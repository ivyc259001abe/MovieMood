<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - アカウント編集</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
    class="bg-black text-white h-screen w-screen flex flex-col justify-between items-center py-2 px-4 overflow-hidden">

    <!-- 【上部】ロゴ & アカウント編集カードエリア -->
    <div class="w-full max-w-xs sm:max-w-sm flex flex-col items-center justify-center flex-1 my-auto space-y-2">

        <!-- 🎬 ヘッダータイトル（MovieMoodロゴ：クリックでホームへ遷移） -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-block group">
                <h1
                    class="text-2xl sm:text-3xl font-extrabold text-amber-500 group-hover:text-amber-400 transition tracking-wide">
                    MovieMood
                </h1>
            </a>
            <p class="text-[11px] sm:text-xs font-medium text-gray-300 leading-tight pt-1">
                あなたの『今の気分』が、次に観る映画を決める。
            </p>
        </div>

        <!-- アカウント編集カード -->
        <div class="w-full bg-[#121824] border border-gray-700/80 rounded-2xl p-4 shadow-2xl space-y-2.5">

            <h2 class="text-base sm:text-lg font-bold text-center text-amber-500">アカウント編集</h2>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                @csrf
                @method('PUT')

                <!-- 1. アイコンプレビュー ＆ アップロード -->
                <div class="flex flex-col items-center space-y-1">
                    <div
                        class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-amber-500 overflow-hidden bg-gray-800 flex items-center justify-center shadow-md">

                        @php
                            $user = auth()->user();
                            $avatarPath = $user->avatar ?? $user->icon ?? $user->avatar_url ?? null;

                            $hasAvatar = false;
                            if ($avatarPath) {
                                if (str_starts_with($avatarPath, 'http')) {
                                    $userIcon = $avatarPath;
                                    $hasAvatar = true;
                                } elseif (file_exists(public_path($avatarPath))) {
                                    $userIcon = asset($avatarPath);
                                    $hasAvatar = true;
                                }
                            }
                        @endphp

                        @if($hasAvatar)
                            <!-- 設定済みアイコンを表示 -->
                            <img id="iconPreview" src="{{ $userIcon }}" alt="現在のアイコン" class="w-full h-full object-cover">
                        @else
                            <!-- 未設定時のデフォルト人型アイコン -->
                            <svg id="defaultIcon" class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                            <img id="iconPreview" class="w-full h-full object-cover hidden" alt="プレビュー">
                        @endif

                    </div>
                    <label for="avatarInput"
                        class="cursor-pointer text-[11px] font-semibold text-amber-500 hover:text-amber-400 underline">
                        画像を選択・変更
                    </label>
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden"
                        onchange="previewImage(event)">
                    <p class="text-[9px] text-gray-400">※ 2MB以下の写真を選択してください</p>
                </div>

                <!-- 2. ニックネーム -->
                <div class="space-y-0.5">
                    <label class="block text-[11px] font-semibold text-gray-300 pl-1">ニックネーム</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}"
                        placeholder="例: たろう" required
                        class="w-full py-1.5 px-3 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs placeholder-gray-400">
                </div>

                <!-- 3. メールアドレス -->
                <div class="space-y-0.5">
                    <label class="block text-[11px] font-semibold text-gray-300 pl-1">メールアドレス</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                        placeholder="example@mail.com" required
                        class="w-full py-1.5 px-3 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs placeholder-gray-400">
                </div>

                <!-- 4. 新しいパスワード -->
                <div class="space-y-0.5">
                    <label class="block text-[11px] font-semibold text-gray-300 pl-1">新しいパスワード <span
                            class="text-[9px] text-gray-400 font-normal">(変更する場合のみ)</span></label>
                    <input type="password" name="password" placeholder="8文字以上" autocomplete="new-password"
                        class="w-full py-1.5 px-3 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs placeholder-gray-400">
                </div>

                <!-- 5. パスワード（確認用） -->
                <div class="space-y-0.5">
                    <input type="password" name="password_confirmation" placeholder="パスワード(確認用)"
                        autocomplete="new-password"
                        class="w-full py-1.5 px-3 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs placeholder-gray-400">
                </div>

                <!-- 🌟 即時チェック用エラーメッセージエリア（JSから出力） -->
                <div id="jsErrorArea" class="hidden p-2 bg-red-900/80 border border-red-500 rounded-xl text-center">
                    <div id="jsErrorMessage" class="text-[11px] text-white font-bold leading-relaxed"></div>
                </div>
                <!-- エラーメッセージ表示エリア（サーバーからのエラー） -->
                @if ($errors->any())
                    <div class="p-2 bg-red-900/80 border border-red-500 rounded-xl text-center space-y-1">
                        @foreach ($errors->all() as $error)
                            <p class="text-[11px] text-red-100 font-bold">⚠️ {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- 変更保存ボタン -->
                <button type="submit" id="submitBtn"
                    class="w-full py-2 bg-amber-500 hover:bg-amber-600 text-black font-bold rounded-full transition duration-200 shadow-md text-xs mt-1">
                    変更を保存する
                </button>
            </form>

            <!-- 下部アクション -->
            <div class="flex items-center justify-between pt-1 px-1">
                <a href="{{ route('mypage') }}"
                    class="text-[11px] font-semibold text-gray-400 hover:text-white underline transition-colors">
                    キャンセル(戻る)
                </a>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="py-1 px-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full text-[11px] transition-colors shadow">
                        ログアウト
                    </button>
                </form>
            </div>

        </div>

    </div>

    <!-- 🎬 流れる共通カルーセル（コンポーネント1行のみ呼び出し） -->
    @include('components.carousel', ['popularMovies' => $popularMovies ?? []])

    <!-- JavaScript: 新規画像選択時の即時プレビュー & 容量事前チェック -->
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const jsErrorArea = document.getElementById('jsErrorArea');
            const jsErrorMessage = document.getElementById('jsErrorMessage');
            const submitBtn = document.getElementById('submitBtn');

            // エラー表示を一旦リセット
            jsErrorArea.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

            if (file) {
                // 🌟 ファイルサイズチェック (2MB = 2 * 1024 * 1024 バイト)
                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    jsErrorMessage.innerHTML = '⚠️ 画像サイズが大きすぎます（2MB以下にしてください）<br>別の画像を選択してください。';
                    jsErrorArea.classList.remove('hidden');

                    // 送信ボタンを無効化
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    return;
                }

                // プレビュー表示処理
                const reader = new FileReader();
                reader.onload = function (e) {
                    const output = document.getElementById('iconPreview');
                    const defaultIcon = document.getElementById('defaultIcon');

                    output.src = e.target.result;
                    output.classList.remove('hidden');

                    if (defaultIcon) {
                        defaultIcon.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    </script>

</body>

</html>