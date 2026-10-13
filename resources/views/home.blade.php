<x-app-layout :popularMovies="$popularMovies ?? []">

    <style>
        /* =========================================================
           スクロールバー
        ========================================================= */
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

        /* =========================================================
           ホーム画面共通
        ========================================================= */
        .home-content {
            width: 100%;
            min-width: 0;
        }

        .home-main-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            align-items: stretch;
            gap: 1.25rem;
            width: 100%;
            min-width: 0;
        }

        .home-main-column {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        .mood-card {
            position: relative;
        }

        .mood-button {
            min-height: 80px;
        }

        .pickup-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.3;
        }

        /* =========================================================
           天気バッジ（Welcomeカード内に配置）
        ========================================================= */
        .home-weather-badge-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(11, 15, 23, 0.7);
            border: 1px solid rgba(245, 158, 11, 0.35);
            border-radius: 0.75rem;
            padding: 0.4rem 0.8rem;
            transition: all 200ms ease;
            cursor: pointer;
            text-decoration: none;
        }

        .home-weather-badge-link:hover {
            border-color: #f59e0b;
            background: rgba(245, 158, 11, 0.15);
            transform: translateY(-1px);
        }

        .weather-icon-small {
            width: 22px;
            height: 22px;
            object-fit: contain;
        }

        /* JSダミー用非表示 */
        .home-weather-badge #weather-search-form,
        .home-weather-badge #weather-location-button,
        .home-weather-badge #weather-mood-recommendation,
        .home-weather-badge .weather-detail-stats {
            display: none !important;
        }

        /* =========================================================
           TODAY'S PICKUP：横幅をスリム化＆ポスタージャストフィット
        ========================================================= */
        .home-pickup-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            min-width: 0;
            height: 100%;
            text-align: center;
            box-sizing: border-box;
        }

        .home-pickup-poster-link {
            display: block;
            width: 100%;
            max-width: 210px;
            margin: 0 auto;
        }

        .home-pickup-poster {
            width: 100%;
            aspect-ratio: 2 / 3;
            max-height: 260px;
            overflow: hidden;
            background: #05080e;
            border: 1px solid #374151;
            border-radius: 1rem;
            box-shadow: 0 12px 28px rgb(0 0 0 / 50%);
        }

        .home-pickup-poster img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 300ms ease;
        }

        .home-pickup-poster-link:hover .home-pickup-poster img {
            transform: scale(1.04);
        }

        /* =========================================================
           PC：非均等グリッド（左:約58% / 右:約42%）で左右の余白を黄金比に
        ========================================================= */
        @media (min-width: 1024px) {
            .home-container {
                padding-top: 24px;
                padding-bottom: 24px;
            }

            .home-main-grid {
                /* 左側を少し広く(1.4fr)、右側をスリムに(1fr) */
                grid-template-columns: 1.4fr 1fr;
                align-items: stretch;
            }

            .home-main-column {
                height: 100%;
                min-height: 0;
            }

            .home-pickup-poster-link {
                max-width: 230px;
            }
        }

        /* =========================================================
           スマホ・タブレット調整
        ========================================================= */
        @media (max-width: 640px) {
            .mood-button {
                min-height: 75px;
            }

            .home-pickup-poster-link {
                max-width: 180px;
            }

            .home-pickup-card {
                padding: 1.25rem;
            }
        }
    </style>

    <div class="home-content max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 home-container">

        <div class="home-main-grid">

            <!-- =====================================================
                 左側：プロフィール・映画検索・気分タグ（幅広め）
            ====================================================== -->
            <div class="home-main-column">

                <!-- WELCOME ＆ 右側天気バッジ -->
                <div class="bg-[#121927] border border-gray-800/80 rounded-2xl p-4 sm:p-5 shadow-xl flex items-center justify-between gap-3">

                    @php
                        $user = Auth::user();
                        $nickname = trim((string) ($user?->nickname ?? ''));
                        if ($nickname === '') {
                            $nickname = trim((string) ($user?->name ?? ''));
                        }
                        if ($nickname === '') {$nickname = 'ユーザー';
                        }

                        $avatar = trim((string) ($user?->avatar
                            ?? $user?->icon
                            ?? $user?->icon_path
                            ?? ''
                        ));

                        $initial = mb_substr($nickname, 0, 1);$avatarUrl = null;

                        if ($avatar !== '') {
                            if (\Illuminate\Support\Str::startsWith($avatar, ['https://', 'http://', 'data:image/'])) {
                                $avatarUrl =$avatar;
                            } else {
                                $avatarPath = ltrim($avatar, '/');
                                if (\Illuminate\Support\Str::startsWith($avatarPath, ['uploads/', 'storage/'])) {
                                    $avatarUrl = asset($avatarPath);
                                } elseif (\Illuminate\Support\Str::startsWith($avatarPath, 'public/')) {
                                    $avatarPath = substr($avatarPath, 7);
                                    $avatarUrl = asset('storage/' .$avatarPath);
                                } else {
                                    $avatarUrl = asset('storage/' .$avatarPath);
                                }
                            }
                        }
                    @endphp

                    <!-- ユーザー情報 -->
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-11 h-11 sm:w-13 sm:h-13 rounded-full overflow-hidden bg-gray-900 border-2 border-amber-500 flex items-center justify-center shrink-0">
                            @if ($avatarUrl)
                                <img
                                    id="home-avatar-image"
                                    src="{{ $avatarUrl }}"
                                    alt="プロフィール画像"
                                    class="w-full h-full object-cover"
                                    onerror="this.style.display='none'; document.getElementById('home-avatar-initial').classList.remove('hidden'); document.getElementById('home-avatar-initial').classList.add('flex');"
                                >
                                <span
                                    id="home-avatar-initial"
                                    class="hidden w-full h-full items-center justify-center text-amber-400 text-lg sm:text-xl font-black"
                                >
                                    {{ $initial }}
                                </span>
                            @else
                                <span class="w-full h-full flex items-center justify-center text-amber-400 text-lg sm:text-xl font-black">
                                    {{ $initial }}
                                </span>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <span class="text-[11px] text-gray-400 font-bold tracking-wide">
                                Welcome!
                            </span>
                            <h1 class="text-base sm:text-xl font-black leading-tight break-words">
                                <span class="text-amber-400">
                                    {{ \Illuminate\Support\Str::limit($nickname, 15, '') }}
                                </span>
                                <span class="text-white">さん！</span>
                            </h1>
                        </div>
                    </div>

                    <!-- 天気バッジ -->
                    <div id="weather-card" class="home-weather-badge shrink-0 text-right">
                        <div class="text-[10px] text-amber-400 font-bold mb-1 flex items-center justify-end gap-1">
                            <i class="fa-solid fa-cloud-sun"></i>
                            <span>今日の空模様から探す</span>
                        </div>
                        <a href="{{ route('weather.page') }}" class="home-weather-badge-link" title="空模様に合わせた映画の提案を見る">
                            <span id="weather-status" class="text-xs text-gray-400">天気取得中…</span>

                            <div id="weather-result" class="hidden flex items-center gap-1.5 text-xs font-bold">
                                <img id="weather-icon" class="weather-icon-small" src="" alt="">
                                <span id="weather-city" class="text-gray-200"></span>
                                <span id="weather-temperature" class="text-amber-400 font-black"></span><span class="text-amber-400 font-black">°C</span>
                            </div>

                            <i class="fa-solid fa-chevron-right text-[10px] text-gray-400 ml-0.5"></i>
                        </a>

                        <!-- JSエラー回避要素 -->
                        <form id="weather-search-form" class="hidden"><input id="weather-city-input"><div id="weather-city-suggestions"></div><button id="weather-search-button"></button></form>
                        <button type="button" id="weather-location-button" class="hidden"></button>
                        <div class="weather-detail-stats hidden"><span id="weather-description"></span><span id="weather-feels-like"></span><span id="weather-humidity"></span><span id="weather-wind"></span></div>
                        <div id="weather-mood-recommendation" class="hidden"></div>
                    </div>

                </div>

                <!-- キーワード検索 -->
                <div class="bg-[#121927] border border-gray-800/80 rounded-2xl p-4 shadow-xl space-y-2.5 relative">

                    <p class="text-xs font-bold text-amber-500 flex items-center gap-1.5">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>キーワード検索</span>
                    </p>

                    <form
                        action="{{ route('movies.search') }}"
                        method="GET"
                        class="flex gap-2 w-full relative"
                    >
                        <div class="relative flex-grow min-w-0">
                            <input
                                type="text"
                                id="searchInput"
                                name="query"
                                value="{{ request('query') }}"
                                placeholder="映画タイトル、キャストなど"
                                autocomplete="off"
                                required
                                class="w-full bg-[#05080e] border border-gray-800 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition"
                            >
                            <div
                                id="autocompleteResults"
                                class="hidden absolute top-full left-0 right-0 bg-[#0b0e15] border border-gray-800 rounded-xl mt-1 max-h-60 overflow-y-auto custom-scrollbar z-50 shadow-2xl"
                            ></div>
                        </div>

                        <button
                            type="submit"
                            class="bg-amber-500 hover:bg-amber-400 text-black font-extrabold px-5 sm:px-6 py-2.5 rounded-xl text-sm transition shrink-0 cursor-pointer"
                        >
                            検索
                        </button>
                    </form>

                </div>

                <!-- 気分タグ -->
                <div class="mood-card bg-[#121927] border border-gray-800/80 rounded-2xl p-4 sm:p-5 shadow-xl space-y-4">

                    <div class="text-center space-y-1">
                        <p class="text-base sm:text-lg font-black text-amber-400 tracking-wide">
                            🎭 今のあなたの「気分」は？
                        </p>
                        <p class="text-xs text-gray-400">
                            今の気分にぴったりの映画を探そう
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <a
                            href="{{ route('movies.search', ['mood' => '号泣']) }}"
                            class="mood-button flex flex-col items-center justify-center gap-1 bg-[#0b0e15] hover:bg-amber-500/10 border border-gray-800 hover:border-amber-500/70 rounded-xl py-3 px-2 transition duration-200 shadow group"
                        >
                            <span class="text-2xl group-hover:scale-110 transition duration-200">😭</span>
                            <span class="text-xs sm:text-sm font-black text-gray-200 group-hover:text-amber-400">#号泣</span>
                        </a>

                        <a
                            href="{{ route('movies.search', ['mood' => 'スカッと']) }}"
                            class="mood-button flex flex-col items-center justify-center gap-1 bg-[#0b0e15] hover:bg-amber-500/10 border border-gray-800 hover:border-amber-500/70 rounded-xl py-3 px-2 transition duration-200 shadow group"
                        >
                            <span class="text-2xl group-hover:scale-110 transition duration-200">😆</span>
                            <span class="text-xs sm:text-sm font-black text-gray-200 group-hover:text-amber-400">#スカッと</span>
                        </a>

                        <a
                            href="{{ route('movies.search', ['mood' => 'ハラハラ']) }}"
                            class="mood-button flex flex-col items-center justify-center gap-1 bg-[#0b0e15] hover:bg-amber-500/10 border border-gray-800 hover:border-amber-500/70 rounded-xl py-3 px-2 transition duration-200 shadow group"
                        >
                            <span class="text-2xl group-hover:scale-110 transition duration-200">😱</span>
                            <span class="text-xs sm:text-sm font-black text-gray-200 group-hover:text-amber-400">#ハラハラ</span>
                        </a>

                        <a
                            href="{{ route('movies.search', ['mood' => 'キュン']) }}"
                            class="mood-button flex flex-col items-center justify-center gap-1 bg-[#0b0e15] hover:bg-amber-500/10 border border-gray-800 hover:border-amber-500/70 rounded-xl py-3 px-2 transition duration-200 shadow group"
                        >
                            <span class="text-2xl group-hover:scale-110 transition duration-200">💖</span>
                            <span class="text-xs sm:text-sm font-black text-gray-200 group-hover:text-amber-400">#キュン</span>
                        </a>
                    </div>

                </div>

            </div>

            <!-- =====================================================
                 右側：TODAY'S PICKUP（洗練ヘッダー ＆ 黄金タグ）
            ====================================================== -->
            <div class="home-main-column">

                <section class="home-pickup-card bg-[#121927] border border-gray-800/80 rounded-2xl p-4 sm:p-5 shadow-xl">

                    <!-- ヘッダー（シンプル＆洗練されたタイトル表記） -->
                    <div class="w-full flex items-center justify-center mb-1">
                        <span class="text-[11px] text-amber-500 font-extrabold tracking-widest uppercase flex items-center gap-1.5">
                            <i class="fa-solid fa-moon text-xs"></i>
                            <span>TODAY'S PICKUP</span>
                        </span>
                    </div>

                    <!-- 気分タグ ＆ キャッチコピー -->
                    <div class="space-y-2 mb-2">
                        @if (!empty($selectedTheme))
                            <div>
                                <a
                                    href="{{ route('movies.search', ['mood' => ltrim($selectedTheme['mood'] ?? $selectedTheme['tag'] ?? '', '#')]) }}"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full border border-amber-500/50 bg-amber-500/15 text-amber-400 text-xs font-black hover:bg-amber-500/25 transition cursor-pointer shadow-sm"
                                >
                                    <span>{{ $selectedTheme['emoji'] ?? '😆' }}</span>
                                    <span>#{{ ltrim($selectedTheme['tag'] ?? '', '#') }}</span>
                                </a>
                            </div>
                        @endif

                        @if (!empty($selectedMessage))
                            <p class="text-xs sm:text-sm font-extrabold text-white leading-snug">
                                「{{ $selectedMessage }}」
                            </p>
                        @endif
                    </div>

                    <!-- ポスター画像 -->
                    @if (!empty($pickup))
                        <a
                            href="{{ route('movies.show', $pickup['id'] ?? 0) }}"
                            class="home-pickup-poster-link my-auto group"
                            aria-label="{{ $pickup['title'] ?? 'おすすめ映画' }}の詳細を見る"
                        >
                            <div class="home-pickup-poster">
                                @if (!empty($pickup['poster_path']))
                                    <img
                                        src="https://image.tmdb.org/t/p/w500{{ $pickup['poster_path'] }}"
                                        alt="{{ $pickup['title'] ?? '' }}"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500 text-xs text-center p-2">
                                        ポスターなし
                                    </div>
                                @endif
                            </div>
                        </a>

                        <!-- 映画タイトル ＆ 評価 -->
                        <div class="mt-2 space-y-0.5">
                            <a href="{{ route('movies.show', $pickup['id'] ?? 0) }}" class="group">
                                <h3
                                    class="pickup-title text-sm sm:text-base font-black text-white group-hover:text-amber-400 transition"
                                    title="{{ $pickup['title'] ?? '' }}"
                                >
                                    {{ $pickup['title'] ?? '' }}
                                </h3>
                            </a>

                            <div class="flex items-center justify-center gap-1 text-amber-400 font-black text-xs pt-0.5">
                                <i class="fa-solid fa-star text-[10px]"></i>
                                <span>{{ number_format((float) ($pickup['vote_average'] ?? 0), 1) }}</span>
                                <span class="text-gray-500 font-normal text-[10px]">/ 10</span>
                            </div>
                        </div>
                    @else
                        <p class="text-xs text-gray-400 my-auto">
                            おすすめ映画を取得できませんでした。
                        </p>
                    @endif

                </section>

            </div>

        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* 1. 映画検索のオートコンプリート */
            const searchInput = document.getElementById('searchInput');
            const resultsBox = document.getElementById('autocompleteResults');
            let searchTimeoutId = null;
            let searchController = null;

            if (searchInput && resultsBox) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(searchTimeoutId);
                    if (searchController) searchController.abort();

                    const query = this.value.trim();
                    if (query.length < 1) {
                        resultsBox.classList.add('hidden');
                        resultsBox.replaceChildren();
                        return;
                    }

                    searchTimeoutId = setTimeout(async function () {
                        searchController = new AbortController();
                        const controller = searchController;

                        try {
                            const response = await fetch(
                                `/api/movies/search?query=${encodeURIComponent(query)}`,
                                {
                                    headers: { 'Accept': 'application/json' },
                                    signal: controller.signal
                                }
                            );
                            if (!response.ok) throw new Error('映画検索に失敗しました。');
                            const data = await response.json();
                            if (searchController !== controller) return;

                            resultsBox.replaceChildren();
                            if (!Array.isArray(data) || data.length === 0) {
                                resultsBox.classList.add('hidden');
                                return;
                            }

                            const fragment = document.createDocumentFragment();
                            data.forEach(function (item) {
                                const link = document.createElement('a');
                                link.href = `/movies/${encodeURIComponent(item.id)}`;
                                link.className = 'flex items-center gap-3 p-3 border-b border-gray-800/60 hover:bg-gray-800/80 transition text-left text-white text-xs';

                                const img = document.createElement('img');
                                img.src = item.poster_path ? `https://image.tmdb.org/t/p/w92${item.poster_path}` : 'https://via.placeholder.com/40x60?text=No';
                                img.alt = '';
                                img.className = 'w-8 h-12 rounded object-cover shrink-0';

                                const details = document.createElement('div');
                                details.className = 'overflow-hidden min-w-0 flex-1';

                                const title = document.createElement('div');
                                title.className = 'font-bold truncate';
                                title.textContent = item.title || '';

                                const releaseYear = document.createElement('div');
                                const year = item.release_year || (item.release_date ? item.release_date.substring(0, 4) : '');
                                releaseYear.className = 'text-[10px] text-gray-400';
                                releaseYear.textContent = year;

                                details.appendChild(title);
                                details.appendChild(releaseYear);
                                link.appendChild(img);
                                link.appendChild(details);
                                fragment.appendChild(link);
                            });

                            resultsBox.appendChild(fragment);
                            resultsBox.classList.remove('hidden');
                        } catch (error) {
                            if (error.name !== 'AbortError') {
                                resultsBox.classList.add('hidden');
                                resultsBox.replaceChildren();
                            }
                        }
                    }, 300);
                });

                document.addEventListener('click', function (event) {
                    if (!searchInput.contains(event.target) && !resultsBox.contains(event.target)) {
                        resultsBox.classList.add('hidden');
                    }
                });
            }

            /* 2. 天気情報取得 */
            const weatherUrl = @json(route('weather.show'));

            const weatherStatus = document.getElementById('weather-status');
            const weatherResult = document.getElementById('weather-result');
            const weatherCity = document.getElementById('weather-city');
            const weatherIcon = document.getElementById('weather-icon');
            const weatherTemperature = document.getElementById('weather-temperature');

            let weatherRequestController = null;

            function renderWeather(data) {
                if (!weatherCity || !weatherIcon || !weatherTemperature || !weatherResult) return;
                weatherCity.textContent = data.city || '';
                weatherTemperature.textContent = Number(data.temperature).toFixed(1);

                if (data.icon) {
                    weatherIcon.src = `https://openweathermap.org/img/wn/${encodeURIComponent(data.icon)}@2x.png`;
                    weatherIcon.alt = data.description || '天気';
                }

                weatherResult.classList.remove('hidden');
                if (weatherStatus) weatherStatus.classList.add('hidden');
            }

            async function fetchWeather(params) {
                if (weatherRequestController) weatherRequestController.abort();
                const controller = new AbortController();
                weatherRequestController = controller;

                const url = new URL(weatherUrl, window.location.origin);
                Object.entries(params).forEach(([key, value]) => { url.searchParams.set(key, String(value)); });

                try {
                    const response = await fetch(url.toString(), {
                        headers: { 'Accept': 'application/json' },
                        signal: controller.signal
                    });
                    const data = await response.json();
                    if (!response.ok) throw new Error();
                    if (weatherRequestController !== controller) return;

                    renderWeather(data);
                } catch (error) {
                    if (weatherStatus) weatherStatus.textContent = '天気取得不可';
                }
            }

            function requestCurrentWeather() {
                if (!navigator.geolocation) {
                    if (weatherStatus) weatherStatus.textContent = '位置情報不可';
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    function (position) { fetchWeather({ lat: position.coords.latitude, lon: position.coords.longitude }); },
                    function (error) {
                        if (weatherStatus) weatherStatus.textContent = '天気取得不可';
                    },
                    { enableHighAccuracy: false, timeout: 15000, maximumAge: 300000 }
                );
            }

            requestCurrentWeather();
        });
    </script>

</x-app-layout>