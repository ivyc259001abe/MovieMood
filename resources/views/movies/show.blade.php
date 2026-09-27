<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - {{ $movie['title'] ?? '映画詳細' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- FontAwesome Font Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] {
            display: none !important;
        }

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

        /* カスタムスクロールバーのスタイル */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(245, 158, 11, 0.4);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(245, 158, 11, 0.7);
        }
    </style>
</head>

<body
    class="bg-black text-white min-h-screen flex flex-col justify-between items-center selection:bg-amber-500 selection:text-black">

    <!-- 🎬 ヘッダーナビゲーション（他の画面と統一） -->
    <header class="w-full sticky top-0 z-50 bg-black/90 backdrop-blur-md border-b border-gray-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- 左側：ロゴ -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <span class="text-2xl font-black tracking-wider text-amber-500 group-hover:text-amber-400 transition">
                    MovieMood
                </span>
            </a>

            <!-- 右側：ナビゲーションメニュー -->
            <nav class="flex items-center gap-3 sm:gap-6 text-sm font-medium">
                @auth
                    <!-- マイページ -->
                    <a href="{{ route('mypage') }}"
                        class="flex items-center gap-2 bg-amber-500/15 hover:bg-amber-500/25 border-2 border-amber-500 hover:border-amber-400 rounded-full px-3 py-1 shadow-lg shadow-amber-500/10 transition group shrink-0"
                        title="{{ Auth::user()->name }}">
                        <div class="relative rounded-full bg-amber-500 text-black font-black flex items-center justify-center text-xs shadow shrink-0 overflow-hidden"
                            style="width: 28px; height: 28px;">
                            @if(!empty(Auth::user()->profile_photo_path))
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
                                    alt="{{ Auth::user()->name }}" class="rounded-full object-cover"
                                    style="width: 28px; height: 28px;">
                            @elseif(!empty(Auth::user()->avatar))
                                <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                                    class="rounded-full object-cover" style="width: 28px; height: 28px;">
                            @else
                                <span>{{ mb_substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                            @endif
                        </div>
                        <span
                            class="text-amber-400 font-extrabold text-xs sm:text-sm max-w-[90px] sm:max-w-[110px] truncate group-hover:text-amber-300">
                            {{ Auth::user()->name }}
                        </span>
                    </a>

                    <!-- コミュニティ -->
                    <a href="{{ route('community.index') }}"
                        class="text-gray-300 hover:text-amber-500 transition flex items-center gap-1.5 font-bold text-sm shrink-0">
                        <i class="fa-solid fa-users text-amber-500"></i>
                        <span class="hidden sm:inline">コミュニティ</span>
                    </a>

                    <!-- ログアウト -->
                    <form method="POST" action="{{ route('logout') }}" class="inline shrink-0">
                        @csrf
                        <button type="submit"
                            class="text-gray-400 hover:text-red-400 transition flex items-center gap-1.5 ml-1 text-xs sm:text-sm">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span class="hidden sm:inline">ログアウト</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-amber-500 hover:bg-amber-400 text-black font-bold px-4 py-2 rounded-full transition text-xs sm:text-sm">
                        ログイン
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- 🎬 メインコンテンツカード（幅広レスポンシブデザイン） -->
    <main class="w-full max-w-5xl px-4 py-6 sm:py-8 my-auto flex-1 flex flex-col justify-center">

        <!-- フラッシュメッセージ -->
        @if (session('success'))
            <div
                class="mb-4 bg-amber-500/20 border border-amber-500 text-amber-400 text-sm p-3 rounded-xl text-center font-bold shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- メインフレーム -->
        <div class="w-full bg-[#121824] border border-gray-800 rounded-2xl shadow-2xl overflow-hidden backdrop-blur-md">

            <!-- サブヘッダー (戻る & みたい！登録) -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-800 bg-black/40"
                x-data="{ watchlisted: false }">
                <!-- 👈 前の画面（またはホーム）に戻るボタン -->
                <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('home') }}"
                    class="text-xs sm:text-sm text-gray-400 hover:text-amber-400 font-bold flex items-center space-x-1.5 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>戻る</span>
                </a>

                <!-- みたい！切り替えボタン -->
                <button @click="watchlisted = !watchlisted"
                    :class="watchlisted ? 'bg-amber-500 text-black border-amber-500 shadow-amber-500/20' : 'bg-amber-500/10 text-amber-400 border-amber-500/40 hover:bg-amber-500 hover:text-black'"
                    class="text-xs sm:text-sm border px-4 py-1.5 rounded-full font-extrabold transition-all flex items-center space-x-1.5 shadow-md">
                    <span x-text="watchlisted ? '✓ 見たいリスト追加済' : '+ みたい！'"></span>
                </button>
            </div>

            <!-- 映画詳細（PC: 2カラム / スマホ: 1カラム） -->
            <div
                class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-12 gap-6 sm:gap-8 max-h-[75vh] overflow-y-auto custom-scrollbar">

                <!-- 左側：ポスター表示エリア (md:4) -->
                <div class="md:col-span-4 flex flex-col items-center">
                    <div
                        class="w-full max-w-[240px] md:max-w-none bg-black/60 rounded-xl overflow-hidden shadow-2xl border border-gray-800/80 aspect-[2/3] flex items-center justify-center relative group">
                        @if(!empty($movie['poster_path']))
                            <img src="https://image.tmdb.org/t/p/w400{{ $movie['poster_path'] }}"
                                alt="{{ $movie['title'] }}"
                                class="w-full h-full object-cover rounded-xl transition duration-300 group-hover:scale-105">
                        @else
                            <div class="text-center p-4">
                                <i class="fa-solid fa-film text-3xl text-gray-600 mb-2"></i>
                                <p class="text-xs text-gray-500">ポスター画像はありません</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 右側：作品情報＆レビュー (md:8) -->
                <div class="md:col-span-8 flex flex-col justify-between space-y-6">

                    <div class="space-y-4">
                        <!-- タイトル -->
                        <h2 class="text-2xl sm:text-3xl font-black text-white leading-tight tracking-wide">
                            {{ $movie['title'] ?? 'タイトル不明' }}
                        </h2>

                        <!-- メタ情報 -->
                        <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm text-gray-300">
                            <span
                                class="bg-amber-500/10 border border-amber-500/30 text-amber-400 font-black px-2.5 py-1 rounded-md flex items-center gap-1">
                                ★ {{ number_format($movie['vote_average'] ?? 0, 1) }}
                            </span>
                            <span class="text-gray-400"><i
                                    class="fa-regular fa-calendar mr-1"></i>{{ $movie['release_date'] ?? '不明' }}</span>
                            <span class="text-gray-400"><i
                                    class="fa-regular fa-clock mr-1"></i>{{ $movie['runtime'] ?? 0 }}分</span>
                        </div>

                        <!-- 監督 -->
                        <p class="text-xs sm:text-sm text-gray-400">
                            <span class="text-gray-500">監督:</span> {{ $movie['director'] ?? '不明' }}
                        </p>

                        <!-- あらすじ -->
                        <div class="bg-[#1a2332] p-4 rounded-xl border border-gray-800 space-y-2 shadow-inner">
                            <p class="text-xs font-bold text-amber-400 flex items-center gap-1.5">
                                <i class="fa-solid fa-align-left"></i> あらすじ
                            </p>
                            <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                                {{ !empty($movie['overview']) ? $movie['overview'] : 'あらすじ情報が登録されていません。' }}
                            </p>
                        </div>
                    </div>

                    <!-- みんなのレビュー (新着3件) -->
                    <div class="space-y-3 pt-4 border-t border-gray-800">
                        <div class="flex items-center justify-between">
                            <p class="text-xs sm:text-sm font-bold text-white flex items-center gap-2">
                                <i class="fa-solid fa-comments text-amber-500"></i>
                                みんなのレビュー <span class="text-gray-400 text-xs font-normal">(新着3件)</span>
                            </p>
                            <a href="{{ url('/movies/' . ($movie['id'] ?? 0) . '/reviews') }}"
                                class="text-xs text-amber-400 hover:text-amber-300 hover:underline transition">
                                すべてのレビューを見る →
                            </a>
                        </div>

                        <div class="space-y-2.5">
                            @forelse($reviews ?? [] as $rev)
                                <div class="bg-[#1a2332] border border-gray-800/80 rounded-xl p-3 space-y-2 text-xs">
                                    <!-- ユーザー情報 & 気分タグ -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div
                                                class="w-5 h-5 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center text-[10px] font-bold">
                                                👤
                                            </div>
                                            <span class="font-bold text-gray-200 text-xs">
                                                {{ $rev->user->nickname ?? $rev->user->name ?? $rev['user_name'] ?? '匿名' }}
                                            </span>
                                            <span class="text-amber-400 text-xs font-bold">
                                                ★ {{ $rev['rating'] ?? $rev->rating }}
                                            </span>
                                        </div>
                                        @if(!empty($rev['moods']) || !empty($rev['mood']))
                                            <span
                                                class="text-[10px] text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 rounded-full font-bold">
                                                {{ $rev['moods'] ?? $rev['mood'] }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- コメント内容 -->
                                    <p class="text-gray-300 text-xs pl-7 leading-relaxed">
                                        {{ $rev['comment'] ?? $rev->comment }}
                                    </p>

                                    <!-- いいねボタンエリア -->
                                    <div
                                        class="flex items-center justify-between pt-1.5 border-t border-gray-800/60 text-[10px] pl-7">
                                        <span class="text-gray-400">
                                            {{ isset($rev->created_at) ? $rev->created_at->format('Y/m/d') : '' }}
                                        </span>

                                        @auth
                                            <form action="{{ route('reviews.like', $rev->id ?? $rev['id']) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="flex items-center space-x-1 px-2.5 py-0.5 rounded-full border transition {{ (isset($rev->likes) && $rev->likes->contains('user_id', Auth::id())) ? 'bg-red-500/20 text-red-400 border-red-500/40' : 'bg-gray-800 text-gray-400 border-gray-700 hover:text-red-400' }}">
                                                    <span>❤️</span>
                                                    <span
                                                        class="font-bold">{{ isset($rev->likes) ? $rev->likes->count() : ($rev['likes_count'] ?? 0) }}</span>
                                                </button>
                                            </form>
                                        @else
                                            <div class="flex items-center space-x-1 text-gray-400">
                                                <span>❤️</span>
                                                <span
                                                    class="font-bold">{{ isset($rev->likes) ? $rev->likes->count() : ($rev['likes_count'] ?? 0) }}</span>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            @empty
                                <p
                                    class="text-center text-xs text-gray-500 py-4 bg-[#1a2332]/50 rounded-xl border border-gray-800/50">
                                    まだレビューがありません。最初のレビューを書いてみよう！
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <!-- この映画のレビューを書くボタン -->
                    <div class="pt-2">
                        @auth
                            <a href="{{ route('reviews.create', $movie['id'] ?? 0) }}"
                                class="block text-center w-full py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-black font-black text-xs sm:text-sm rounded-xl shadow-lg transition transform hover:-translate-y-0.5">
                                ✏️ この映画のレビューを書く
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="block text-center w-full py-2.5 bg-gray-800 hover:bg-gray-700 text-amber-400 font-bold text-xs sm:text-sm rounded-xl shadow transition border border-amber-500/30">
                                🔒 ログインしてレビューを書く
                            </a>
                        @endauth
                    </div>

                </div>
            </div>

        </div>

    </main>

    <!-- 🎬 流れる共通カルーセル（下部） -->
    <div class="w-full mt-4">
        @include('components.carousel', ['popularMovies' => $popularMovies ?? []])
    </div>

</body>

</html>