<!DOCTYPE html>
<html lang="ja" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
        [x-cloak] {
            display: none !important;
        }

        @keyframes loop-scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-loop-scroll {
            display: flex;
            width: max-content;
            animation: loop-scroll 120s linear infinite;
        }

        .animate-loop-scroll:hover {
            animation-play-state: paused;
        }
    </style>
</head>

<body
    class="bg-black text-white min-h-screen flex flex-col font-sans antialiased w-full selection:bg-amber-500 selection:text-black overflow-x-hidden relative">

    <!-- 🌟 ヘッダーナビゲーション（レスポンシブ・三本線メニュー対応） -->
    <header x-data="{ mobileMenuOpen: false }"
        class="bg-[#0b0e14] border-b border-gray-800/80 sticky top-0 z-50 w-full py-2">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 flex items-center justify-between w-full">

            <!-- 左：MovieMoodロゴ ＆ キャッチコピー -->
            <div class="flex flex-col items-start justify-center min-w-0">
                <a href="{{ route('home') }}" title="HOME画面へ戻る"
                    class="group text-lg sm:text-2xl font-extrabold text-amber-500 hover:text-amber-400 transition-all duration-200 tracking-wide shrink-0 no-underline inline-flex items-center gap-1.5">
                    <span class="group-hover:scale-105 transition-transform duration-200">MovieMood</span>
                </a>
                <p
                    class="text-[9px] sm:text-xs text-gray-300 font-bold m-0 mt-0.5 pl-0.5 pointer-events-none truncate max-w-[200px] sm:max-w-none">
                    〜 あなたの「今の気分」が、次に観る映画を決める。 〜
                </p>
            </div>

            <!-- 右：PCナビゲーション（sm:flex で 640px以上 のみ表示） -->
            <div class="hidden sm:flex items-center gap-3 sm:gap-5 text-xs font-bold">
                @auth
                    <!-- 🔔 1. 通知アイコン（ドロップダウン） -->
                    <div x-data="{ open: false, unreadCount: {{ Auth::user()->unreadNotifications->count() }} }"
                        class="relative">
                        <button @click="
                                        open = !open;
                                        if (open && unreadCount > 0) {
                                            fetch('{{ route('notifications.readAll') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Content-Type': 'application/json'
                                                }
                                            }).then(() => unreadCount = 0);
                                        }
                                    "
                            class="relative text-gray-300 hover:text-amber-400 p-1.5 focus:outline-none transition cursor-pointer flex items-center"
                            title="お知らせ">
                            <i class="fa-solid fa-bell text-base text-amber-500"></i>
                            <span x-show="unreadCount > 0" x-cloak
                                class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-[#0b0e14]"></span>
                        </button>

                        <!-- 通知ドロップダウンメニュー -->
                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute right-0 mt-2 w-80 sm:w-96 bg-[#121824] border border-gray-800 rounded-2xl shadow-2xl overflow-hidden py-1 z-50 text-left">
                            <div
                                class="px-4 py-2.5 bg-[#1a2332] border-b border-gray-800 flex justify-between items-center">
                                <span class="font-bold text-gray-200 text-xs flex items-center gap-1.5">
                                    <i class="fa-solid fa-bell text-amber-500"></i> お知らせ
                                </span>
                                <span
                                    class="text-[10px] bg-amber-500/20 text-amber-400 border border-amber-500/30 px-2 py-0.5 rounded-full font-bold"
                                    x-text="unreadCount + '件の未読'"></span>
                            </div>

                            <div class="max-h-80 overflow-y-auto divide-y divide-gray-800/60 p-1.5 space-y-1">
                                @forelse(Auth::user()->notifications->take(10) as $notification)
                                    @php
                                        $data = $notification->data ?? [];
                                        $movieId = $data['movie_id'] ?? $data['tmdb_id'] ?? null;
                                        $reviewId = $data['review_id'] ?? null;
                                        $movieTitle = $data['movie_title'] ?? $data['title'] ?? null;
                                        $userName = $data['user_name'] ?? $data['sender_name'] ?? 'ユーザー';
                                        $rawMessage = $data['message'] ?? '新しいお知らせがあります';

                                        $targetUrl = '#';
                                        if (!empty($data['url']) && $data['url'] !== '#') {
                                            $targetUrl = $data['url'];
                                        } elseif ($movieId) {
                                            if (\Illuminate\Support\Facades\Route::has('movies.show')) {
                                                $targetUrl = route('movies.show', $movieId);
                                            } else {
                                                $targetUrl = url('/movies/' . $movieId);
                                            }
                                            if ($reviewId) {
                                                $targetUrl .= '#review-' . $reviewId;
                                            }
                                        }
                                        $isUnread = is_null($notification->read_at);
                                    @endphp

                                    <div
                                        class="group relative rounded-xl transition duration-150 overflow-hidden border border-transparent {{ $isUnread ? 'bg-[#1a2332]/90' : 'bg-transparent hover:bg-gray-800/60' }} p-3">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">
                                                {{ mb_substr($userName, 0, 1) }}
                                            </div>

                                            <div class="flex-1 min-w-0 text-xs text-gray-300 leading-relaxed space-y-1">
                                                <div>
                                                    <span class="font-bold text-white">{{ $userName }}</span> さんがあなたの

                                                    @if($movieId && $movieTitle)
                                                        <a href="{{ $targetUrl }}"
                                                            class="font-bold text-amber-400 hover:text-amber-300 hover:underline transition inline-block mx-0.5 relative z-10">
                                                            『{{ $movieTitle }}』
                                                        </a>
                                                    @elseif($movieId)
                                                        <a href="{{ $targetUrl }}"
                                                            class="font-bold text-amber-400 hover:text-amber-300 hover:underline transition inline-block mx-0.5 relative z-10">
                                                            映画ページへ
                                                        </a>
                                                    @else
                                                        <span class="text-gray-200">{{ $rawMessage }}</span>
                                                    @endif

                                                    のレビューにコメントしました。
                                                </div>

                                                <div class="flex items-center justify-between text-[10px] text-gray-500 pt-1">
                                                    <span>{{ $notification->created_at->diffForHumans() }}</span>
                                                    @if($isUnread)
                                                        <span
                                                            class="w-2 h-2 rounded-full bg-amber-500 inline-block shadow-[0_0_6px_rgba(245,158,11,0.8)]"></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-xs text-gray-500 py-6 m-0">新着のお知らせはありません</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- 👤 2. マイページ -->
                    <a href="{{ route('mypage') }}"
                        class="flex items-center gap-1.5 sm:gap-2 bg-[#161f2c] hover:bg-gray-800 border border-amber-500/50 rounded-full py-1 px-2.5 sm:px-3.5 transition shadow-sm group shrink-0 no-underline">
                        <div
                            class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-gray-900 flex items-center justify-center shrink-0 overflow-hidden ring-1 ring-amber-400">
                            @if(Auth::check() && Auth::user()->avatar)
                                <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-user text-gray-400 text-[9px] sm:text-[10px]"></i>
                            @endif
                        </div>
                        <span
                            class="text-[11px] sm:text-xs text-gray-200 group-hover:text-amber-400 transition max-w-[80px] sm:max-w-[130px] truncate">
                            {{ Auth::user()->nickname ?? Auth::user()->name ?? 'マイページ' }}
                        </span>
                    </a>

                    <!-- 👥 3. コミュニティ -->
                    <a href="{{ route('community.index') }}"
                        class="text-amber-500 hover:text-amber-400 transition flex items-center gap-1 px-1 py-1 shrink-0 no-underline"
                        title="コミュニティ">
                        <i class="fa-solid fa-users text-amber-500 text-sm sm:text-xs"></i>
                        <span>コミュニティ</span>
                    </a>

                    <!-- 🚪 4. ログアウト -->
                    <form method="POST" action="{{ route('logout') }}" class="inline shrink-0 m-0">
                        @csrf
                        <button type="submit"
                            class="text-red-500 hover:text-red-400 transition flex items-center gap-1 px-1 py-1 font-bold cursor-pointer bg-transparent border-0"
                            title="ログアウト">
                            <i class="fa-solid fa-right-from-bracket text-red-500 text-sm sm:text-xs"></i>
                            <span>ログアウト</span>
                        </button>
                    </form>
                @endauth
            </div>

            <!-- 右：スマホ用ハンバーガーボタン（sm:hidden で 640px未満 のみ表示） -->
            <div class="flex items-center gap-2 sm:hidden">
                @auth
                    <!-- スマホ用通知ベル -->
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen"
                        class="text-amber-500 p-1.5 focus:outline-none">
                        <i class="fa-solid fa-bell text-base"></i>
                    </button>
                @endauth

                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" aria-label="メニューを開く"
                    class="p-2 rounded-lg text-amber-500 hover:bg-gray-800/80 focus:outline-none transition">
                    <i class="fa-solid text-xl" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>

        </div>

        <!-- 📱 スマホ用展開ドロワーメニュー（三本線タップ時にスライドダウン） -->
        @auth
            <div x-show="mobileMenuOpen" x-cloak @click.away="mobileMenuOpen = false"
                class="sm:hidden bg-[#0e131f] border-t border-gray-800/80 px-4 pt-3 pb-4 space-y-3 mt-2">

                <!-- ユーザー情報表示 -->
                <div class="flex items-center gap-3 pb-2.5 border-b border-gray-800">
                    <div
                        class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center shrink-0 overflow-hidden ring-1 ring-amber-400">
                        @if(Auth::check() && Auth::user()->avatar)
                            <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-user text-gray-400 text-xs"></i>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-xs text-amber-400 truncate">
                            {{ Auth::user()->nickname ?? Auth::user()->name ?? 'ユーザー' }}</div>
                        <div class="font-medium text-[10px] text-gray-500 truncate">{{ Auth::user()->email ?? '' }}</div>
                    </div>
                </div>

                <!-- メニュー一覧 -->
                <div class="space-y-1">
                    <a href="{{ route('home') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-gray-200 hover:bg-gray-800 transition no-underline">
                        <i class="fa-solid fa-house text-amber-500 w-4 text-center"></i>
                        <span>ホーム</span>
                    </a>

                    <a href="{{ route('mypage') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-gray-200 hover:bg-gray-800 transition no-underline">
                        <i class="fa-solid fa-user-gear text-amber-500 w-4 text-center"></i>
                        <span>マイページ</span>
                    </a>

                    <a href="{{ route('community.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-gray-200 hover:bg-gray-800 transition no-underline">
                        <i class="fa-solid fa-users text-amber-500 w-4 text-center"></i>
                        <span>コミュニティ</span>
                    </a>
                </div>

                <!-- ログアウトボタン -->
                <div class="pt-2 border-t border-gray-800">
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-3 py-2 rounded-lg text-xs font-bold text-rose-400 hover:bg-rose-500/10 transition flex items-center gap-2.5 bg-transparent border-0 cursor-pointer">
                            <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                            <span>ログアウト</span>
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </header>

    <!-- 📱 メインコンテンツエリア -->
    <main class="flex-grow w-full max-w-full overflow-x-hidden">
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

    <footer
        class="bg-black border-t border-gray-900 mt-6 text-gray-400 text-xs w-full shrink-0 max-w-full overflow-hidden">
        <!-- 人気映画カルーセル -->
        @if(!empty($popularMovies))
            <div class="border-b border-gray-900 py-2 bg-black w-full overflow-hidden">
                <div class="w-full text-center mb-1.5 px-4">
                    <span class="text-[10px] font-extrabold text-gray-400 tracking-wider uppercase">
                        POPULAR MOVIES @auth <span class="text-gray-500 font-normal">（タップで詳細へ）</span> @endauth
                    </span>
                </div>

                <!-- 横スクロールアニメーションエリア -->
                <div class="w-full overflow-hidden relative">
                    <div class="animate-loop-scroll gap-2 will-change-transform">
                        @foreach($loopMovies as $movie)
                            <a href="{{ route('movies.show', $movie['id'] ?? 0) }}"
                                class="flex-none w-[110px] h-[150px] bg-gray-900 rounded-md overflow-hidden border border-gray-800 relative shadow-md group">
                                @if(!empty($movie['poster_path']))
                                    <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}"
                                        alt="{{ $movie['title'] ?? '' }}"
                                        class="w-[110px] h-[150px] object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-600 text-[10px]">No Image
                                    </div>
                                @endif
                                <div
                                    class="absolute top-1 right-1 bg-black/85 border border-amber-500/80 text-amber-400 text-[9px] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-sm">
                                    ★
                                    {{ (isset($movie['vote_average']) && $movie['vote_average'] > 0) ? number_format($movie['vote_average'], 1) : '-' }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- 最下部：コピーライト -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 text-center text-[10px] text-gray-500 font-medium">
            <p class="m-0">&copy; 2026 MovieMood. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>