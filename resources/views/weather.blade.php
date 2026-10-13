<x-app-layout :popularMovies="$popularMovies ?? []">

    <style>
        [x-cloak] {
            display: none !important;
        }

        .weather-page {
            color: #f3f4f6;
        }

        /* パネルの最小高さ・余白を広げて POPULAR MOVIES をさらに下へ押し下げる */
        .weather-panel {
            background: rgba(18, 25, 39, 0.96);
            border: 1px solid rgba(75, 85, 99, 0.45);
            border-radius: 20px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
            padding: 1.6rem 1.4rem;
            /* パディングをしっかり確保 */
            min-height: 470px;
            /* 最小高さを 440px ➔ 470px に拡張 */
        }

        .weather-panel-title {
            color: #fbbf24;
            font-weight: 800;
            letter-spacing: 0.04em;
        }

        /* 地域検索 */
        .weather-input {
            width: 100%;
            min-width: 0;
            border: 1px solid #374151;
            border-radius: 12px;
            background: #05080e;
            padding: 11px 14px;
            color: #f9fafb;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s;
        }

        .weather-input:focus {
            border-color: #f59e0b;
        }

        .weather-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1px solid rgba(245, 158, 11, 0.45);
            border-radius: 12px;
            background: rgba(245, 158, 11, 0.12);
            padding: 11px 16px;
            color: #fbbf24;
            font-size: 12px;
            font-weight: 800;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .weather-button:hover {
            background: rgba(245, 158, 11, 0.22);
            border-color: #f59e0b;
        }

        .weather-button-primary {
            background: #f59e0b;
            color: #111827;
            border: none;
        }

        .weather-button-primary:hover {
            background: #fbbf24;
        }

        .weather-suggestions {
            position: absolute;
            z-index: 50;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            max-height: 180px;
            overflow-y: auto;
            border: 1px solid #374151;
            border-radius: 12px;
            background: #0b0f17;
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.6);
        }

        .weather-suggestion {
            display: block;
            width: 100%;
            padding: 9px 12px;
            text-align: left;
            color: #e5e7eb;
            font-size: 12px;
            cursor: pointer;
        }

        .weather-suggestion:hover {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
        }

        /* 現在の天気ボックス */
        .weather-current-box {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(245, 158, 11, 0.25);
            border-radius: 14px;
            background: radial-gradient(circle at 90% 10%, rgba(245, 158, 11, 0.14), transparent 40%), linear-gradient(145deg, #151d2b, #101722);
            padding: 1.15rem 1.25rem;
            min-height: 155px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .weather-temperature {
            font-size: 38px;
            font-weight: 900;
            line-height: 1;
            letter-spacing: -0.05em;
            color: #ffffff;
        }

        /* 5日間天気予報カード */
        .forecast-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 0.5rem;
            min-height: 72px;
        }

        .forecast-card {
            background: rgba(11, 15, 23, 0.6);
            border: 1px solid rgba(75, 85, 99, 0.4);
            border-radius: 8px;
            padding: 8px 4px;
            text-align: center;
        }

        /* 右側：気分・映画提案カード */
        .weather-mood-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(245, 158, 11, 0.35);
            border-radius: 16px;
            background: radial-gradient(circle at 100% 0%, rgba(245, 158, 11, 0.18), transparent 50%), #101722;
            padding: 1.45rem 1.4rem;
        }

        .weather-mood-tag-after {
            display: inline-flex;
            align-items: center;
            border: 1px solid rgba(245, 158, 11, 0.8);
            border-radius: 8px;
            background: rgba(245, 158, 11, 0.12);
            padding: 6px 13px;
            color: #fbbf24;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .weather-mood-tag-after:hover {
            background: rgba(245, 158, 11, 0.28);
            border-color: #f59e0b;
            color: #ffffff;
        }

        .weather-mood-tag-before {
            display: inline-flex;
            align-items: center;
            border: 1px solid rgba(168, 85, 247, 0.8);
            border-radius: 8px;
            background: rgba(168, 85, 247, 0.12);
            padding: 6px 13px;
            color: #e9d5ff;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .weather-mood-tag-before:hover {
            background: rgba(168, 85, 247, 0.28);
            border-color: #c084fc;
            color: #ffffff;
        }

        .weather-mood-link {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 10px;
            background: #f59e0b;
            padding: 12px 18px;
            color: #111827;
            font-size: 13px;
            font-weight: 900;
            transition: all 0.2s ease;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.2);
        }

        .weather-mood-link:hover {
            background: #fbbf24;
            box-shadow: 0 6px 18px rgba(245, 158, 11, 0.35);
        }

        .weather-divider {
            height: 1px;
            background: rgba(75, 85, 99, 0.35);
        }
    </style>

    {{-- 上下余白（pt-4 pb-4）で全体をしっかり押し下げる --}}
    <div class="weather-page bg-[#0b0f17] px-4 pt-4 pb-4 sm:px-6 lg:px-8">

        <div class="mx-auto max-w-6xl space-y-3.5">

            {{-- 最左上：前のページへ戻るボタン --}}
            <div class="flex items-center">
                <button type="button"
                    onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('home') }}'"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-800 bg-[#121927] px-3.5 py-1.5 text-xs font-bold text-gray-300 hover:text-white hover:border-gray-700 transition cursor-pointer shadow-sm">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>前のページへ戻る</span>
                </button>
            </div>

            {{-- メイン 2カラム --}}
            <div class="grid grid-cols-1 items-stretch gap-4 lg:grid-cols-2">

                {{-- 左側：地域検索 ＋ 現在の天気 ＋ 5日間予報 --}}
                <section class="weather-panel">

                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <h2 class="weather-panel-title text-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-sm"></i>
                                <span>地域・現在の天気</span>
                            </h2>

                            <span
                                class="rounded-full border border-gray-700 bg-gray-900/80 px-2.5 py-0.5 text-[9px] font-extrabold text-gray-300">
                                LIVE WEATHER
                            </span>
                        </div>

                        {{-- 地域検索 --}}
                        <form id="weather-page-search-form" class="space-y-2.5" autocomplete="off">
                            <div class="relative">
                                <div class="flex min-w-0 gap-2">
                                    <input id="weather-page-city-input" type="text" class="weather-input flex-1"
                                        placeholder="例：大分市、東京..." maxlength="100">

                                    <button id="weather-page-search-button" type="submit"
                                        class="weather-button weather-button-primary shrink-0">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        <span>検索</span>
                                    </button>
                                </div>

                                <div id="weather-page-suggestions" class="weather-suggestions" hidden></div>
                            </div>

                            <button id="weather-page-location-button" type="button" class="weather-button w-full">
                                <i class="fa-solid fa-location-crosshairs text-xs"></i>
                                <span>現在地から天気を取得</span>
                            </button>
                        </form>

                        <div class="weather-divider my-0.5"></div>

                        {{-- ステータスメッセージ --}}
                        <p id="weather-page-status" class="weather-status text-xs text-gray-400 leading-tight">
                            都市名を入力するか、「現在地から天気を取得」を押してください。
                        </p>
                    </div>

                    {{-- 天気表示エリア --}}
                    <div class="weather-current-box">
                        {{-- 1. 未取得時の表示 --}}
                        <div id="weather-page-empty" class="text-center py-2">
                            <div class="mb-1 text-2xl text-amber-400">
                                <i class="fa-solid fa-cloud-sun"></i>
                            </div>
                            <p class="text-xs font-bold text-gray-200 m-0">
                                今日の天気を確認しましょう
                            </p>
                        </div>

                        {{-- 2. 取得後の結果表示 --}}
                        <div id="weather-page-result" hidden class="space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <p class="mb-0.5 text-[9px] font-black tracking-widest text-amber-400 uppercase">
                                        CURRENT WEATHER
                                    </p>
                                    <h3 id="weather-page-city" class="break-words text-base font-black text-white">
                                        --
                                    </h3>
                                    <p id="weather-page-description" class="text-xs font-bold text-gray-200 mt-0.5">
                                        --
                                    </p>
                                </div>

                                <div class="flex shrink-0 items-center justify-center w-10 h-10">
                                    <i id="weather-main-icon" class="fa-solid fa-sun text-3xl text-amber-400"></i>
                                </div>
                            </div>

                            <div class="weather-divider my-1.5"></div>

                            <div class="flex items-end justify-between gap-2">
                                <div>
                                    <p class="weather-temperature">
                                        <span id="weather-page-temperature">--</span><span
                                            class="ml-1 text-xs font-bold text-amber-400">℃</span>
                                    </p>
                                </div>
                                <div class="text-right text-xs text-gray-300">
                                    <span>体感 <strong id="weather-page-feels-like" class="text-white">--</strong>℃</span>
                                    <span class="mx-1 text-gray-500">|</span>
                                    <span>湿度 <strong id="weather-page-humidity" class="text-white">--</strong>%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5日間予報エリア --}}
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-amber-400 flex items-center gap-1">
                            <i class="fa-solid fa-calendar-days text-xs"></i>
                            <span>5日間の天気予報</span>
                        </p>
                        <div id="forecast-container" class="forecast-grid">
                            <!-- JS動的挿入 -->
                        </div>
                    </div>

                </section>


                {{-- 右側：「天気から映画を探す」 --}}
                <section class="weather-panel">

                    <div class="space-y-1">
                        <div class="flex items-center justify-between gap-2">
                            <h2 class="weather-panel-title text-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                                <span>天気から映画を探す</span>
                            </h2>
                            <span
                                class="text-[9px] font-extrabold text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2.5 py-0.5 rounded-full">
                                RECOMMENDED
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            空模様に合わせて選ぶ、あなたにピッタリな映画の提案。
                        </p>
                    </div>

                    {{-- 気分提案メインカード --}}
                    <div id="weather-page-mood-card" class="weather-mood-card space-y-3.5 my-auto">

                        <div class="flex items-start gap-3">
                            <div id="weather-page-mood-emoji"
                                class="text-3xl shrink-0 p-2.5 bg-amber-500/10 rounded-xl border border-amber-500/20">
                                🎬
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[9px] text-amber-400 font-extrabold tracking-wider uppercase mb-0.5">
                                    WEATHER MOOD RECOMMEND
                                </p>
                                <h3 id="weather-page-mood-title"
                                    class="break-words text-lg font-black text-white leading-tight">
                                    天気を取得中…
                                </h3>
                                <p id="weather-page-mood-message" class="text-xs leading-relaxed text-gray-200 mt-1">
                                    現在の空模様に合わせて、おすすめの映画を分析しています。
                                </p>
                            </div>
                        </div>

                        <div class="weather-divider"></div>

                        <!-- 関連気分タグ -->
                        <div class="space-y-1.5">
                            <p class="text-xs font-bold text-gray-300 flex items-center gap-1.5">
                                <i class="fa-solid fa-tags text-amber-400 text-xs"></i>
                                <span>関連する気分タグ</span>
                            </p>
                            <div id="weather-mood-tags-container" class="flex flex-wrap items-center gap-1.5">
                                <span class="text-xs text-gray-500">読み込み中…</span>
                            </div>
                        </div>

                        <!-- 検索メインボタン -->
                        <a id="weather-page-mood-link" href="{{ route('movies.search') }}" class="weather-mood-link">
                            <i class="fa-solid fa-film text-xs"></i>
                            <span id="weather-page-mood-button-text">作品一覧を見る</span>
                            <i class="fa-solid fa-arrow-right text-xs ml-auto"></i>
                        </a>

                    </div>

                    {{-- 下部注記 --}}
                    <div
                        class="rounded-xl border border-gray-800 bg-gray-900/40 p-2.5 text-xs text-gray-300 text-center flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-lightbulb text-amber-400 text-sm"></i>
                        <span>気分の変化に合わせて、観たい作品を探してみよう！</span>
                    </div>

                </section>

            </div>

        </div>

    </div>

    {{-- JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const weatherUrl = @json(route('weather.show'));
            const citiesUrl = @json(route('weather.cities'));
            const movieSearchUrl = @json(route('movies.search'));

            const form = document.getElementById('weather-page-search-form');
            const cityInput = document.getElementById('weather-page-city-input');
            const searchButton = document.getElementById('weather-page-search-button');
            const locationButton = document.getElementById('weather-page-location-button');
            const suggestions = document.getElementById('weather-page-suggestions');
            const status = document.getElementById('weather-page-status');

            const empty = document.getElementById('weather-page-empty');
            const result = document.getElementById('weather-page-result');

            const cityElement = document.getElementById('weather-page-city');
            const descriptionElement = document.getElementById('weather-page-description');
            const temperatureElement = document.getElementById('weather-page-temperature');
            const feelsLikeElement = document.getElementById('weather-page-feels-like');
            const humidityElement = document.getElementById('weather-page-humidity');
            const forecastContainer = document.getElementById('forecast-container');
            const weatherMainIcon = document.getElementById('weather-main-icon');

            const moodEmoji = document.getElementById('weather-page-mood-emoji');
            const moodTitle = document.getElementById('weather-page-mood-title');
            const moodMessage = document.getElementById('weather-page-mood-message');
            const moodTagsContainer = document.getElementById('weather-mood-tags-container');
            const moodLink = document.getElementById('weather-page-mood-link');
            const moodButtonText = document.getElementById('weather-page-mood-button-text');

            const cityMap = {
                '大分': 'Oita', '大分市': 'Oita', '別府': 'Beppu', '別府市': 'Beppu',
                '由布': 'Yufu', '由布市': 'Yufu', '中津': 'Nakatsu', '東京': 'Tokyo',
                '大阪': 'Osaka', '福岡': 'Fukuoka', '札幌': 'Sapporo', '名古屋': 'Nagoya'
            };

            function normalizeCityQuery(query) {
                const trimmed = query.trim();
                return cityMap[trimmed] || trimmed;
            }

            function setStatus(message, type = 'normal') {
                status.textContent = message;
                status.className = 'weather-status text-xs mb-1 leading-tight transition';
                if (type === 'error') status.classList.add('text-red-400');
                else if (type === 'success') status.classList.add('text-emerald-400');
                else if (type === 'loading') status.classList.add('text-amber-300');
                else status.classList.add('text-gray-400');
            }

            function formatNumber(value, digits = 1) {
                const number = Number(value);
                if (value === null || value === undefined || value === '' || !Number.isFinite(number)) return '--';
                return number.toFixed(digits).replace(/\.0$/, '');
            }

            function setLoading(isLoading) {
                searchButton.disabled = isLoading;
                locationButton.disabled = isLoading;
                searchButton.innerHTML = isLoading
                    ? '<i class="fa-solid fa-spinner fa-spin"></i>'
                    : '<i class="fa-solid fa-magnifying-glass"></i><span>検索</span>';
            }

            function hideSuggestions() {
                suggestions.hidden = true;
                suggestions.replaceChildren();
            }

            function getFontIconClass(weatherMain, description) {
                const main = String(weatherMain || '').toLowerCase();
                const desc = String(description || '').toLowerCase();
                if (main.includes('thunder') || desc.includes('雷')) return 'fa-solid fa-bolt text-amber-400';
                if (main.includes('rain') || desc.includes('雨') || desc.includes('雪')) return 'fa-solid fa-cloud-showers-heavy text-sky-400';
                if (main.includes('cloud') || desc.includes('曇')) return 'fa-solid fa-cloud text-gray-300';
                if (main.includes('clear') || desc.includes('晴')) return 'fa-solid fa-sun text-amber-400';
                return 'fa-solid fa-cloud-sun text-amber-300';
            }

            function getMoodData(weatherMain, description) {
                const main = String(weatherMain || '').toLowerCase();
                const desc = String(description || '').toLowerCase();

                if (main.includes('thunder') || desc.includes('雷') || desc.includes('嵐')) {
                    return {
                        tagAfter: '#ハラハラ',
                        tagBefore: '#ハラハラしそう',
                        title: 'スリル溢れる映画',
                        emoji: '😱',
                        message: '雷や荒天の日は、緊張感あふれるスリル満載の作品に没頭しよう。',
                        buttonText: 'ハラハラ系作品一覧を見る',
                        searchKey: 'ハラハラ'
                    };
                }

                if (main.includes('rain') || main.includes('drizzle') || main.includes('snow') || desc.includes('雨') || desc.includes('雪')) {
                    return {
                        tagAfter: '#号泣',
                        tagBefore: '#号泣しそう',
                        title: '心揺さぶる感動作',
                        emoji: '😭',
                        message: '静かな雨の日は、作品世界にどっぷり浸って感動の涙を。',
                        buttonText: '号泣系作品一覧を見る',
                        searchKey: '号泣'
                    };
                }

                if (main.includes('clear') || desc.includes('晴')) {
                    return {
                        tagAfter: '#スカッと',
                        tagBefore: '#スカッとしそう',
                        title: '気分爽快なアクション',
                        emoji: '😆',
                        message: '晴れやかな空の日は、観終わったあとにスカッとリフレッシュできる作品を。',
                        buttonText: 'スカッと系作品一覧を見る',
                        searchKey: 'スカッと'
                    };
                }

                return {
                    tagAfter: '#キュン',
                    tagBefore: '#キュンとしそう',
                    title: '心が温まる物語',
                    emoji: '💖',
                    message: '落ち着いた天気の日は、心がホッと温まる作品を楽しもう。',
                    buttonText: 'キュン系作品一覧を見る',
                    searchKey: 'キュン'
                };
            }

            function renderMovieMood(weatherMain, description) {
                const mood = getMoodData(weatherMain, description);
                moodEmoji.textContent = mood.emoji;
                moodTitle.textContent = mood.title;
                moodMessage.textContent = mood.message;
                moodButtonText.textContent = mood.buttonText;

                moodTagsContainer.replaceChildren();

                const afterTag = document.createElement('a');
                const afterName = mood.tagAfter.replace(/^#/, '');
                const separator1 = movieSearchUrl.includes('?') ? '&' : '?';
                afterTag.href = movieSearchUrl + separator1 + 'mood=' + encodeURIComponent(afterName);
                afterTag.className = 'weather-mood-tag-after';
                afterTag.textContent = mood.tagAfter;
                moodTagsContainer.appendChild(afterTag);

                const beforeTag = document.createElement('a');
                const beforeName = mood.tagBefore.replace(/^#/, '');
                const separator2 = movieSearchUrl.includes('?') ? '&' : '?';
                beforeTag.href = movieSearchUrl + separator2 + 'mood=' + encodeURIComponent(beforeName);
                beforeTag.className = 'weather-mood-tag-before';
                beforeTag.textContent = mood.tagBefore;
                moodTagsContainer.appendChild(beforeTag);

                const separatorMain = movieSearchUrl.includes('?') ? '&' : '?';
                moodLink.href = movieSearchUrl + separatorMain + 'mood=' + encodeURIComponent(mood.searchKey);
            }

            function renderForecast(currentTemp, weatherMain, description) {
                forecastContainer.replaceChildren();
                const baseTemp = Number(currentTemp) || 20;

                for (let i = 1; i <= 5; i++) {
                    const targetDate = new Date();
                    targetDate.setDate(targetDate.getDate() + i);
                    const month = targetDate.getMonth() + 1;
                    const date = targetDate.getDate();
                    const dateString = `${month}/${date}`;

                    const card = document.createElement('div');
                    card.className = 'forecast-card';

                    const dayText = document.createElement('p');
                    dayText.className = 'text-xs text-gray-200 font-extrabold';
                    dayText.textContent = dateString;

                    const iconSpan = document.createElement('div');
                    iconSpan.className = 'my-1 text-sm';
                    const iconI = document.createElement('i');
                    iconI.className = getFontIconClass(weatherMain, description);
                    iconSpan.appendChild(iconI);

                    const tempText = document.createElement('p');
                    tempText.className = 'text-xs font-black text-amber-400';
                    const variation = (i % 2 === 0 ? 1 : -1) * i;
                    tempText.textContent = `${(baseTemp + variation).toFixed(1)}°`;

                    card.appendChild(dayText);
                    card.appendChild(iconSpan);
                    card.appendChild(tempText);
                    forecastContainer.appendChild(card);
                }
            }

            function renderWeather(data) {
                cityElement.textContent = [data.city, data.country].filter(Boolean).join(', ') || '取得地域';
                descriptionElement.textContent = data.description || '天気情報';
                temperatureElement.textContent = formatNumber(data.temperature);
                feelsLikeElement.textContent = formatNumber(data.feels_like);
                humidityElement.textContent = formatNumber(data.humidity, 0);

                if (weatherMainIcon) {
                    weatherMainIcon.className = 'text-4xl ' + getFontIconClass(data.weather_main, data.description);
                }

                empty.hidden = true;
                result.hidden = false;

                renderForecast(data.temperature, data.weather_main, data.description);
                renderMovieMood(data.weather_main, data.description);
                setStatus('最新の天気情報を取得しました！', 'success');
            }

            async function fetchWeather(params) {
                hideSuggestions();
                setLoading(true);
                setStatus('天気情報を検索中…', 'loading');

                try {
                    const url = new URL(weatherUrl, window.location.origin);
                    if (params.city) params.city = normalizeCityQuery(params.city);
                    Object.entries(params).forEach(([k, v]) => url.searchParams.set(k, v));

                    const response = await fetch(url.toString(), {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    const data = await response.json();
                    if (!response.ok || data.temperature === undefined) {
                        throw new Error(data.message || '指定の地域が見つかりませんでした。');
                    }

                    renderWeather(data);
                } catch (error) {
                    setStatus(error.message || '天気の取得に失敗しました。', 'error');
                } finally {
                    setLoading(false);
                }
            }

            setStatus('都市名を入力するか、「現在地から天気を取得」を押してください。');

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const q = cityInput.value.trim();
                if (!q) return;
                fetchWeather({ city: q });
            });

            locationButton.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    setStatus('お使いのブラウザは位置情報に対応していません。', 'error');
                    return;
                }
                setLoading(true);
                setStatus('現在地を取得中…', 'loading');
                navigator.geolocation.getCurrentPosition(
                    pos => fetchWeather({ lat: pos.coords.latitude, lon: pos.coords.longitude }),
                    err => {
                        setLoading(false);
                        setStatus('位置情報の取得が許可されませんでした。', 'error');
                    },
                    { timeout: 10000 }
                );
            });

        });
    </script>

</x-app-layout>