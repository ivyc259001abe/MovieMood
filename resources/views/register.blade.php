<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - 新規登録</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* 横へスムーズに流れるアニメーション */
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
    class="bg-black text-white h-screen w-screen flex flex-col justify-between items-center py-2 sm:py-3 px-4 overflow-hidden">

    <!-- 【上部】ロゴ & 会員登録カードエリア -->
    <div class="w-full max-w-sm sm:max-w-md flex flex-col items-center justify-center flex-1 my-auto">

        <!-- ヘッダータイトル -->
        <div class="text-center mb-1 sm:mb-2">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-amber-500 tracking-wide">MovieMood</h1>
        </div>

        <!-- 新規登録カード -->
        <div class="w-full bg-[#121824] border border-gray-700/80 rounded-2xl p-4 sm:p-5 shadow-2xl space-y-2.5">

            <!-- 見出し -->
            <h2 class="text-base sm:text-lg font-bold text-center text-amber-500">新規アカウント登録</h2>

            <form action="/register" method="POST" enctype="multipart/form-data" class="space-y-2">
                @csrf

                <!-- アイコン画像（リアルタイムプレビュー対応） -->
                <div class="flex flex-col items-center justify-center">
                    <label class="text-[10px] sm:text-xs font-semibold text-gray-300 mb-0.5">
                        アイコン画像 <span class="text-amber-400">※任意</span>
                    </label>
                    <label
                        class="relative w-10 h-10 sm:w-12 sm:h-12 rounded-full border-2 border-dashed border-gray-500 flex flex-col items-center justify-center cursor-pointer hover:border-amber-500 transition-colors bg-gray-800/50 overflow-hidden group">

                        <!-- 選択前のプラスアイコン -->
                        <div id="icon-placeholder" class="flex flex-col items-center justify-center">
                            <span class="text-lg text-gray-400 font-light leading-none">+</span>
                        </div>

                        <!-- 選択後のプレビュー画像 -->
                        <img id="icon-preview" class="hidden w-full h-full object-cover rounded-full" alt="アイコンプレビュー">

                        <!-- ファイル入力 -->
                        <input type="file" name="icon" id="icon-input" class="hidden" accept="image/*"
                            onchange="previewImage(event)">
                    </label>
                    <span class="text-[9px] text-gray-400 mt-0.5">画像を選択</span>
                </div>

                <!-- ニックネーム -->
                <div>
                    <label class="block text-[11px] font-semibold text-gray-300 pl-1 mb-0.5">ニックネーム</label>
                    <input type="text" name="name" placeholder="ニックネーム" required
                        class="w-full py-1.5 px-3 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs placeholder-gray-400">
                </div>

                <!-- メールアドレス -->
                <div>
                    <label class="block text-[11px] font-semibold text-gray-300 pl-1 mb-0.5">メールアドレス (ID)</label>
                    <input type="email" name="email" placeholder="メールアドレス" required
                        class="w-full py-1.5 px-3 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs placeholder-gray-400">
                </div>

                <!-- パスワード -->
                <div>
                    <label class="block text-[11px] font-semibold text-gray-300 pl-1 mb-0.5">パスワード</label>
                    <input type="password" name="password" placeholder="パスワード (8文字以上)" required
                        class="w-full py-1.5 px-3 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs placeholder-gray-400">
                </div>

                <!-- パスワード (確認) -->
                <div>
                    <label class="block text-[11px] font-semibold text-gray-300 pl-1 mb-0.5">パスワード (確認)</label>
                    <input type="password" name="password_confirmation" placeholder="パスワードを再入力" required
                        class="w-full py-1.5 px-3 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs placeholder-gray-400">
                </div>

                <!-- 登録ボタン -->
                <button type="submit"
                    class="w-full py-2 bg-amber-500 hover:bg-amber-600 text-black font-bold rounded-full transition duration-200 shadow-md text-xs sm:text-sm mt-1">
                    登録する
                </button>
            </form>

            <div class="text-center pt-0.5">
                <a href="/login"
                    class="text-[11px] text-gray-300 underline hover:text-amber-400 transition-colors">すでにアカウントをお持ちの方はこちら</a>
            </div>

        </div>

    </div>

    <!-- 【下部】POPULAR MOVIES カルーセル -->
    <div class="w-full mt-1 space-y-1 flex-shrink-0">
        <!-- ラベルテキスト -->
        <p class="text-center text-[10px] font-bold text-gray-400 tracking-wider">
            POPULAR MOVIES (タップで詳細へ)
        </p>

        <!-- ポスター流れるエリア（少しサイズをコンパクトにして画面内に収める） -->
        <div class="overflow-hidden w-full">
            <div class="animate-scroll flex gap-2.5 px-2">
                <!-- 1周目 -->
                @foreach($popularMovies as $movie)
                    @if(!empty($movie['poster_path']))
                        <a href="{{ route('movies.show', $movie['id']) }}"
                            class="flex-shrink-0 transition-transform duration-200 hover:scale-105">
                            <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}"
                                title="{{ $movie['title'] }}"
                                class="w-16 h-24 sm:w-20 sm:h-28 object-cover rounded-lg shadow-lg border border-gray-800">
                        </a>
                    @endif
                @endforeach

                <!-- 2周目（ループ用） -->
                @foreach($popularMovies as $movie)
                    @if(!empty($movie['poster_path']))
                        <a href="{{ route('movies.show', $movie['id']) }}"
                            class="flex-shrink-0 transition-transform duration-200 hover:scale-105">
                            <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}"
                                title="{{ $movie['title'] }}"
                                class="w-16 h-24 sm:w-20 sm:h-28 object-cover rounded-lg shadow-lg border border-gray-800">
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- 画像プレビュー用のJavaScript -->
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('icon-preview');
            const placeholder = document.getElementById('icon-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</body>

</html>