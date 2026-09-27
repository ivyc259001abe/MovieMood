<!DOCTYPE html>
<html lang="ja" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'MovieMood' }}</title>

    <!-- Tailwind CSS & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        /* スクロールバー非表示 */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-black text-white min-h-screen flex flex-col font-sans antialiased w-full selection:bg-amber-500 selection:text-black">

    <!-- 🌟 ヘッダーナビゲーション -->
    <header class="bg-[#0b0e14] border-b border-gray-800/80 sticky top-0 z-50 w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 sm:h-16 flex items-center justify-between">
            
            <!-- 左：MovieMoodロゴ -->
            <a href="{{ route('home') }}" class="text-xl sm:text-2xl font-extrabold text-amber-500 hover:text-amber-400 transition tracking-wide">
                MovieMood
            </a>

            <!-- 右：①マイページ（白背景＋ピンク∞アイコン） ➔ ②コミュニティ ➔ ③ログアウト（赤色） -->
            <div class="flex items-center gap-4 sm:gap-5 text-xs font-bold">
                @auth
                    <!-- ① マイページ -->
                    <a href="{{ route('mypage') }}" class="flex items-center gap-2 bg-[#161f2c] hover:bg-gray-800 border border-amber-500/50 rounded-full py-1 px-3 transition shadow-sm group">
                        <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-white text-pink-500 font-extrabold flex items-center justify-center text-[10px] sm:text-[12px] shrink-0 overflow-hidden ring-1 ring-amber-400">
                            @if(Auth::user()->profile_photo_url ?? false)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="" class="w-full h-full object-cover">
                            @else
                                <span class="text-pink-500 font-black leading-none">∞</span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-200 group-hover:text-amber-400 transition max-w-[120px] sm:max-w-[130px] truncate">
                            {{ Str::limit(Auth::user()->name ?? 'マイページ', 15, '') }}
                        </span>
                    </a>

                    <!-- ② コミュニティ -->
                    <a href="{{ route('community.index') }}" class="text-gray-300 hover:text-amber-400 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-users text-amber-500"></i>
                        <span>コミュニティ</span>
                    </a>

                    <!-- ③ ログアウト（赤色） -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-400 transition flex items-center gap-1.5 px-1 py-1 font-bold">
                            <i class="fa-solid fa-right-from-bracket text-red-500"></i>
                            <span>ログアウト</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-amber-500 text-black font-bold rounded-xl hover:bg-amber-400 transition text-xs">
                        ログイン
                    </a>
                @endauth
            </div>

        </div>
    </header>

    <!-- 📱 メインコンテンツエリア -->
    <main class="flex-grow w-full">
        {{ $slot }}
    </main>

    <!-- 🔻 フッター ＆ POPULAR MOVIES カルーセル（100%表示時にチラ見えするように上部マージンを詰める） -->
    <footer class="bg-[#0b0e14] border-t border-gray-900 mt-4 sm:mt-6 text-gray-400 text-xs w-full">
        
        @if(!empty($popularMovies))
            <div class="border-b border-gray-900 py-2.5 bg-black w-full overflow-hidden">
                <div class="w-full text-center mb-1.5 px-4">
                    <span class="text-[10px] sm:text-[11px] font-extrabold text-gray-400 tracking-wider uppercase">
                        POPULAR MOVIES <span class="text-gray-500 font-normal">（タップで詳細へ）</span>
                    </span>
                </div>
                
                <!-- 横スクロール移動エリア -->
                <div class="w-full overflow-x-auto no-scrollbar py-1" id="carouselContainer">
                    <div class="flex gap-2.5 px-4 w-max" id="carouselContent">
                        @foreach(array_merge($popularMovies, $popularMovies) as $movie)
                            <a href="{{ route('movies.show', $movie['id'] ?? 0) }}" class="shrink-0 w-20 sm:w-24 group">
                                <div class="aspect-[2/3] rounded-lg overflow-hidden bg-gray-900 border border-gray-800 group-hover:border-amber-500 transition relative shadow-md">
                                    @if(!empty($movie['poster_path']))
                                        <img src="https://image.tmdb.org/t/p/w200{{ $movie['poster_path'] }}" alt="{{ $movie['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-600 text-[10px]">No Image</div>
                                    @endif
                                    <div class="absolute top-1 right-1 bg-black/80 text-amber-400 text-[8px] sm:text-[9px] font-bold px-1 rounded backdrop-blur-sm">
                                        ★ {{ number_format((float)($movie['vote_average'] ?? 0), 1) }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- 最下部：シンプルなコピーライト -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 text-center text-xs text-gray-500">
            <p>&copy; MovieMood</p>
        </div>
    </footer>

    <!-- 🤖 横スクロール動作制御（JavaScript） -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('carouselContainer');
            if (!container) return;

            let isHovered = false;
            container.addEventListener('mouseenter', () => isHovered = true);
            container.addEventListener('mouseleave', () => isHovered = false);

            function autoScroll() {
                if (!isHovered) {
                    container.scrollLeft += 1;
                    if (container.scrollLeft >= (container.scrollWidth - container.clientWidth) / 2) {
                        container.scrollLeft = 0;
                    }
                }
            }

            setInterval(autoScroll, 20);
        });
    </script>

</body>
</html>