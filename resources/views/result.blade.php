<x-app-layout :popularMovies="$popularMovies ?? []">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- =====================================================
             🔙 直前のページに戻る
        ====================================================== -->
        <div class="mb-4">
            <button type="button" onclick="goBackOrHome()"
                class="inline-flex items-center gap-2 bg-[#121927] hover:bg-gray-800 text-gray-300 border border-gray-800 font-bold px-4 py-2 rounded-full text-xs transition cursor-pointer">
                <i class="fa-solid fa-arrow-left"></i>
                一つ前に戻る
            </button>
        </div>


        <!-- =====================================================
             🎭 見出し ＆ 別の作品を見る
        ====================================================== -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">

            <h1 class="text-xl sm:text-2xl font-black text-white m-0">

                @if(!empty($query))

                    🔍 「<span class="text-amber-400">{{ $query }}</span>」の検索結果

                @elseif(!empty($mood))

                    @php
                        $moodEmoji = [
                            '号泣' => '😭',
                            'スカッと' => '😆',
                            'ハラハラ' => '😱',
                            'キュン' => '💖',
                        ][$mood] ?? '🎭';
                    @endphp

                    {{ $moodEmoji }}
                    「<span class="text-amber-400">{{ $mood }}</span>」のおすすめ6選

                @else

                    🎬 おすすめ作品一覧

                @endif

            </h1>


            <!-- =================================================
                 🔄 Mood再検索
            ================================================== -->
            @if(!empty($mood))

                <a href="{{ route('movies.search', ['mood' => $mood]) }}"
                    class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-black font-extrabold px-4 py-2 rounded-full text-xs transition shadow cursor-pointer shrink-0">
                    <i class="fa-solid fa-rotate-right"></i>
                    別の作品を見る
                </a>

            @endif

        </div>


        <!-- =====================================================
             🍿 おすすめ映画6作品
        ====================================================== -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3.5 mb-10">

            @forelse($movies as $m)

                    <a href="{{ route('movies.show', $m['id']) }}"
                        class="bg-[#0f172a] border border-slate-800/80 rounded-2xl p-2.5 flex flex-col shadow-lg hover:border-amber-500/50 transition group">

                        <!-- =================================================
                                 🎬 ポスター
                            ================================================== -->
                        <div class="relative aspect-[2/3] w-full overflow-hidden rounded-xl bg-black mb-2">

                            @php
                                $imgSrc = !empty($m['poster_path'])
                                    ? 'https://image.tmdb.org/t/p/w500' . $m['poster_path']
                                    : (!empty($m['backdrop_path'])
                                        ? 'https://image.tmdb.org/t/p/w500' . $m['backdrop_path']
                                        : null);
                            @endphp

                            @if($imgSrc)

                                <img src="{{ $imgSrc }}" alt="{{ $m['title'] ?? '映画ポスター' }}" loading="lazy"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">

                            @else

                                <div class="w-full h-full flex items-center justify-center text-gray-600 text-xs">
                                    🎬
                                </div>

                            @endif

                        </div>


                        <!-- =================================================
                                 📝 タイトル・公開年・評価
                            ================================================== -->
                        <div class="flex-1 flex flex-col justify-between px-0.5">

                            <h3 class="text-[12px] font-bold text-white group-hover:text-amber-400 transition truncate mb-1"
                                title="{{ $m['title'] ?? '' }}">
                                {{ $m['title'] ?? 'タイトル不明' }}
                            </h3>


                            <div class="flex items-center justify-between text-[10px] text-slate-400 mt-auto">

                                <span>
                                    {{ !empty($m['release_date'])
                ? substr($m['release_date'], 0, 4) . '年'
                : '不明' }}
                                </span>


                                <div class="flex items-center gap-1 text-amber-400 font-extrabold">

                                    <i class="fa-solid fa-star text-[9px]"></i>

                                    <span>
                                        {{ number_format((float) ($m['vote_average'] ?? 0), 1) }}
                                    </span>

                                </div>

                            </div>

                        </div>

                    </a>

            @empty

                <div class="col-span-full text-center py-12 text-gray-500 text-sm">
                    該当する映画が見つかりませんでした。
                    <br>
                    別のキーワードや感情タグでお試しください。
                </div>

            @endforelse

        </div>

    </div>


    <!-- =========================================================
         🔙 遷移元に戻る
    ========================================================== -->
    <script>
        function goBackOrHome() {
            if (
                document.referrer &&
                document.referrer !== location.href
            ) {
                window.location.href = document.referrer;
            } else {
                history.back();
            }
        }
    </script>

</x-app-layout>