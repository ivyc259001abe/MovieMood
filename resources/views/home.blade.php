<x-app-layout :popularMovies="$popularMovies ?? []">

    <style>
        /* =========================================================
           検索候補用のスタイリッシュな細いスクロールバー
        ========================================================== */

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
           HOME全体

           ・PCではPOPULAR MOVIESが少し見える余白を残す
           ・コンテンツ自体を無理に画面いっぱいにしない
        ========================================================== */

        .home-content {
            width: 100%;
        }


        /* =========================================================
           気分タグ

           4つの感情をMovieMoodの重要機能として目立たせる
        ========================================================== */

        .mood-card {
            position: relative;
        }

        .mood-button {
            min-height: 92px;
        }


        /* =========================================================
           PC画面での微調整

           画面が十分に大きい場合でも、
           メインコンテンツが縦に伸びすぎないようにする
        ========================================================== */

        @media (min-width: 1024px) {

            .home-container {
                padding-top: 24px;
                padding-bottom: 18px;
            }

            .mood-button {
                min-height: 88px;
            }

        }


        /* =========================================================
           タブレット・スマートフォン
        ========================================================== */

        @media (max-width: 1023px) {

            .home-container {
                padding-top: 20px;
                padding-bottom: 20px;
            }

        }


        @media (max-width: 640px) {

            .mood-button {
                min-height: 82px;
            }

        }
    </style>


    <!-- =========================================================
         HOME メインコンテンツ
    ========================================================== -->

    <div class="home-content max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 home-container">

        <!-- =====================================================
             PC
             左：7カラム
             右：5カラム

             スマートフォン
             1カラム
        ====================================================== -->

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">


            <!-- =================================================
                 左側：7カラム
            ================================================== -->

            <div class="lg:col-span-7 flex flex-col gap-4">


                <!-- =================================================
                     🌟 WELCOME
                ================================================== -->

                <div
                    class="bg-[#121927] border border-gray-800/80 rounded-2xl p-4 sm:p-5 shadow-xl flex items-center gap-4">

                    <!-- アバター -->

                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 rounded-full overflow-hidden bg-gray-900 border-2 border-amber-500 flex items-center justify-center shrink-0">

                        @if(Auth::check() && Auth::user()->avatar)

                            <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                                class="w-full h-full object-cover">

                        @else

                            <i class="fa-solid fa-user text-gray-400 text-lg"></i>

                        @endif

                    </div>


                    <!-- Welcome -->

                    <div>

                        <span class="text-xs text-gray-400 font-bold tracking-wide flex items-center gap-1">

                            Welcome!

                        </span>


                        <h1 class="text-base sm:text-xl font-black mt-0.5">

                            <span class="text-amber-400">

                                {{ Str::limit(Auth::user()->name ?? 'ゲスト', 15, '') }}

                            </span>

                            <span class="text-white">

                                さん！

                            </span>

                        </h1>

                    </div>

                </div>



                <!-- =================================================
                     🔍 キーワード検索
                ================================================== -->

                <div class="bg-[#121927] border border-gray-800/80 rounded-2xl p-4 shadow-xl space-y-3 relative">

                    <p class="text-xs font-bold text-amber-500 flex items-center gap-1.5">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <span>
                            キーワード検索
                        </span>

                    </p>


                    <form action="{{ route('movies.search') }}" method="GET" class="flex gap-2 w-full relative">

                        <div class="relative flex-grow">

                            <input type="text" id="searchInput" name="query" value="{{ request('query') }}"
                                placeholder="キーワード検索（映画タイトル、キャストなど）" autocomplete="off" required
                                class="w-full bg-[#05080e] border border-gray-800 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition">


                            <!-- 検索候補 -->

                            <div id="autocompleteResults"
                                class="hidden absolute top-full left-0 right-0 bg-[#0b0e15] border border-gray-800 rounded-xl mt-1 max-h-60 overflow-y-auto custom-scrollbar z-50 shadow-2xl">
                            </div>

                        </div>


                        <!-- 検索ボタン -->

                        <button type="submit"
                            class="bg-amber-500 hover:bg-amber-400 text-black font-extrabold px-6 py-3 rounded-xl text-sm transition shrink-0 cursor-pointer">

                            検索

                        </button>

                    </form>

                </div>



                <!-- =================================================
                     🎭 気分タグ

                     ★ MovieMoodの重要機能なので大きめに表示
                     ★ flex-growは使用しない
                     ★ 右側のレイアウトには影響させない
                ================================================== -->

                <div
                    class="mood-card bg-[#121927] border border-gray-800/80 rounded-2xl p-5 sm:p-6 shadow-xl space-y-5">


                    <!-- =================================================
                         見出し
                    ================================================== -->

                    <div class="text-center space-y-1.5">

                        <p class="text-lg sm:text-xl font-black text-amber-400 tracking-wide">

                            🎭 今のあなたの「気分」は？

                        </p>


                        <p class="text-xs sm:text-sm text-gray-400">

                            今の気分にぴったりの映画を探そう

                        </p>

                    </div>



                    <!-- =================================================
                         4つの感情タグ
                    ================================================== -->

                    <div class="grid grid-cols-2 gap-3">


                        <!-- =================================================
                             😭 #号泣
                        ================================================== -->

                        <a href="{{ route('movies.search', ['mood' => '号泣']) }}" class="mood-button flex flex-col items-center justify-center gap-1.5
                                   bg-[#0b0e15]
                                   hover:bg-amber-500/10
                                   border border-gray-800
                                   hover:border-amber-500/70
                                   rounded-xl
                                   py-4
                                   px-3
                                   transition duration-200
                                   shadow group">

                            <span class="text-2xl sm:text-3xl group-hover:scale-110 transition duration-200">

                                😭

                            </span>


                            <span class="text-sm sm:text-base font-black text-gray-200 group-hover:text-amber-400">

                                #号泣

                            </span>

                        </a>



                        <!-- =================================================
                             😆 #スカッと
                        ================================================== -->

                        <a href="{{ route('movies.search', ['mood' => 'スカッと']) }}" class="mood-button flex flex-col items-center justify-center gap-1.5
                                   bg-[#0b0e15]
                                   hover:bg-amber-500/10
                                   border border-gray-800
                                   hover:border-amber-500/70
                                   rounded-xl
                                   py-4
                                   px-3
                                   transition duration-200
                                   shadow group">

                            <span class="text-2xl sm:text-3xl group-hover:scale-110 transition duration-200">

                                😆

                            </span>


                            <span class="text-sm sm:text-base font-black text-gray-200 group-hover:text-amber-400">

                                #スカッと

                            </span>

                        </a>



                        <!-- =================================================
                             😱 #ハラハラ
                        ================================================== -->

                        <a href="{{ route('movies.search', ['mood' => 'ハラハラ']) }}" class="mood-button flex flex-col items-center justify-center gap-1.5
                                   bg-[#0b0e15]
                                   hover:bg-amber-500/10
                                   border border-gray-800
                                   hover:border-amber-500/70
                                   rounded-xl
                                   py-4
                                   px-3
                                   transition duration-200
                                   shadow group">

                            <span class="text-2xl sm:text-3xl group-hover:scale-110 transition duration-200">

                                😱

                            </span>


                            <span class="text-sm sm:text-base font-black text-gray-200 group-hover:text-amber-400">

                                #ハラハラ

                            </span>

                        </a>



                        <!-- =================================================
                             💖 #キュン
                        ================================================== -->

                        <a href="{{ route('movies.search', ['mood' => 'キュン']) }}" class="mood-button flex flex-col items-center justify-center gap-1.5
                                   bg-[#0b0e15]
                                   hover:bg-amber-500/10
                                   border border-gray-800
                                   hover:border-amber-500/70
                                   rounded-xl
                                   py-4
                                   px-3
                                   transition duration-200
                                   shadow group">

                            <span class="text-2xl sm:text-3xl group-hover:scale-110 transition duration-200">

                                💖

                            </span>


                            <span class="text-sm sm:text-base font-black text-gray-200 group-hover:text-amber-400">

                                #キュン

                            </span>

                        </a>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 右側：5カラム

                 🌙 TODAY'S PICKUP

                 ★ 左側の高さに合わせるための
                   flex-grow等は使用しない
                 ★ PCでは右側に固定
            ================================================== -->

            <div
                class="lg:col-span-5 bg-[#121927] border border-gray-800/80 rounded-2xl p-5 shadow-xl flex flex-col items-center justify-center text-center space-y-4">


                <!-- =================================================
                     TODAY'S PICKUP ヘッダー
                ================================================== -->

                <div class="space-y-2 flex flex-col items-center">


                    <!-- ラベル -->

                    <span
                        class="text-[10px] text-amber-500 font-extrabold tracking-widest uppercase bg-amber-500/10 px-3 py-1 rounded-full border border-amber-500/20 inline-block">

                        🌙 TODAY'S PICKUP

                    </span>



                    <!-- =================================================
                         選択された感情タグ
                    ================================================== -->

                    @if(!empty($selectedTheme))

                        <a href="{{ route('movies.search', ['mood' => $selectedTheme['mood']]) }}"
                            class="flex items-center gap-1.5 px-3 py-1 rounded-lg border text-xs font-bold pt-1 transition cursor-pointer {{ $selectedTheme['bg'] }}">

                            <span>
                                {{ $selectedTheme['emoji'] }}
                            </span>

                            <span>
                                {{ $selectedTheme['tag'] }}
                            </span>

                        </a>

                    @endif



                    <!-- =================================================
                         キャッチコピー
                    ================================================== -->

                    @if(!empty($selectedMessage))

                        <h2 class="text-xs sm:text-sm font-extrabold text-amber-300">

                            「{{ $selectedMessage }}」

                        </h2>

                    @endif

                </div>



                <!-- =================================================
                     🎬 映画カード
                ================================================== -->

                @if(!empty($pickup))

                    <a href="{{ route('movies.show', $pickup['id'] ?? 0) }}"
                        class="block group cursor-pointer w-full max-w-[180px] sm:max-w-[200px] mx-auto space-y-2.5">


                        <!-- ポスター -->

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


                        <!-- 評価 -->

                        <p class="text-xs text-amber-400 font-extrabold flex items-center justify-center gap-1">

                            <i class="fa-solid fa-star text-[11px]"></i>

                            <span>
                                {{ number_format((float) ($pickup['vote_average'] ?? 0), 1) }}
                            </span>

                        </p>

                    </a>

                @endif

            </div>

        </div>

    </div>



    <!-- =========================================================
         🔍 オートコンプリート JavaScript
    ========================================================== -->

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const searchInput =
                document.getElementById('searchInput');

            const resultsBox =
                document.getElementById('autocompleteResults');

            let timeoutId = null;


            /* =====================================================
               要素が存在しない場合
            ====================================================== */

            if (!searchInput || !resultsBox) {
                return;
            }



            /* =====================================================
               入力時
            ====================================================== */

            searchInput.addEventListener('input', function () {

                clearTimeout(timeoutId);

                const query = this.value.trim();


                /* -------------------------------------------------
                   入力が空の場合
                -------------------------------------------------- */

                if (query.length < 1) {

                    resultsBox.classList.add('hidden');

                    resultsBox.innerHTML = '';

                    return;

                }



                /* -------------------------------------------------
                   300ms待ってから検索
                -------------------------------------------------- */

                timeoutId = setTimeout(() => {

                    fetch(
                        `/api/movies/search?query=${encodeURIComponent(query)}`
                    )

                        .then(response => response.json())

                        .then(data => {


                            /* -------------------------------------
                               検索結果がない場合
                            -------------------------------------- */

                            if (!data || data.length === 0) {

                                resultsBox.classList.add('hidden');

                                return;

                            }


                            let html = '';



                            /* -------------------------------------
                               検索結果を表示
                            -------------------------------------- */

                            data.forEach(item => {


                                /* ポスター */

                                const poster = item.poster_path

                                    ? `https://image.tmdb.org/t/p/w92${item.poster_path}`

                                    : 'https://via.placeholder.com/40x60?text=No';



                                /* 公開年 */

                                const year =
                                    item.release_year ||
                                    (
                                        item.release_date
                                            ? item.release_date.substring(0, 4)
                                            : ''
                                    );



                                /* HTML */

                                html += `

                                    <a
                                        href="/movies/${item.id}"
                                        class="flex items-center gap-3 p-3 border-b border-gray-800/60 hover:bg-gray-800/80 transition text-left text-white text-xs">

                                        <img
                                            src="${poster}"
                                            class="w-8 h-12 rounded object-cover shrink-0">

                                        <div class="overflow-hidden">

                                            <div class="font-bold truncate">
                                                ${item.title}
                                            </div>

                                            <div class="text-[10px] text-gray-400">
                                                ${year}
                                            </div>

                                        </div>

                                    </a>

                                `;

                            });



                            /* 結果を表示 */

                            resultsBox.innerHTML = html;

                            resultsBox.classList.remove('hidden');

                        })


                        /* -----------------------------------------
                           エラー時
                        ------------------------------------------ */

                        .catch(() => {

                            resultsBox.classList.add('hidden');

                        });

                }, 300);

            });



            /* =====================================================
               検索ボックス以外をクリックしたら閉じる
            ====================================================== */

            document.addEventListener('click', function (e) {

                if (
                    !searchInput.contains(e.target) &&
                    !resultsBox.contains(e.target)
                ) {

                    resultsBox.classList.add('hidden');

                }

            });

        });

    </script>


</x-app-layout>