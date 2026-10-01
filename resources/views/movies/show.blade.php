<x-app-layout>
    <style>
        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #f59e0b;
            cursor: pointer;
            box-shadow: 0 0 8px rgba(245, 158, 11, 0.6);
            transition: transform 0.1s ease;
        }

        input[type="range"]::-webkit-slider-thumb:hover {
            transform: scale(1.15);
        }

        input[type="range"]::-moz-range-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #f59e0b;
            cursor: pointer;
            box-shadow: 0 0 8px rgba(245, 158, 11, 0.6);
            border: none;
            transition: transform 0.1s ease;
        }

        input[type="range"]::-moz-range-thumb:hover {
            transform: scale(1.15);
        }

        /* 💡 通知からジャンプしてきた時に該当レビューをハイライト表示 */
        :target {
            animation: highlight 2.5s ease-in-out;
        }

        @keyframes highlight {
            0% {
                background-color: rgba(245, 158, 11, 0.25);
                border-color: rgba(245, 158, 11, 0.8);
            }

            100% {
                background-color: #121824;
            }
        }
    </style>

    <div class="min-h-screen bg-[#0b0f17] text-gray-100 py-3 px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-7xl mx-auto space-y-6 w-full">

            <!-- 1. 上段：映画情報 ＆ レビュー投稿フォーム -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch w-full">

                <!-- 👈 左：映画情報 ＆ あらすじ -->
                <div
                    class="bg-[#121824] border border-gray-800 rounded-2xl p-5 shadow-xl w-full flex flex-col sm:flex-row gap-5 items-stretch">

                    <!-- 左列：ポスター画像 ＋ 💡ポスター下活用コンテンツ -->
                    <div class="w-32 sm:w-44 flex-shrink-0 flex flex-col justify-between space-y-3">

                        <!-- ポスター画像 -->
                        <div class="w-full rounded-xl overflow-hidden shadow-lg border border-gray-700/50 aspect-[2/3]">
                            <img src="{{ !empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : asset('images/no-poster.png') }}"
                                alt="{{ $movie['title'] }}" class="w-full h-full object-cover">
                        </div>

                        <!-- 🌟【追加機能】ポスター下の隙間活用エリア -->
                        <div class="space-y-2 flex-1 flex flex-col justify-end pt-1">

                            <!-- 1. 配信/鑑賞情報ミニカード -->
                            <div class="bg-[#1a2332] border border-gray-800 rounded-xl p-2.5 text-center">
                                <div
                                    class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1 flex items-center justify-center gap-1">
                                    <i class="fa-solid fa-tv text-amber-400"></i> どこで見れる？
                                </div>
                                <div class="text-[11px] font-semibold text-gray-200">
                                    🎬 劇場公開作品 / 配信準備中
                                </div>
                            </div>

                            <!-- 2. シェア＆外部リンクボタン -->
                            <div class="grid grid-cols-2 gap-1.5">
                                <a href="https://twitter.com/intent/tweet?text={{ urlencode('『' . $movie['title'] . '』をチェック！ #MovieMood') }}&url={{ urlencode(request()->fullUrl()) }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-300 hover:text-white rounded-lg py-1.5 px-2 text-[10px] font-bold transition flex items-center justify-center gap-1">
                                    <i class="fa-brands fa-x-twitter"></i> シェア
                                </a>

                                <a href="https://www.themoviedb.org/movie/{{ $movie['id'] }}" target="_blank"
                                    rel="noopener noreferrer"
                                    class="bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-300 hover:text-white rounded-lg py-1.5 px-2 text-[10px] font-bold transition flex items-center justify-center gap-1">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> TMDB
                                </a>
                            </div>

                        </div>

                    </div>

                    <!-- 右列：タイトル + メタ情報 + あらすじ -->
                    <div class="flex-1 min-w-0 flex flex-col justify-between">

                        <!-- 上部：タイトル ＆ メタ情報 -->
                        <div class="space-y-2 mb-3">
                            <div class="flex items-start justify-between gap-2">
                                <h1 class="text-lg sm:text-xl font-extrabold text-white tracking-tight leading-snug">
                                    {{ $movie['title'] }}
                                </h1>

                                <!-- 📌 「みたい！」ボタン -->
                                <form action="{{ route('watchlist.toggle') }}" method="POST" class="flex-shrink-0">
                                    @csrf
                                    <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
                                    <input type="hidden" name="title" value="{{ $movie['title'] }}">
                                    <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] ?? '' }}">

                                    @php
                                        $inWatchlist = false;
                                        if (auth()->check()) {
                                            $user = auth()->user();
                                            if (method_exists($user, 'watchlists')) {
                                                $inWatchlist = $user->watchlists()->where('movie_id', $movie['id'])->exists();
                                            } elseif (method_exists($user, 'watchlist')) {
                                                $inWatchlist = $user->watchlist()->where('movie_id', $movie['id'])->exists();
                                            }
                                        }
                                    @endphp

                                    <button type="submit"
                                        class="py-1 px-3 rounded-full text-xs font-bold transition duration-200 flex items-center gap-1.5 border shadow-sm {{ $inWatchlist ? 'bg-amber-500 text-black border-amber-400 hover:bg-amber-400' : 'bg-gray-800 text-amber-400 border-amber-500/40 hover:bg-amber-500/20' }}">
                                        <i class="fa-{{ $inWatchlist ? 'solid' : 'regular' }} fa-bookmark"></i>
                                        <span>{{ $inWatchlist ? '登録中' : '＋ みたい！' }}</span>
                                    </button>
                                </form>
                            </div>

                            <div class="flex flex-wrap items-center gap-1.5">
                                <span
                                    class="bg-amber-500/10 border border-amber-500/30 text-amber-400 px-2.5 py-0.5 rounded-full text-xs font-extrabold flex items-center gap-1">
                                    <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                                    TMDB: {{ number_format($movie['vote_average'] ?? 0, 1) }}
                                </span>
                                <span
                                    class="bg-gray-800 border border-gray-700 text-gray-300 text-xs px-2.5 py-0.5 rounded-full">
                                    📅 {{ $movie['release_date'] ?? '未定' }}
                                </span>
                                @if(!empty($movie['runtime']))
                                    <span
                                        class="bg-gray-800 border border-gray-700 text-gray-300 text-xs px-2.5 py-0.5 rounded-full">
                                        ⏱️ {{ $movie['runtime'] }}分
                                    </span>
                                @endif
                            </div>

                            @if(!empty($movie['director']))
                                <div class="text-xs text-gray-400">
                                    🎬 監督: <span class="text-gray-200 font-semibold">{{ $movie['director'] }}</span>
                                </div>
                            @endif

                            @if(!empty($movie['genres']) && is_array($movie['genres']))
                                <div class="flex flex-wrap gap-1 pt-0.5">
                                    @foreach($movie['genres'] as $genre)
                                        <span
                                            class="text-[10px] bg-gray-800/90 text-gray-400 px-2 py-0.5 rounded border border-gray-700/60">
                                            {{ is_array($genre) ? ($genre['name'] ?? '') : $genre }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- 下部：📖 STORY / あらすじ -->
                        <div class="pt-2 border-t border-gray-800/80 flex-1 flex flex-col min-h-0">
                            <h3
                                class="text-xs font-bold text-amber-400 uppercase tracking-wider flex items-center gap-1.5 mb-1.5">
                                <i class="fa-solid fa-book-open"></i> STORY / あらすじ
                            </h3>
                            <div class="bg-[#1a2332] p-3.5 rounded-xl border border-gray-800/80 flex-1 overflow-y-auto">
                                <p class="text-xs sm:text-sm text-gray-200 leading-relaxed font-normal">
                                    {{ (!empty($movie['overview']) && trim($movie['overview']) !== '') ? $movie['overview'] : '※日本語あらすじ情報は準備中です。' }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- 👉 右：レビュー投稿フォーム -->
                <div
                    class="bg-[#121824] border border-gray-800 rounded-2xl p-5 shadow-xl w-full flex flex-col justify-between">
                    <h2
                        class="text-sm sm:text-base font-bold text-white flex items-center gap-2 border-b border-gray-800 pb-2 mb-3">
                        <i class="fa-solid fa-pen-to-square text-amber-500"></i>
                        この映画のレビューを投稿する
                    </h2>

                    <form action="{{ route('reviews.store', $movie['id']) }}" method="POST"
                        class="space-y-3 flex-1 flex flex-col justify-between">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
                        <input type="hidden" name="movie_title" value="{{ $movie['title'] }}">
                        <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] ?? '' }}">

                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <label for="ratingSlider"
                                    class="block text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    評価（1.0 〜 10.0）
                                </label>
                                <div class="text-amber-400 font-extrabold text-base flex items-center gap-1">
                                    <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                                    <span id="ratingValue">8.0</span>
                                </div>
                            </div>

                            <input type="range" name="rating" id="ratingSlider" min="1.0" max="10.0" step="0.1"
                                value="8.0"
                                class="w-full h-1.5 rounded-lg appearance-none cursor-pointer focus:outline-none transition-all duration-75"
                                oninput="updateRatingDisplay(this)">

                            <div class="bg-[#1a2332] border border-gray-800/80 rounded-lg px-2.5 py-1 text-center">
                                <span id="ratingLabel"
                                    class="text-xs font-bold text-amber-400 transition-colors duration-150">
                                    ✨ 超おすすめ！観て後悔なし
                                </span>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">
                                観たあとの気分タグ（複数選択可）
                            </span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(['号泣', 'スカッと', 'ハラハラ', 'キュン'] as $m)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="moods[]" value="{{ $m }}" class="peer hidden">
                                        <span
                                            class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-[#1a2332] border border-gray-700 text-gray-300 peer-checked:bg-amber-500 peer-checked:text-black peer-checked:border-amber-400 transition hover:border-gray-500">
                                            #{{ $m }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label for="comment" class="block text-xs font-bold text-gray-400 uppercase tracking-wider">
                                レビュー感想コメント
                            </label>
                            <textarea id="comment" name="comment" rows="2" required
                                placeholder="この映画を観た感想や見どころを書いてみましょう..."
                                class="w-full bg-[#1a2332] border border-gray-700 rounded-xl p-2.5 text-xs sm:text-sm text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none leading-relaxed resize-none h-20"></textarea>
                        </div>

                        <div class="pt-1">
                            <button type="submit"
                                class="w-full bg-amber-500 hover:bg-amber-400 text-black font-extrabold px-5 py-2.5 rounded-xl text-xs sm:text-sm transition shadow-lg flex items-center justify-center gap-2">
                                <i class="fa-solid fa-paper-plane"></i>
                                レビューを投稿する
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- 👇 スクロール案内 -->
            <div class="flex justify-center pt-2">
                <a href="#reviews"
                    class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-amber-400 hover:text-amber-300 bg-[#121824] border border-amber-500/30 px-5 py-2 rounded-full shadow-md transition hover:scale-105">
                    <i class="fa-solid fa-chevron-down animate-bounce text-amber-400"></i>
                    この映画のレビュー一覧を見る（{{ count($reviews) }}件）
                </a>
            </div>

            <!-- 2. 下段：レビュー一覧 -->
            <div id="reviews" class="space-y-5 w-full pt-6 border-t border-gray-800">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-comments text-amber-500"></i>
                        この映画のレビュー
                        <span
                            class="text-xs bg-amber-500/20 text-amber-400 border border-amber-500/30 px-2.5 py-0.5 rounded-full font-bold">
                            {{ count($reviews) }}件
                        </span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
                    @forelse($reviews as $review)
                        <div id="review-{{ $review->id }}"
                            class="bg-[#121824] border border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xl space-y-3 w-full flex flex-col justify-between scroll-mt-6 transition-all duration-300">
                            <div class="space-y-2.5">
                                <div class="flex items-center justify-between border-b border-gray-800 pb-2.5">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-8 h-8 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center font-bold text-xs">
                                            {{ mb_substr($review->user->nickname ?? $review->user->name ?? '匿', 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-xs sm:text-sm text-gray-200 block">
                                                {{ $review->user->nickname ?? $review->user->name ?? '匿名ユーザー' }}
                                            </span>
                                            <span class="text-[10px] text-gray-500">
                                                {{ is_object($review) && $review->created_at ? $review->created_at->diffForHumans() : '' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div
                                        class="bg-amber-500/10 border border-amber-500/30 text-amber-400 px-2.5 py-0.5 rounded-full text-xs font-extrabold flex items-center gap-1">
                                        <i class="fa-solid fa-star text-[10px]"></i>
                                        <span>{{ is_object($review) ? number_format($review->rating, 1) : '8.0' }}</span>
                                    </div>
                                </div>

                                @php
                                    $moodVal = is_object($review) ? ($review->mood ?? $review->moods ?? '') : '';
                                    $moodList = is_array($moodVal) ? $moodVal : explode(',', $moodVal);
                                @endphp
                                @if(!empty(array_filter($moodList)))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($moodList as $m)
                                            @if(trim($m))
                                                <span
                                                    class="bg-amber-500/20 text-amber-300 border border-amber-500/40 text-[11px] px-2 py-0.5 rounded-md font-semibold">
                                                    #{{ trim(str_replace('#', '', $m)) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                <p
                                    class="text-xs sm:text-sm text-gray-200 leading-relaxed bg-[#1a2332] p-3 rounded-xl border border-gray-800/80">
                                    {{ $review->comment }}
                                </p>
                            </div>

                            <!-- ❤️ いいねボタン ＆ 💬 コメント開閉ボタン -->
                            <div class="flex items-center justify-between pt-2 border-t border-gray-800/60 text-xs">
                                <form action="{{ route('reviews.like', $review->id ?? $review->review_id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center gap-1.5 px-3 py-1 rounded-full border transition {{ $review->isLikedBy(Auth::user()) ? 'bg-rose-500/20 border-rose-500/50 text-rose-400' : 'bg-gray-800/80 border-gray-700 text-gray-400 hover:text-rose-400 hover:border-rose-500/30' }}">
                                        <i
                                            class="{{ $review->isLikedBy(Auth::user()) ? 'fa-solid' : 'fa-regular' }} fa-heart text-xs"></i>
                                        <span class="font-bold">{{ $review->likes->count() }}</span>
                                    </button>
                                </form>

                                @php
                                    $commentsCount = $review->comments_count ?? (method_exists($review, 'comments') ? $review->comments->count() : 0);
                                @endphp
                                <button type="button" onclick="toggleCommentBox({{ $review->id }})"
                                    class="text-gray-400 hover:text-amber-400 transition flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-800/80 border border-gray-700 hover:border-amber-500/40">
                                    <i class="fa-regular fa-comment text-xs"></i>
                                    <span class="font-bold">{{ $commentsCount }} 件</span>
                                    <i class="fa-solid fa-chevron-down text-[10px] ml-1 transition-transform duration-200"
                                        id="comment-arrow-{{ $review->id }}"></i>
                                </button>
                            </div>

                            <!-- 💡 クリックで開閉するコメントエリア -->
                            <div id="comment-box-{{ $review->id }}"
                                class="hidden pt-3 border-t border-gray-800/80 space-y-3">
                                @if(method_exists($review, 'comments') && $review->comments->count() > 0)
                                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                                        @foreach($review->comments as $comment)
                                            <div class="bg-[#1a2332] p-2.5 rounded-lg border border-gray-800 text-xs space-y-1">
                                                <div class="flex items-center justify-between text-gray-400 text-[10px]">
                                                    <span class="font-bold text-amber-400">
                                                        {{ $comment->user->nickname ?? $comment->user->name ?? 'ユーザー' }}
                                                    </span>
                                                    <span>{{ $comment->created_at ? $comment->created_at->diffForHumans() : '' }}</span>
                                                </div>
                                                <p class="text-gray-200 leading-snug">{{ $comment->comment ?? $comment->body }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-[11px] text-gray-500 text-center py-1">まだコメントはありません。</p>
                                @endif

                                <!-- コメント投稿フォーム -->
                                <form action="{{ route('reviews.comments.store', $review->id) }}" method="POST"
                                    class="flex gap-2">
                                    @csrf
                                    <input type="text" name="comment" required placeholder="コメントを書く..." autocomplete="off"
                                        class="flex-1 bg-[#1a2332] border border-gray-700 rounded-lg px-3 py-1.5 text-xs text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500">
                                    <button type="submit"
                                        class="bg-amber-500 hover:bg-amber-400 text-black font-bold px-3 py-1.5 rounded-lg text-xs transition flex-shrink-0">
                                        送信
                                    </button>
                                </form>
                            </div>

                        </div>
                    @empty
                        <div
                            class="col-span-full bg-[#121824] border border-gray-800 rounded-2xl p-6 text-center text-gray-400 text-xs sm:text-sm w-full">
                            まだレビューがありません。最初のレビューを投稿してみましょう！
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script>
        function updateRatingDisplay(slider) {
            const val = parseFloat(slider.value);
            const min = parseFloat(slider.min) || 1.0;
            const max = parseFloat(slider.max) || 10.0;

            const percentage = ((val - min) / (max - min)) * 100;
            slider.style.background = `linear-gradient(to right, #f59e0b 0%, #f59e0b ${percentage}%, #374151 ${percentage}%, #374151 100%)`;

            const ratingValueElem = document.getElementById('ratingValue');
            if (ratingValueElem) {
                ratingValueElem.innerText = val.toFixed(1);
            }

            const ratingLabelElem = document.getElementById('ratingLabel');
            if (ratingLabelElem) {
                let text = '';
                if (val >= 9.0) {
                    text = '🏆 歴史的名作！絶対に観るべき傑作';
                } else if (val >= 8.0) {
                    text = '✨ 超おすすめ！観て後悔なし';
                } else if (val >= 6.5) {
                    text = '👍 かなり面白い！おすすめの作品';
                } else if (val >= 5.0) {
                    text = '👌 普通に楽しめる標準的な作品';
                } else if (val >= 3.0) {
                    text = '🤔 自分にはあまり合わなかったかも…';
                } else {
                    text = '😅 正直あまりハマらなかった / 期待外れ';
                }
                ratingLabelElem.innerText = text;
            }
        }

        function toggleCommentBox(reviewId) {
            const box = document.getElementById('comment-box-' + reviewId);
            const arrow = document.getElementById('comment-arrow-' + reviewId);

            if (box) {
                box.classList.toggle('hidden');
            }
            if (arrow) {
                arrow.classList.toggle('rotate-180');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const slider = document.getElementById('ratingSlider');
            if (slider) {
                updateRatingDisplay(slider);
            }
        });
    </script>
</x-app-layout>