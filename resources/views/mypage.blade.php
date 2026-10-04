<x-app-layout :popularMovies="$popularMovies ?? []">
    <div class="max-w-6xl mx-auto space-y-6">

        <!-- 👤 プロフィールヘッダー -->
        <div
            class="bg-gray-900/90 rounded-2xl p-5 sm:p-6 border border-gray-800 shadow-xl flex items-center justify-between gap-4">
            <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                <div
                    class="relative w-14 h-14 sm:w-20 sm:h-20 rounded-full border-2 border-amber-500 overflow-hidden bg-gray-800 flex items-center justify-center shadow-md flex-shrink-0">
                    @php
                        $user = auth()->user();
                        $avatarPath = $user->avatar ?? $user->icon ?? $user->icon_path ?? null;
                    @endphp

                    @if($avatarPath)
                        <img src="{{ str_starts_with($avatarPath, 'http') ? $avatarPath : asset('storage/' . $avatarPath) }}"
                            class="w-full h-full object-cover"
                            onError="this.onerror=null; this.src='{{ asset($avatarPath) }}';">
                    @else
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    @endif
                </div>

                <div class="space-y-0.5 sm:space-y-1 min-w-0">
                    <span class="text-[10px] sm:text-xs text-amber-500 font-bold tracking-wider uppercase block">MY
                        PROFILE</span>
                    <h1 class="text-base sm:text-2xl font-extrabold text-white truncate">
                        {{ auth()->user()->nickname ?? auth()->user()->name ?? 'ユーザー' }}
                    </h1>
                    <p class="text-[11px] sm:text-xs text-gray-400 font-medium truncate">
                        {{ auth()->user()->email }}
                    </p>
                </div>
            </div>

            <div class="flex-shrink-0">
                <a href="{{ route('profile.edit') }}"
                    class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-2 sm:py-2.5 bg-gray-800 hover:bg-amber-500 hover:text-black text-amber-400 font-bold rounded-full text-xs transition border border-gray-700 shadow-md whitespace-nowrap">
                    <i class="fa-solid fa-gear"></i>
                    <span class="hidden sm:inline">アカウントを編集</span>
                    <span class="sm:hidden">編集</span>
                </a>
            </div>
        </div>

        <!-- 📑 マイページコンテンツ（WATCHLIST / MY REVIEWS / LIKES） -->
        <div class="bg-gray-900/90 rounded-2xl border border-gray-800 shadow-xl overflow-hidden">

            <!-- タブ切替 -->
            <div class="flex border-b border-gray-800 text-xs font-bold text-center bg-black/40">
                <button type="button" id="tab-btn-watchlist" onclick="switchProfileTab('watchlist')"
                    class="tab-btn flex-1 py-4 px-2 transition flex items-center justify-center gap-2 text-amber-500 border-b-2 border-amber-500 bg-gray-900/50">
                    <span>📌</span>
                    <span>WATCHLIST</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-800 text-gray-300">
                        {{ is_array($watchlist ?? null) || ($watchlist ?? null) instanceof \Countable ? count($watchlist) : 0 }}
                    </span>
                </button>

                <button type="button" id="tab-btn-reviews" onclick="switchProfileTab('reviews')"
                    class="tab-btn flex-1 py-4 px-2 transition flex items-center justify-center gap-2 text-gray-400 hover:text-gray-200">
                    <span>✍</span>
                    <span>MY REVIEWS</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-800 text-gray-300">
                        {{ is_array($myReviews ?? null) || ($myReviews ?? null) instanceof \Countable ? count($myReviews) : 0 }}
                    </span>
                </button>

                <button type="button" id="tab-btn-likes" onclick="switchProfileTab('likes')"
                    class="tab-btn flex-1 py-4 px-2 transition flex items-center justify-center gap-2 text-gray-400 hover:text-gray-200">
                    <span>💖</span>
                    <span>LIKES</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-800 text-gray-300">
                        {{ count($likedReviews ?? []) }}
                    </span>
                </button>
            </div>

            <!-- 1️⃣ タブ：ウォッチリスト表示 -->
            <div id="tab-content-watchlist" class="tab-panel p-4 sm:p-6">
                @if(!empty($watchlist) && count($watchlist) > 0)
                    <!-- パソコン画面で最大6列（6件）並ぶように設定する例 -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                        @foreach($watchlist as $item)
                            @php
                                $data = is_array($item) ? $item : $item->toArray();
                                $movieId = $data['movie_id'] ?? $data['tmdb_id'] ?? $data['id'] ?? '';
                                $title = $data['title'] ?? $data['movie_title'] ?? $data['name'] ?? '無題';
                                $posterPath = $data['poster_path'] ?? $data['poster'] ?? '';
                                $releaseDate = $data['release_date'] ?? $data['year'] ?? null;
                                $year = $releaseDate ? substr($releaseDate, 0, 4) : '';

                                // 評価値の取得（vote_average, rating, score 等）
                                $rawRating = $data['vote_average'] ?? $data['rating'] ?? $data['score'] ?? null;
                                $rating = (is_numeric($rawRating) && (float) $rawRating > 0) ? number_format((float) $rawRating, 1) : '-';
                            @endphp

                            <a href="{{ route('movies.show', $movieId) }}"
                                class="group relative block rounded-2xl overflow-hidden bg-slate-900/80 border border-gray-800 hover:border-amber-500/50 transition duration-300 shadow-md hover:shadow-xl hover:-translate-y-1">

                                <!-- ポスター画像エリア -->
                                <div class="relative w-full overflow-hidden bg-gray-950" style="aspect-ratio: 2 / 3;">
                                    <img src="{{ !empty($posterPath) ? (str_starts_with($posterPath, 'http') ? $posterPath : 'https://image.tmdb.org/t/p/w500' . $posterPath) : asset('images/no-poster.png') }}"
                                        alt="{{ $title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </div>

                                <!-- 下部情報エリア（タイトル、公開年、★評価） -->
                                <div class="p-3 bg-slate-900/90 space-y-1">
                                    <p class="text-xs font-bold text-white group-hover:text-amber-400 truncate">
                                        {{ $title }}
                                    </p>
                                    <div class="flex items-center justify-between text-[11px] text-gray-400 font-medium">
                                        <span>{{ $year ? $year . '年' : '' }}</span>
                                        <span class="text-amber-400 font-bold">★ {{ $rating }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center space-y-2">
                        <div class="text-3xl text-gray-600">📌</div>
                        <p class="text-xs text-gray-400 font-bold">ウォッチリストに登録された映画はありません。</p>
                    </div>
                @endif
            </div>

            <!-- 2️⃣ タブ：マイレビュー -->
            <div id="tab-content-reviews" class="tab-panel p-6 hidden">
                @if(!empty($myReviews) && count($myReviews) > 0)
                    <div class="space-y-4">
                        @foreach($myReviews as $review)
                            <div
                                class="p-4 bg-black/40 rounded-xl border border-gray-800 space-y-2 transition hover:border-gray-700">
                                <div class="flex items-center justify-between border-b border-gray-800/80 pb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-amber-500 font-bold text-xs">🎬</span>
                                        <a href="{{ route('movies.show', $review->movie_id ?? $review->tmdb_id ?? 1) }}"
                                            class="text-xs font-bold text-gray-200 hover:text-amber-400 transition">
                                            {{ $review->movie_title ?? '対象の映画' }}
                                        </a>
                                    </div>
                                    <span class="text-xs text-amber-400 font-bold">⭐
                                        {{ number_format($review->rating ?? 0, 1) }}</span>
                                </div>
                                <p class="text-xs text-gray-300 leading-relaxed pt-1">
                                    {{ $review->comment ?? $review->body }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center space-y-2">
                        <div class="text-3xl text-gray-600">✍️</div>
                        <p class="text-xs text-gray-400 font-bold">まだ投稿したレビューはありません。</p>
                    </div>
                @endif
            </div>

            <!-- 3️⃣ タブ：いいね -->
            <div id="tab-content-likes" class="tab-panel p-6 hidden">
                @if(!empty($likedReviews) && count($likedReviews) > 0)
                    <div class="space-y-4">
                        @foreach($likedReviews as $item)
                            <div
                                class="p-4 bg-black/40 rounded-xl border border-gray-800 space-y-2 transition hover:border-gray-700">
                                <div class="flex items-center justify-between border-b border-gray-800/80 pb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-rose-500 font-bold text-xs">💖</span>
                                        <a href="{{ route('movies.show', $item->movie_id ?? $item->tmdb_id ?? 1) }}"
                                            class="text-xs font-bold text-gray-200 hover:text-amber-400 transition">
                                            {{ $item->movie_title ?? '映画' }}
                                        </a>
                                    </div>
                                    <span class="text-xs text-amber-400 font-bold">⭐
                                        {{ number_format($item->rating ?? 0, 1) }}</span>
                                </div>
                                <p class="text-xs text-gray-300 leading-relaxed pt-1">
                                    {{ $item->comment ?? $item->body }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center space-y-2">
                        <div class="text-3xl text-gray-600">💖</div>
                        <p class="text-xs text-gray-400 font-bold">いいねした投稿はありません。</p>
                    </div>
                @endif
            </div>

        </div>

    </div>

    <script>
        function switchProfileTab(tabName) {
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.add('hidden');
            });

            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-amber-500', 'border-b-2', 'border-amber-500', 'bg-gray-900/50');
                btn.classList.add('text-gray-400');
            });

            const targetPanel = document.getElementById('tab-content-' + tabName);
            if (targetPanel) {
                targetPanel.classList.remove('hidden');
            }

            const targetBtn = document.getElementById('tab-btn-' + tabName);
            if (targetBtn) {
                targetBtn.classList.remove('text-gray-400');
                targetBtn.classList.add('text-amber-500', 'border-b-2', 'border-amber-500', 'bg-gray-900/50');
            }
        }
    </script>
</x-app-layout>