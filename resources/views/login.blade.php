<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - ログイン</title>
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
    class="bg-black text-white h-screen w-screen flex flex-col justify-between items-center py-4 px-4 overflow-hidden">

    <!-- 【上部】ロゴ & ログインカードエリア -->
    <div class="w-full max-w-sm sm:max-w-md flex flex-col items-center justify-center flex-1 space-y-3 sm:space-y-4">

        <!-- ヘッダータイトル & キャッチコピー（間隔を少し広げました） -->
        <div class="text-center">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-amber-500 tracking-wide">MovieMood</h1>
            <p class="text-xs sm:text-sm font-medium text-gray-200 leading-snug pt-5 sm:pt-6">
                あなたの『今の気分』が、次に観る映画を決める。
            </p>
        </div>

        <!-- ログインカード -->
        <div
            class="w-full bg-[#121824] border border-gray-700/80 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-2xl space-y-4">

            <h2 class="text-lg sm:text-xl font-bold text-center text-amber-500">ログイン</h2>

            <form action="/login" method="POST" class="space-y-3 sm:space-y-4">
                @csrf
                <!-- メールアドレス -->
                <div class="space-y-1">
                    <label class="block text-xs font-semibold text-gray-300 pl-1">メールアドレス (ID)</label>
                    <input type="email" name="email" placeholder="メールアドレス [ID]" required
                        class="w-full py-2.5 sm:py-3 px-4 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs sm:text-sm placeholder-gray-400">
                </div>

                <!-- パスワード -->
                <div class="space-y-1">
                    <label class="block text-xs font-semibold text-gray-300 pl-1">パスワード</label>
                    <input type="password" name="password" placeholder="パスワード" required
                        class="w-full py-2.5 sm:py-3 px-4 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs sm:text-sm placeholder-gray-400">
                </div>

                <!-- ログインボタン -->
                <button type="submit"
                    class="w-full py-2.5 sm:py-3 bg-amber-500 hover:bg-amber-600 text-black font-bold rounded-full transition duration-200 shadow-md text-sm mt-1">
                    ログイン
                </button>
            </form>

            <div class="text-center pt-1">
                <a href="/register"
                    class="text-xs text-gray-300 underline hover:text-amber-400 transition-colors">新規登録はこちら</a>
            </div>

        </div>

    </div>

    <!-- 【下部】POPULAR MOVIES カルーセル -->
    <div class="w-full mt-2 space-y-2 flex-shrink-0">
        <!-- ラベルテキスト -->
        <p class="text-center text-[11px] sm:text-xs font-bold text-gray-400 tracking-wider">
            POPULAR MOVIES (タップで詳細へ)
        </p>

        <!-- ポスター流れるエリア -->
        <div class="overflow-hidden w-full">
            <div class="animate-scroll flex gap-3 px-2">
                <!-- 1周目 -->
                @foreach($popularMovies as $movie)
                    @if(!empty($movie['poster_path']))
                        <a href="{{ route('movies.show', $movie['id']) }}"
                            class="flex-shrink-0 transition-transform duration-200 hover:scale-105">
                            <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}"
                                title="{{ $movie['title'] }}"
                                class="w-24 h-36 sm:w-28 sm:h-40 object-cover rounded-xl shadow-lg border border-gray-800">
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
                                class="w-24 h-36 sm:w-28 sm:h-40 object-cover rounded-xl shadow-lg border border-gray-800">
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

</body>

</html>