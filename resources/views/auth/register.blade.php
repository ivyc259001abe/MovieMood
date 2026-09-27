<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - 新規アカウント登録</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-scroll {
            display: flex;
            width: 200%;
            animation: scroll 35s linear infinite;
        }

        .animate-scroll:hover {
            animation-play-state: paused;
        }
    </style>
</head>

<body
    class="bg-black text-white h-screen w-screen flex flex-col justify-between items-center py-2 px-4 overflow-hidden">

    <!-- 【上部】ロゴ & アカウント登録カードエリア -->
    <div class="w-full max-w-xs sm:max-w-sm flex flex-col items-center justify-center flex-1 my-auto space-y-2">

        <!-- ヘッダータイトル -->
        <div class="text-center">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-amber-500 tracking-wide">MovieMood</h1>
            <p class="text-[11px] sm:text-xs font-medium text-gray-300 leading-tight pt-1">
                あなたの『今の気分』が、次に観る映画を決める。
            </p>
        </div>

        <!-- アカウント登録カード -->
        <div class="w-full bg-[#121824] border border-gray-700/80 rounded-2xl p-4 shadow-2xl space-y-2.5">

            <h2 class="text-base sm:text-lg font-bold text-center text-amber-500">新規アカウント登録</h2>

            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                @csrf

                <!-- 1. アイコンプレビュー ＆ アップロード -->
                <div class="flex flex-col items-center space-y-1">
                    <div
                        class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-amber-500 overflow-hidden bg-gray-800 flex items-center justify-center shadow-md">

                        <svg id="defaultIcon" class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>

                        <img id="iconPreview" class="w-full h-full object-cover hidden" alt="プレビュー">

                    </div>
                    <label class="cursor-pointer text-xs font-semibold text-amber-500 hover:text-amber-400 underline">
                        アイコン画像を選択 (任意)
                        <input type="file" name="icon" accept="image/*" class="hidden" onchange="previewImage(event)">
                    </label>
                </div>

                <!-- 2. ニックネーム -->
                <div class="space-y-0.5">
                    <label class="block text-xs font-semibold text-gray-300 pl-1">ニックネーム</label>
                    <input type="text" name="nickname" value="{{ old('nickname') }}" placeholder="例: たろう" required
                        class="w-full py-1.5 px-3 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs placeholder-gray-400">
                </div>

                <!-- 3. メールアドレス -->
                <div class="space-y-0.5">
                    <label class="block text-xs font-semibold text-gray-300 pl-1">メールアドレス</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="example@mail.com" required
                        class="w-full py-1.5 px-3 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs placeholder-gray-400">
                </div>

                <!-- 4. パスワード -->
                <div class="space-y-0.5">
                    <label class="block text-xs font-semibold text-gray-300 pl-1">パスワード</label>
                    <input type="password" name="password" placeholder="8文字以上" required autocomplete="new-password"
                        class="w-full py-1.5 px-3 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs placeholder-gray-400">
                </div>

                <!-- 5. パスワード（確認用） -->
                <div class="space-y-0.5">
                    <input type="password" name="password_confirmation" placeholder="パスワード(確認用)" required
                        autocomplete="new-password"
                        class="w-full py-1.5 px-3 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs placeholder-gray-400">
                </div>

                <!-- エラーメッセージ表示エリア -->
                @if ($errors->any())
                    <div class="p-1.5 bg-red-900/50 border border-red-500 rounded-xl text-center space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <p class="text-[10px] text-red-200 font-bold">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- 新規登録ボタン -->
                <button type="submit"
                    class="w-full py-2 bg-amber-500 hover:bg-amber-600 text-black font-bold rounded-full transition duration-200 shadow-md text-xs mt-1">
                    アカウントを作成する
                </button>
            </form>

            <!-- ログイン画面への誘導 -->
            <div class="text-center pt-2 pb-0.5">
                <a href="{{ route('login') }}"
                    class="text-xs font-semibold text-gray-300 hover:text-amber-500 underline transition-colors">
                    すでにアカウントをお持ちの方はこちら (ログイン)
                </a>
            </div>

        </div>

    </div>

    <!-- 【下部】POPULAR MOVIES カルーセル (API連携) -->
    <div class="w-full space-y-1 flex-shrink-0 pb-1">
        <p class="text-center text-[10px] font-bold text-gray-400 tracking-wider">
            POPULAR MOVIES (タップで詳細へ)
        </p>

        <div class="overflow-hidden w-full">
            <div class="animate-scroll flex gap-2 px-2">
                @php $movies = $popularMovies ?? []; @endphp

                <!-- 1周目 -->
                @foreach($movies as $movie)
                    @if(!empty($movie['poster_path']))
                        <a href="{{ route('movies.show', $movie['id']) }}"
                            class="flex-shrink-0 transition-transform duration-200 hover:scale-105">
                            <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}"
                                title="{{ $movie['title'] }}" loading="lazy"
                                class="w-16 h-24 sm:w-20 sm:h-28 object-cover rounded-lg shadow-lg border border-gray-800">
                        </a>
                    @endif
                @endforeach

                <!-- 2周目 -->
                @foreach($movies as $movie)
                    @if(!empty($movie['poster_path']))
                        <a href="{{ route('movies.show', $movie['id']) }}"
                            class="flex-shrink-0 transition-transform duration-200 hover:scale-105">
                            <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}"
                                title="{{ $movie['title'] }}" loading="lazy"
                                class="w-16 h-24 sm:w-20 sm:h-28 object-cover rounded-lg shadow-lg border border-gray-800">
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- JavaScript: 新規画像選択時の即時プレビュー -->
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
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