<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

        <!-- ← 一つ前に戻る リンク -->
        <div class="mb-2.5">
            <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('movies.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>一つ前に戻る</span>
            </a>
        </div>

        @php
            $movieId = $movie['id'] ?? $movie->id ?? 0;
            $movieTitle = $movie['title'] ?? $movie->title ?? '映画タイトル';
            $posterPath = $movie['poster_path'] ?? $movie->poster_path ?? null;
            $posterUrl = $posterPath
                ? (str_starts_with($posterPath, 'http') ? $posterPath : 'https://image.tmdb.org/t/p/w500' . $posterPath)
                : null;
            $voteAverage = $movie['vote_average'] ?? $movie->vote_average ?? null;
            $releaseDate = $movie['release_date'] ?? $movie->release_date ?? null;
            $runtime = $movie['runtime'] ?? $movie->runtime ?? null;
            $director = $movie['director'] ?? $movie->director ?? null;
            $overview = $movie['overview'] ?? $movie->overview ?? null;
        @endphp

        <!-- 🎬 左右完全等幅（5:5 = grid-cols-2） ＆ 高さ完全一致（items-stretch） -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-stretch">

            <!-- 【左側】映画情報カード -->
            <div
                class="bg-gray-900/90 border border-gray-800 rounded-2xl shadow-2xl overflow-hidden relative flex flex-col justify-between">

                <!-- 上部コンテンツ（タイトル・ポスターサムネイル・あらすじ） -->
                <div class="p-4 sm:p-5 space-y-3.5 relative z-10 flex-1">
                    <!-- 上部：小ポスター ＆ タイトル情報 -->
                    <div class="flex gap-3.5 items-start">
                        <!-- サムネイルポスター -->
                        <div
                            class="w-24 sm:w-28 shrink-0 aspect-[2/3] rounded-xl overflow-hidden border border-gray-700/80 shadow-md bg-black/50">
                            @if($posterUrl)
                                <img src="{{ $posterUrl }}" alt="{{ $movieTitle }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-500 gap-1">
                                    <i class="fa-solid fa-film text-xl"></i>
                                    <span class="text-[10px]">No Image</span>
                                </div>
                            @endif
                        </div>

                        <!-- タイトル ＆ 詳細情報 -->
                        <div class="space-y-1.5 flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <h1 class="text-lg sm:text-xl font-black text-white leading-tight">
                                    {{ $movieTitle }}
                                </h1>
                            </div>

                            <div class="flex flex-wrap items-center gap-2.5 text-xs font-bold text-gray-300">
                                @if($voteAverage)
                                    <div class="flex items-center gap-1 text-amber-400">
                                        <i class="fa-solid fa-star"></i>
                                        <span>{{ number_format((float) $voteAverage, 1) }}</span>
                                    </div>
                                @endif

                                @if($releaseDate)
                                    <div class="flex items-center gap-1 text-gray-300">
                                        <i class="fa-regular fa-calendar-days text-amber-500"></i>
                                        <span>{{ date('Y年n月', strtotime($releaseDate)) }}</span>
                                    </div>
                                @endif

                                @if($runtime)
                                    <div class="flex items-center gap-1 text-gray-300">
                                        <i class="fa-regular fa-clock text-amber-500"></i>
                                        <span>{{ $runtime }}分</span>
                                    </div>
                                @endif
                            </div>

                            @if($director)
                                <div class="text-xs text-gray-400">
                                    監督: <span class="text-gray-200 font-medium">{{ $director }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- あらすじエリア -->
                    <div class="bg-black/60 backdrop-blur-md border border-gray-800 p-3 rounded-xl space-y-1">
                        <span class="text-xs font-extrabold text-amber-500 flex items-center gap-1">
                            <i class="fa-solid fa-align-left"></i>
                            あらすじ
                        </span>
                        @if(!empty($overview))
                            <p class="text-xs text-gray-300 leading-relaxed line-clamp-3">
                                {{ $overview }}
                            </p>
                        @else
                            <p class="text-xs text-gray-400 leading-relaxed italic">
                                ※日本語あらすじ情報は準備中です。
                            </p>
                        @endif
                    </div>
                </div>

                <!-- 🖼️ 下部：ポスター画像ビジュアルエリア -->
                <div
                    class="relative w-full h-40 sm:h-48 overflow-hidden border-t border-gray-800/80 bg-black/60 shrink-0">
                    @if($posterUrl)
                        <img src="{{ $posterUrl }}" alt="Movie Visual"
                            class="w-full h-full object-cover object-top opacity-60 hover:opacity-80 transition duration-500">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-gray-900/80 pointer-events-none">
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-600 text-xs font-bold">Movie
                            Mood Visual</div>
                    @endif
                </div>

            </div>

            <!-- 【右側】みんなのレビュー・アクションエリア -->
            <div
                class="bg-gray-900/90 border border-gray-800 rounded-2xl p-4 sm:p-5 shadow-2xl flex flex-col justify-between">

                <div class="space-y-3.5">
                    <!-- 上部ヘッダー：アクションボタン -->
                    <div class="flex items-center justify-between border-b border-gray-800 pb-2.5">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-comments text-amber-500 text-base"></i>
                            <h2 class="text-base font-extrabold text-white">みんなのレビュー</h2>
                            @if(isset($reviews))
                                <span
                                    class="text-xs text-gray-400">（新着{{ is_array($reviews) || $reviews instanceof \Countable ? count($reviews) : 0 }}件）</span>
                            @endif
                        </div>

                        <!-- レビューを書くボタン -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('reviews.create', $movieId) }}"
                                class="text-xs font-bold text-black bg-amber-500 hover:bg-amber-400 px-3 py-1.5 rounded-lg transition shadow-md flex items-center gap-1">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>レビューを書く</span>
                            </a>
                        </div>
                    </div>

                    <!-- 🔖 Watchlist Toggle Button (Alpine.js) -->
                    <div x-data="{ 
                        inWatchlist: {{ Auth::user()->watchlists()->where('movie_id', $movieId)->exists() ? 'true' : 'false' }},
                        loading: false,
                        async toggleWatchlist() {
                            if (this.loading) return;
                            this.loading = true;
                            
                            try {
                                const response = await fetch('{{ route('watchlist.toggle') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        movie_id: '{{ $movieId }}',
                                        title: '{{ addslashes($movieTitle) }}',
                                        poster_path: '{{ $posterPath ?? '' }}'
                                    })
                                });
                                const data = await response.json();
                                if (data.status === 'added') {
                                    this.inWatchlist = true;
                                } else if (data.status === 'removed') {
                                    this.inWatchlist = false;
                                }
                            } catch (error) {
                                console.error('Error toggling watchlist:', error);
                            } finally {
                                this.loading = false;
                            }
                        }
                    }">
                        <button @click="toggleWatchlist()" :disabled="loading"
                            :class="inWatchlist ? 'bg-amber-500 text-black hover:bg-amber-400' : 'bg-gray-800 text-white hover:bg-gray-700 border border-gray-700'"
                            class="w-full flex items-center justify-center space-x-2 px-4 py-2 rounded-xl font-bold text-xs transition duration-200 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                :fill="inWatchlist ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            <span x-text="inWatchlist ? 'ウォッチリストから外す' : 'ウォッチリストに追加'"></span>
                        </button>
                    </div>

                    <!-- レビューリスト（高さ固定・スクロール可能） -->
                    <div class="space-y-2.5 max-h-[280px] overflow-y-auto pr-1 custom-scrollbar">
                        @if(isset($reviews) && (is_array($reviews) || $reviews instanceof \Countable) && count($reviews) > 0)
                            @foreach($reviews as $review)
                                @php
                                    $revUserName = is_array($review) ? ($review['user_name'] ?? $review['user']['name'] ?? 'ユーザー') : ($review->user_name ?? $review->user->name ?? 'ユーザー');
                                    $revRating = is_array($review) ? ($review['rating'] ?? 0) : ($review->rating ?? 0);
                                    $revComment = is_array($review) ? ($review['comment'] ?? '') : ($review->comment ?? '');
                                    $revMoods = is_array($review) ? ($review['moods'] ?? []) : ($review->moods ?? []);
                                    if (is_string($revMoods)) {
                                        $revMoods = json_decode($revMoods, true) ?? [$revMoods];
                                    }
                                @endphp
                                <div class="bg-black/40 border border-gray-800 p-3 rounded-xl space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold text-gray-200">{{ $revUserName }}</span>
                                        <div class="flex items-center gap-1 text-amber-400 font-bold">
                                            <i class="fa-solid fa-star text-[10px]"></i>
                                            <span>{{ number_format((float) $revRating, 1) }}</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-300 leading-relaxed">
                                        {{ $revComment }}
                                    </p>
                                    @if(!empty($revMoods))
                                        <div class="flex flex-wrap gap-1 pt-0.5">
                                            @foreach((array) $revMoods as $mood)
                                                <span
                                                    class="text-[10px] bg-amber-500/20 text-amber-400 border border-amber-500/30 px-2 py-0.5 rounded-full font-semibold">
                                                    {{ $mood }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-500 text-xs">
                                まだレビューがありません。最初のレビューを投稿してみよう！
                            </div>
                        @endif
                    </div>
                </div>

                <!-- フッター：すべてのレビューを見る リンク -->
                <div class="pt-2.5 border-t border-gray-800/80 flex items-center justify-end mt-3">
                    <a href="#"
                        class="text-xs font-bold text-gray-400 hover:text-amber-400 transition flex items-center gap-1">
                        <span>すべてのレビューを見る</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>