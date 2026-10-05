<x-app-layout :popularMovies="$popularMovies ?? []">
    <style>
        /* 検索候補用のスタイリッシュな細いスクロールバー */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #0b0e15;
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #f59e0b;
        }

        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #374151 #0b0e15;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

            <!-- 左側 (7カラム) -->
            <div class="lg:col-span-7 flex flex-col gap-4">

                <!-- 🌟 WELCOME -->
                <div
                    class="bg-[#121927] border border-gray-800/80 rounded-2xl p-4 sm:p-5 shadow-xl flex items-center gap-4">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 rounded-full overflow-hidden bg-gray-900 border-2 border-amber-500 flex items-center justify-center shrink-0">
                        @if(Auth::check() && Auth::user()->avatar)
                            <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-user text-gray-400 text-lg"></i>
                        @endif
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 font-bold tracking-wide flex items-center gap-1">
                            Welcome!
                        </span>
                        <h1 class="text-base sm:text-xl font-black mt-0.5">
                            <span class="text-amber-400">{{ Str::limit(Auth::user()->name ?? 'ゲスト', 15, '') }}</span>
                            <span class="text-white"> さん、こんにちは！</span>
                        </h1>
                    </div>
                </div>

                <!-- 🔍 検索 -->
                <div class="bg-[#121927] border border-gray-800/80 rounded-2xl p-4 shadow-xl space-y-3 relative">
                    <p class="text-xs font-bold text-amber-500 flex items-center gap-1.5">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>キーワード検索</span>
                    </p>

                    <form action="{{ route('movies.search') }}" method="GET" class="flex gap-2 w-full relative">
                        <div class="relative flex-grow">
                            <input type="text" id="searchInput" name="query" value="{{ request('query') }}"
                                placeholder="キーワード検索（映画タイトル、キャストなど）" autocomplete="off" required
                                class="w-full bg-[#05080e] border border-gray-800 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition">

                            <div id="autocompleteResults"
                                class="hidden absolute top-full left-0 right-0 bg-[#0b0e15] border border-gray-800 rounded-xl mt-1 max-h-60 overflow-y-auto custom-scrollbar z-50 shadow-2xl">
                            </div>
                        </div>
                        <button type="submit"
                            class="bg-amber-500 hover:bg-amber-400 text-black font-extrabold px-6 py-3 rounded-xl text-sm transition shrink-0 cursor-pointer">
                            検索
                        </button>
                    </form>
                </div>

                <!-- 🎭 気分タグ -->
                <div
                    class="bg-[#121927] border border-gray-800/80 rounded-2xl p-4 shadow-xl space-y-3 flex-grow flex flex-col justify-center">
                    <p class="text-xs font-bold text-amber-500 text-center">
                        ▼ 今のあなたの「気分」は？
                    </p>

                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('movies.search', ['mood' => '号泣']) }}"
                            class="flex items-center justify-center gap-2 bg-[#0b0e15] hover:bg-gray-800 border border-gray-800 hover:border-amber-500/60 rounded-xl py-3 px-3 transition shadow group">
                            <span class="text-base">😭</span>
                            <span class="text-xs font-bold text-gray-200 group-hover:text-amber-400">#号泣</span>
                        </a>

                        <a href="{{ route('movies.search', ['mood' => 'スカッと']) }}"
                            class="flex items-center justify-center gap-2 bg-[#0b0e15] hover:bg-gray-800 border border-gray-800 hover:border-amber-500/60 rounded-xl py-3 px-3 transition shadow group">
                            <span class="text-base">😆</span>
                            <span class="text-xs font-bold text-gray-200 group-hover:text-amber-400">#スカッと</span>
                        </a>

                        <a href="{{ route('movies.search', ['mood' => 'ハラハラ']) }}"
                            class="flex items-center justify-center gap-2 bg-[#0b0e15] hover:bg-gray-800 border border-gray-800 hover:border-amber-500/60 rounded-xl py-3 px-3 transition shadow group">
                            <span class="text-base">😱</span>
                            <span class="text-xs font-bold text-gray-200 group-hover:text-amber-400">#ハラハラ</span>
                        </a>

                        <a href="{{ route('movies.search', ['mood' => 'キュン']) }}"
                            class="flex items-center justify-center gap-2 bg-[#0b0e15] hover:bg-gray-800 border border-gray-800 hover:border-amber-500/60 rounded-xl py-3 px-3 transition shadow group">
                            <span class="text-base">💖</span>
                            <span class="text-xs font-bold text-gray-200 group-hover:text-amber-400">#キュン</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- 右側 (5カラム) : 🌙 TODAY'S PICKUP -->
            <div
                class="lg:col-span-5 bg-[#121927] border border-gray-800/80 rounded-2xl p-5 shadow-xl flex flex-col items-center justify-center text-center space-y-4">

                <!-- 上部ヘッダー部 -->
                <div class="space-y-2 flex flex-col items-center">
                    <span
                        class="text-[10px] text-amber-500 font-extrabold tracking-widest uppercase bg-amber-500/10 px-3 py-1 rounded-full border border-amber-500/20 inline-block">
                        🌙 TODAY'S PICKUP
                    </span>

                    @if(!empty($selectedTheme))
                        <!-- 感情タグ（クリックで検索結果へ） -->
                        <a href="{{ route('movies.search', ['mood' => $selectedTheme['mood']]) }}"
                            class="flex items-center gap-1.5 px-3 py-1 rounded-lg border text-xs font-bold pt-1 transition cursor-pointer {{ $selectedTheme['bg'] }}">
                            <span>{{ $selectedTheme['emoji'] }}</span>
                            <span>{{ $selectedTheme['tag'] }}</span>
                        </a>
                    @endif

                    <!-- キャッチコピー -->
                    @if(!empty($selectedMessage))
                        <h2 class="text-xs sm:text-sm font-extrabold text-amber-300">
                            「{{ $selectedMessage }}」
                        </h2>
                    @endif
                </div>

                @if(!empty($pickup))
                    <!-- 映画カード -->
                    <a href="{{ route('movies.show', $pickup['id'] ?? 0) }}"
                        class="block group cursor-pointer w-full max-w-[180px] sm:max-w-[200px] mx-auto space-y-2.5">
                        <div
                            class="aspect-[2/3] rounded-xl overflow-hidden bg-black border border-gray-800 shadow-xl relative group-hover:border-amber-500 transition duration-300">
                            @if(!empty($pickup['poster_path']))
                                <img src="https://image.tmdb.org/t/p/w500{{ $pickup['poster_path'] }}"
                                    alt="{{ $pickup['title'] ?? '' }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @endif
                        </div>

                        <!-- タイトル -->
                        <h3
                            class="text-sm sm:text-base font-extrabold text-white leading-tight break-words mt-2 group-hover:text-amber-400 transition">
                            {{ $pickup['title'] ?? '' }}
                        </h3>

                        <p class="text-xs text-amber-400 font-extrabold flex items-center justify-center gap-1">
                            <i class="fa-solid fa-star text-[11px]"></i>
                            <span>{{ number_format((float) ($pickup['vote_average'] ?? 0), 1) }}</span>
                        </p>
                    </a>
                @endif

            </div>
        </div>

    </div>

    <!-- 💡 JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const resultsBox = document.getElementById('autocompleteResults');
            let timeoutId = null;

            if (!searchInput || !resultsBox) return;

            searchInput.addEventListener('input', function () {
                clearTimeout(timeoutId);
                const query = this.value.trim();

                if (query.length < 1) {
                    resultsBox.classList.add('hidden');
                    resultsBox.innerHTML = '';
                    return;
                }

                timeoutId = setTimeout(() => {
                    fetch(`/api/movies/search?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data || data.length === 0) {
                                resultsBox.classList.add('hidden');
                                return;
                            }

                            let html = '';
                            data.forEach(item => {
                                const poster = item.poster_path
                                    ? `https://image.tmdb.org/t/p/w92${item.poster_path}`
                                    : 'https://via.placeholder.com/40x60?text=No';

                                const year = item.release_year
                                    || (item.release_date ? item.release_date.substring(0, 4) : '');

                                html += `
                                    <a href="/movies/${item.id}" class="flex items-center gap-3 p-3 border-b border-gray-800/60 hover:bg-gray-800/80 transition text-left text-white text-xs">
                                        <img src="${poster}" class="w-8 h-12 rounded object-cover shrink-0">
                                        <div class="overflow-hidden">
                                            <div class="font-bold truncate">${item.title}</div>
                                            <div class="text-[10px] text-gray-400">${year}</div>
                                        </div>
                                    </a>
                                `;
                            });

                            resultsBox.innerHTML = html;
                            resultsBox.classList.remove('hidden');
                        })
                        .catch(() => {
                            resultsBox.classList.add('hidden');
                        });
                }, 300);
            });

            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
                    resultsBox.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>