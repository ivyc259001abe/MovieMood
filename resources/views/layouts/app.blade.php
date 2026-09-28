<!DOCTYPE html>
<html lang="ja" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'MovieMood' }}</title>

    <!-- Tailwind CSS 警告抑制スクリプト -->
    <script>
      const originalWarn = console.warn;
      console.warn = (...args) => {
        if (args[0] && typeof args[0] === 'string' && args[0].includes('cdn.tailwindcss.com')) return;
        originalWarn(...args);
      };
    </script>
    <!-- Tailwind CSS & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        @keyframes loop-scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-loop-scroll {
            animation: loop-scroll 100s linear infinite;
        }
        .animate-loop-scroll:hover {
            animation-play-state: paused;
        }
    </style>
</head>
<body class="bg-black text-white min-h-screen flex flex-col font-sans antialiased w-full selection:bg-amber-500 selection:text-black overflow-x-hidden">

    <!-- 🌟 ヘッダーナビゲーション -->
    <header class="bg-[#0b0e14] border-b border-gray-800/80 sticky top-0 z-50 w-full py-2">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 flex items-center justify-between">

            <!-- 左：MovieMoodロゴ ＆ キャッチコピー -->
            <div class="flex flex-col items-start justify-center">
                <a href="{{ Auth::check() ? route('home') : '#' }}" 
                   title="HOME画面へ戻る" 
                   class="group text-lg sm:text-2xl font-extrabold text-amber-500 hover:text-amber-400 transition-all duration-200 tracking-wide shrink-0 no-underline inline-flex items-center gap-1.5">
                    <span class="group-hover:scale-105 transition-transform duration-200">MovieMood</span>
                </a>
                <p class="text-[10px] sm:text-xs text-gray-300 font-bold m-0 mt-0.5 pl-0.5 pointer-events-none">
                    〜 あなたの「今の気分」が、次に観る映画を決める。 〜
                </p>
            </div>

            <!-- 右：ナビゲーション -->
            <div class="flex items-center gap-2 sm:gap-4 text-xs font-bold">
                @auth
                    <!-- マイページ -->
                    <a href="{{ route('mypage') }}" class="flex items-center gap-1.5 sm:gap-2 bg-[#161f2c] hover:bg-gray-800 border border-amber-500/50 rounded-full py-1 px-2.5 sm:px-3.5 transition shadow-sm group shrink-0 no-underline">
                        <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-gray-900 flex items-center justify-center shrink-0 overflow-hidden ring-1 ring-amber-400">
                            @if(Auth::check() && Auth::user()->avatar)
                                <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-user text-gray-400 text-[9px] sm:text-[10px]"></i>
                            @endif
                        </div>
                        <span class="text-[11px] sm:text-xs text-gray-200 group-hover:text-amber-400 transition max-w-[70px] sm:max-w-[130px] truncate">
                            {{ Str::limit(Auth::user()->name ?? 'マイページ', 15, '') }}
                        </span>
                    </a>

                    <!-- コミュニティ -->
                    <a href="{{ route('community.index') }}" class="text-gray-300 hover:text-amber-400 transition flex items-center gap-1 px-1 py-1 shrink-0 no-underline" title="コミュニティ">
                        <i class="fa-solid fa-users text-amber-500 text-sm sm:text-xs"></i>
                        <span class="hidden sm:inline">コミュニティ</span>
                    </a>

                    <!-- ログアウト -->
                    <form method="POST" action="{{ route('logout') }}" class="inline shrink-0">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-400 transition flex items-center gap-1 px-1 py-1 font-bold cursor-pointer" title="ログアウト">
                            <i class="fa-solid fa-right-from-bracket text-red-500 text-sm sm:text-xs"></i>
                            <span class="hidden sm:inline">ログアウト</span>
                        </button>
                    </form>
                @endauth
            </div>

        </div>
    </header>

    <!-- 📱 メインコンテンツエリア -->
    <main class="flex-grow w-full">
        {{ $slot }}
    </main>

    <!-- 🔻 フッター ＆ POPULAR MOVIES カルーセル -->
    @php
        if (empty($popularMovies)) {
            try {
                $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));
                if ($apiKey) {
                    $response = \Illuminate\Support\Facades\Http::get("https://api.themoviedb.org/3/movie/popular", [
                        'api_key' => $apiKey,
                        'language' => 'ja-JP',
                        'page' => 1,
                    ]);
                    $popularMovies = $response->successful() ? ($response->json()['results'] ?? []) : [];
                }
            } catch (\Exception $e) {
                $popularMovies = [];
            }
        }
        $loopMovies = array_merge($popularMovies, $popularMovies);
    @endphp

    <footer class="bg-black border-t border-gray-900 mt-6 text-gray-400 text-xs w-full shrink-0">

        <!-- 人気映画カルーセル (ログイン画面と同一仕様) -->
        @if(!empty($popularMovies))
            <div class="border-b border-gray-900 py-2 bg-black w-full overflow-hidden">
                <div class="w-full text-center mb-1.5 px-4">
                    <span class="text-[10px] font-extrabold text-gray-400 tracking-wider uppercase">
                        POPULAR MOVIES @auth <span class="text-gray-500 font-normal">（タップで詳細へ）</span> @endauth
                    </span>
                </div>

                <!-- 横スクロールCSSアニメーションエリア -->
                <div class="w-full overflow-hidden whitespace-nowrap flex">
                    <div class="flex gap-2 animate-loop-scroll will-change-transform">
                        @foreach($loopMovies as $movie)
                            <a href="{{ route('movies.show', $movie['id'] ?? 0) }}" 
                               class="flex-none w-[110px] h-[150px] bg-gray-900 rounded-md overflow-hidden border border-gray-800 relative shadow-md group">
                                @if(!empty($movie['poster_path']))
                                    <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}" alt="{{ $movie['title'] ?? '' }}" class="w-[110px] h-[150px] object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-600 text-[10px]">No Image</div>
                                @endif
                                @if(isset($movie['vote_average']) && $movie['vote_average'] > 0)
                                    <div class="absolute top-1 right-1 bg-black/85 border border-amber-500/80 text-amber-400 text-[9px] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-sm">
                                        ★ {{ number_format((float) $movie['vote_average'], 1) }}
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- 最下部：統一されたコピーライト表記 -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 text-center text-[10px] text-gray-500 font-medium">
            <p class="m-0">&copy; 2026 MovieMood. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>