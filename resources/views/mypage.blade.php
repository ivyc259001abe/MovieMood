
<x-app-layout :popularMovies="$popularMovies ?? []">

    <style>
        html {
            scroll-behavior: smooth;
        }

        @keyframes profile-glow-highlight {
            0% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.9);
                border-color: rgba(245, 158, 11, 1);
            }

            50% {
                box-shadow: 0 0 24px 5px rgba(245, 158, 11, 0.45);
                border-color: rgba(251, 191, 36, 1);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
                border-color: rgba(55, 65, 81, 1);
            }
        }

        .profile-glow-highlight {
            animation: profile-glow-highlight 2.5s ease-in-out;
        }
    </style>

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- プロフィールヘッダー --}}
        <div class="bg-gray-900/90 rounded-2xl p-5 sm:p-6 border border-gray-800 shadow-xl flex items-center justify-between gap-4">

            <div class="flex items-center gap-4 sm:gap-5 min-w-0">

                <div class="relative w-14 h-14 sm:w-20 sm:h-20 rounded-full border-2 border-amber-500 overflow-hidden bg-gray-800 flex items-center justify-center shadow-md flex-shrink-0">

                    @php
                        $user = auth()->user();
                        $avatarPath = $user->avatar ?? $user->icon ?? $user->icon_path ?? null;
                    @endphp

                    @if($avatarPath)
                        <img
                            src="{{ str_starts_with($avatarPath, 'http') ? $avatarPath : asset('storage/' . $avatarPath) }}"
                            alt="プロフィール画像"
                            class="w-full h-full object-cover"
                            onerror="this.onerror=null; this.src='{{ asset($avatarPath) }}';">
                    @else
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    @endif

                </div>

                <div class="space-y-0.5 sm:space-y-1 min-w-0">
                    <span class="text-[10px] sm:text-xs text-amber-500 font-bold tracking-wider uppercase block">
                        MY PROFILE
                    </span>

                    <h1 class="text-base sm:text-2xl font-extrabold text-white truncate">
                        {{ $user->nickname ?? $user->name ?? 'ユーザー' }}
                    </h1>

                    <p class="text-[11px] sm:text-xs text-gray-400 font-medium truncate">
                        {{ $user->email }}
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


        {{-- マイページコンテンツ --}}
        <div class="bg-gray-900/90 rounded-2xl border border-gray-800 shadow-xl overflow-hidden">

            {{-- タブ切り替え --}}
            <div class="flex border-b border-gray-800 text-xs font-bold text-center bg-black/40">

                {{-- WATCHLIST --}}
                <button
                    type="button"
                    id="tab-btn-watchlist"
                    onclick="switchProfileTab('watchlist')"
                    class="tab-btn flex-1 py-4 px-2 transition flex items-center justify-center gap-2 text-amber-500 border-b-2 border-amber-500 bg-gray-900/50">

                    <span>📌</span>
                    <span>WATCHLIST</span>

                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-800 text-gray-300">
                        {{ $watchlist instanceof \Illuminate\Pagination\LengthAwarePaginator ? $watchlist->total() : (is_countable($watchlist ?? null) ? count($watchlist) : 0) }}
                    </span>

                </button>

                {{-- MY REVIEWS --}}
                <button
                    type="button"
                    id="tab-btn-reviews"
                    onclick="switchProfileTab('reviews')"
                    class="tab-btn flex-1 py-4 px-2 transition flex items-center justify-center gap-2 text-gray-400 hover:text-gray-200">

                    <span>✍</span>
                    <span>MY REVIEWS</span>

                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-800 text-gray-300">
                        {{ is_countable($myReviews ?? null) ? count($myReviews) : 0 }}
                    </span>

                </button>

                {{-- LIKES --}}
                <button
                    type="button"
                    id="tab-btn-likes"
                    onclick="switchProfileTab('likes')"
                    class="tab-btn flex-1 py-4 px-2 transition flex items-center justify-center gap-2 text-gray-400 hover:text-gray-200">

                    <span>💖</span>
                    <span>LIKES</span>

                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-800 text-gray-300">
                        {{ is_countable($likedReviews ?? null) ? count($likedReviews) : 0 }}
                    </span>

                </button>

            </div>


            {{-- 1. WATCHLIST --}}
            <div id="tab-content-watchlist" class="tab-panel p-4 sm:p-6">

                @if(!empty($watchlist) && count($watchlist) > 0)

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">

                        @foreach($watchlist as $item)

                            @php
                                $data = is_array($item) ? $item : $item->toArray();

                                $movieId = $data['movie_id']
                                    ?? $data['tmdb_id']
                                    ?? $data['id']
                                    ?? null;

                                $title = $data['title']
                                    ?? $data['movie_title']
                                    ?? $data['name']
                                    ?? '無題';

                                $posterPath = $data['poster_path']
                                    ?? $data['poster']
                                    ?? '';

                                $releaseDate = $data['release_date']
                                    ?? $data['year']
                                    ?? null;

                                $year = $releaseDate
                                    ? substr((string) $releaseDate, 0, 4)
                                    : '';

                                $rawRating = $data['vote_average']
                                    ?? $data['rating']
                                    ?? $data['score']
                                    ?? null;

                                $rating = (
                                    is_numeric($rawRating)
                                    && (float) $rawRating > 0
                                )
                                    ? number_format((float) $rawRating, 1)
                                    : '-';
                            @endphp

                            @if($movieId)
                                <a
                                    href="{{ route('movies.show', $movieId) }}"
                                    class="block group overflow-hidden rounded-xl border border-gray-800 hover:border-amber-500/50 transition">

                                    <div class="relative w-full overflow-hidden bg-gray-950" style="aspect-ratio: 2 / 3;">

                                        <img
                                            src="{{ !empty($posterPath) ? (str_starts_with($posterPath, 'http') ? $posterPath : 'https://image.tmdb.org/t/p/w500' . $posterPath) : asset('images/no-poster.png') }}"
                                            alt="{{ $title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">

                                    </div>

                                    <div class="p-3 bg-slate-900/90 space-y-1">

                                        <p class="text-xs font-bold text-white group-hover:text-amber-400 truncate">
                                            {{ $title }}
                                        </p>

                                        <div class="flex items-center justify-between text-[11px] text-gray-400 font-medium">
                                            <span>{{ $year ? $year . '年' : '' }}</span>

                                            <span class="text-amber-400 font-bold">
                                                ★ {{ $rating }}
                                            </span>
                                        </div>

                                    </div>

                                </a>
                            @endif

                        @endforeach

                    </div>


                    {{-- ウォッチリストのページネーション --}}
                    @if($watchlist instanceof \Illuminate\Pagination\LengthAwarePaginator && $watchlist->hasPages())

                        <div class="mt-6 flex items-center justify-center gap-1.5 text-xs">

                            @if($watchlist->onFirstPage())
                                <span class="px-2 py-1 rounded bg-gray-800/60 text-gray-600 cursor-not-allowed">
                                    ‹
                                </span>
                            @else
                                <a href="{{ $watchlist->previousPageUrl() }}"
                                    class="px-2 py-1 rounded bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition">
                                    ‹
                                </a>
                            @endif

                            @foreach($watchlist->getUrlRange(1, $watchlist->lastPage()) as $page => $url)

                                @if($page == $watchlist->currentPage())
                                    <span class="px-2.5 py-1 rounded bg-amber-500 text-gray-950 font-bold">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-2.5 py-1 rounded bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition">
                                        {{ $page }}
                                    </a>
                                @endif

                            @endforeach

                            @if($watchlist->hasMorePages())
                                <a href="{{ $watchlist->nextPageUrl() }}"
                                    class="px-2 py-1 rounded bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition">
                                    ›
                                </a>
                            @else
                                <span class="px-2 py-1 rounded bg-gray-800/60 text-gray-600 cursor-not-allowed">
                                    ›
                                </span>
                            @endif

                        </div>

                    @endif

                @else

                    <div class="py-12 text-center space-y-2">
                        <div class="text-3xl text-gray-600">📌</div>

                        <p class="text-xs text-gray-400 font-bold">
                            ウォッチリストに登録された映画はありません。
                        </p>
                    </div>

                @endif

            </div>


            {{-- 2. MY REVIEWS --}}
            <div id="tab-content-reviews" class="tab-panel p-4 sm:p-6 hidden">

                @if(!empty($myReviews) && count($myReviews) > 0)

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        @foreach($myReviews as $review)

                            @php
                                $reviewMovieId = $review->movie_id
                                    ?? $review->tmdb_id
                                    ?? $review->movie->tmdb_id
                                    ?? $review->movie->id
                                    ?? null;

                                $reviewTitle = $review->movie_title
                                    ?? $review->movie->title
                                    ?? '対象の映画';

                                $reviewText = $review->comment
                                    ?? $review->body
                                    ?? '';

                                $reviewRating = $review->rating ?? 0;
                            @endphp

                            <div
                                class="profile-item p-4 bg-black/40 rounded-xl border border-gray-800 space-y-2 transition hover:border-gray-700">

                                <div class="flex items-center justify-between border-b border-gray-800/80 pb-2 gap-3">

                                    <div class="flex items-center gap-2 min-w-0">

                                        <span class="text-amber-500 font-bold text-xs flex-shrink-0">🎬</span>

                                        @if($reviewMovieId)
                                            <a
                                                href="{{ route('movies.show', $reviewMovieId) }}#review-{{ $review->id }}"
                                                class="text-xs font-bold text-gray-200 hover:text-amber-400 transition truncate">

                                                {{ $reviewTitle }}

                                            </a>
                                        @else
                                            <span class="text-xs font-bold text-gray-200 truncate">
                                                {{ $reviewTitle }}
                                            </span>
                                        @endif

                                    </div>

                                    <span class="text-xs text-amber-400 font-bold flex-shrink-0">
                                        ⭐ {{ number_format((float) $reviewRating, 1) }}
                                    </span>

                                </div>

                                <p class="text-xs text-gray-300 leading-relaxed pt-1 whitespace-pre-line">
                                    {{ $reviewText }}
                                </p>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="py-12 text-center space-y-2">
                        <div class="text-3xl text-gray-600">✍️</div>

                        <p class="text-xs text-gray-400 font-bold">
                            まだ投稿したレビューはありません。
                        </p>
                    </div>

                @endif

            </div>


            {{-- 3. LIKES --}}
            <div id="tab-content-likes" class="tab-panel p-4 sm:p-6 hidden">

                @if(!empty($likedReviews) && count($likedReviews) > 0)

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        @foreach($likedReviews as $item)

                            @php
                                /*
                                 * $item がレビュー本体の場合と、
                                 * いいねレコードで review リレーションを持つ場合の両方に対応。
                                 */
                                $likedReview = $item->review ?? $item;

                                $likedReviewId = $likedReview->id
                                    ?? $item->review_id
                                    ?? null;

                                $likedMovieId = $likedReview->movie_id
                                    ?? $likedReview->tmdb_id
                                    ?? $likedReview->movie->tmdb_id
                                    ?? $likedReview->movie->id
                                    ?? $item->movie_id
                                    ?? $item->tmdb_id
                                    ?? null;

                                $likedTitle = $likedReview->movie_title
                                    ?? $likedReview->movie->title
                                    ?? $item->movie_title
                                    ?? '映画';

                                $likedText = $likedReview->comment
                                    ?? $likedReview->body
                                    ?? $item->comment
                                    ?? $item->body
                                    ?? '';

                                $likedRating = $likedReview->rating
                                    ?? $item->rating
                                    ?? 0;
                            @endphp

                            <div
                                class="profile-item p-4 bg-black/40 rounded-xl border border-gray-800 space-y-2 transition hover:border-gray-700">

                                <div class="flex items-center justify-between border-b border-gray-800/80 pb-2 gap-3">

                                    <div class="flex items-center gap-2 min-w-0">

                                        <span class="text-rose-500 font-bold text-xs flex-shrink-0">💖</span>

                                        @if($likedMovieId && $likedReviewId)
                                            <a
                                                href="{{ route('movies.show', $likedMovieId) }}#review-{{ $likedReviewId }}"
                                                class="text-xs font-bold text-gray-200 hover:text-amber-400 transition truncate">

                                                {{ $likedTitle }}

                                            </a>
                                        @elseif($likedMovieId)
                                            <a
                                                href="{{ route('movies.show', $likedMovieId) }}"
                                                class="text-xs font-bold text-gray-200 hover:text-amber-400 transition truncate">

                                                {{ $likedTitle }}

                                            </a>
                                        @else
                                            <span class="text-xs font-bold text-gray-200 truncate">
                                                {{ $likedTitle }}
                                            </span>
                                        @endif

                                    </div>

                                    <span class="text-xs text-amber-400 font-bold flex-shrink-0">
                                        ⭐ {{ number_format((float) $likedRating, 1) }}
                                    </span>

                                </div>

                                <p class="text-xs text-gray-300 leading-relaxed pt-1 whitespace-pre-line">
                                    {{ $likedText }}
                                </p>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="py-12 text-center space-y-2">
                        <div class="text-3xl text-gray-600">💖</div>

                        <p class="text-xs text-gray-400 font-bold">
                            いいねした投稿はありません。
                        </p>
                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- タブ切り替え・カードのハイライト --}}
    <script>
        function switchProfileTab(tabName) {
            // すべてのパネルを非表示にする
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.add('hidden');
            });

            // ボタンの選択状態をリセットする
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove(
                    'text-amber-500',
                    'border-b-2',
                    'border-amber-500',
                    'bg-gray-900/50'
                );

                btn.classList.add('text-gray-400');
            });

            // 選択されたパネルを表示する
            const targetPanel = document.getElementById(
                'tab-content-' + tabName
            );

            if (!targetPanel) {
                return;
            }

            targetPanel.classList.remove('hidden');

            // 選択されたボタンを強調する
            const targetBtn = document.getElementById(
                'tab-btn-' + tabName
            );

            if (targetBtn) {
                targetBtn.classList.remove('text-gray-400');

                targetBtn.classList.add(
                    'text-amber-500',
                    'border-b-2',
                    'border-amber-500',
                    'bg-gray-900/50'
                );
            }

            // URLに現在のタブを反映する
            const hash = '#tab-' + tabName;

            if (window.location.hash !== hash) {
                history.replaceState(
                    null,
                    '',
                    window.location.pathname +
                    window.location.search +
                    hash
                );
            }

            // MY REVIEWS・LIKESのカードを金色に光らせる
            if (tabName === 'reviews' || tabName === 'likes') {
                const items = targetPanel.querySelectorAll('.profile-item');

                items.forEach(item => {
                    item.classList.remove('profile-glow-highlight');
                });

                requestAnimationFrame(() => {
                    items.forEach(item => {
                        void item.offsetWidth;
                        item.classList.add('profile-glow-highlight');
                    });
                });
            }

            // 選択したタブが見える位置へ移動
            if (targetBtn) {
                targetBtn.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }
        }

        // URLのハッシュに応じて初期タブを設定する
        document.addEventListener('DOMContentLoaded', function () {
            const hash = window.location.hash;

            if (hash === '#tab-reviews') {
                switchProfileTab('reviews');
            } else if (hash === '#tab-likes') {
                switchProfileTab('likes');
            } else {
                switchProfileTab('watchlist');
            }
        });
    </script>

</x-app-layout>