<x-app-layout>
    <style>
        /* スライダーの標準背景設定（JSでlinear-gradientを動的に変更） */
        input[type="range"] {
            -webkit-appearance: none;
            appearance: none;
            background: linear-gradient(to right, #f59e0b 77.7%, #374151 77.7%);
            border-radius: 8px;
            outline: none;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #f59e0b;
            cursor: pointer;
            box-shadow: 0 0 8px rgba(245, 158, 11, 0.8);
            transition: transform 0.1s ease;
        }

        input[type="range"]::-webkit-slider-thumb:hover {
            transform: scale(1.2);
        }

        input[type="range"]::-moz-range-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #f59e0b;
            cursor: pointer;
            box-shadow: 0 0 8px rgba(245, 158, 11, 0.8);
            transition: transform 0.1s ease;
        }

        input[type="range"]::-moz-range-thumb:hover {
            transform: scale(1.2);
        }

        /* カスタムスクロールバー */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #121824;
            border-radius: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #f59e0b;
            border-radius: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d97706;
        }

        /* ハイライトアニメーション */
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

        /* 検索候補用の細いダークスクロールバー */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #0b0e14;
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #f59e0b;
        }

        /* Firefox用 */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #374151 #0b0e14;
        }
    </style>

    <div class="min-h-screen bg-[#0b0f17] text-gray-100 py-3 px-2 sm:px-6 lg:px-8 w-full max-w-full overflow-x-hidden">
        <div class="max-w-7xl mx-auto space-y-3.5 w-full">

            <!-- 1つ前に戻るボタン -->
            <div>
                <button type="button" onclick="goBackOrHome()"
                    class="inline-flex items-center gap-1.5 bg-[#121824] hover:bg-gray-800 text-gray-300 border border-gray-800 font-bold px-3 py-1.5 rounded-full text-xs transition cursor-pointer shadow-lg">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> 前のページへ戻る
                </button>
            </div>

            <!-- 1. 上段：映画情報 ＆ レビュー投稿フォーム -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-stretch w-full">

                <!-- 👈 左：映画情報カード -->
                <div
                    class="bg-[#121824] border border-gray-800/90 rounded-2xl p-3.5 sm:p-5 shadow-xl w-full flex flex-col justify-between overflow-hidden">
                    <div class="flex flex-col sm:flex-row gap-3.5 sm:gap-5 h-full">

                        <!-- 🎬 左カラム：ポスター ＋ どこで見れる？ ＋ シェア・TMDBボタン -->
                        <div class="w-full sm:w-44 flex-shrink-0 flex flex-col items-center sm:items-stretch gap-2.5">

                            <!-- 1. ポスター画像 -->
                            <div
                                class="w-32 sm:w-full h-[180px] sm:h-[260px] rounded-xl overflow-hidden shadow-2xl border border-gray-700/40 bg-gray-900 flex-shrink-0">
                                <img src="{{ !empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : asset('images/no-poster.png') }}"
                                    alt="{{ $movie['title'] }}" class="w-full h-full object-cover">
                            </div>

                            <!-- 2. どこで見れる？ -->
                            <div class="w-full max-w-[200px] sm:max-w-none">
                                <div
                                    class="bg-[#1a2332] border border-gray-800/80 rounded-lg py-1.5 px-2 text-center w-full">
                                    <div
                                        class="text-[10px] text-amber-400 font-bold uppercase tracking-wider mb-0.5 flex items-center justify-center gap-1">
                                        <i class="fa-solid fa-tv text-[9px]"></i> どこで見れる？
                                    </div>
                                    <div class="text-[10px] sm:text-[11px] font-semibold text-gray-300 leading-tight">
                                        🎬 劇場公開 / 配信準備中
                                    </div>
                                </div>
                            </div>

                            <!-- 3. シェア / TMDB ボタン -->
                            <div class="grid grid-cols-2 gap-1.5 w-full max-w-[200px] sm:max-w-none">
                                <a href="https://twitter.com/intent/tweet?text={{ urlencode('『' . $movie['title'] . '』をチェック！ #MovieMood') }}&url={{ urlencode(request()->fullUrl()) }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="bg-gray-800/90 hover:bg-gray-700 border border-gray-700/80 text-gray-300 hover:text-white rounded-lg py-1.5 px-1 text-[11px] font-bold transition flex items-center justify-center gap-1">
                                    シェア
                                </a>

                                <a href="https://www.themoviedb.org/movie/{{ $movie['id'] }}" target="_blank"
                                    rel="noopener noreferrer"
                                    class="bg-gray-800/90 hover:bg-gray-700 border border-gray-700/80 text-gray-300 hover:text-white rounded-lg py-1.5 px-1 text-[11px] font-bold transition flex items-center justify-center gap-1">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i> TMDB
                                </a>
                            </div>

                        </div>

                        <!-- 📝 右カラム：タイトル・メタ情報・あらすじ -->
                        <div class="flex-1 min-w-0 h-full flex flex-col justify-between space-y-3">
                            <!-- 上部情報 -->
                            <div class="space-y-2 flex-shrink-0">
                                <div class="flex items-start justify-between gap-2">
                                    <h1 class="text-base sm:text-lg font-bold text-white tracking-tight leading-snug">
                                        {{ $movie['title'] }}
                                    </h1>

                                    <form action="{{ route('watchlist.toggle') }}" method="POST" class="flex-shrink-0">
                                        @csrf
                                        <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
                                        <input type="hidden" name="title" value="{{ $movie['title'] }}">
                                        <input type="hidden" name="poster_path"
                                            value="{{ $movie['poster_path'] ?? '' }}">

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
                                            class="py-1 px-2.5 rounded-full text-[11px] font-bold transition duration-200 flex items-center gap-1 border shadow-sm bg-amber-500 text-black border-amber-400 hover:bg-amber-400">
                                            <i
                                                class="fa-{{ $inWatchlist ? 'solid' : 'regular' }} fa-bookmark text-[10px]"></i>
                                            <span>{{ $inWatchlist ? '登録中' : '＋ 気になる！' }}</span>
                                        </button>
                                    </form>
                                </div>

                                <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                                    <span
                                        class="bg-amber-500/10 border border-amber-500/30 text-amber-400 px-2 py-0.5 rounded-full text-[11px] font-extrabold flex items-center gap-1">
                                        <i class="fa-solid fa-star text-amber-400 text-[9px]"></i>
                                        TMDB: {{ number_format($movie['vote_average'] ?? 0, 1) }}
                                    </span>
                                    <span
                                        class="bg-gray-800 border border-gray-700/80 text-gray-300 text-[11px] px-2 py-0.5 rounded-full">
                                        📅
                                        {{ isset($movie['release_date']) ? substr($movie['release_date'], 0, 7) : '未定' }}
                                    </span>
                                    @if(!empty($movie['runtime']))
                                        <span
                                            class="bg-gray-800 border border-gray-700/80 text-gray-300 text-[11px] px-2 py-0.5 rounded-full">
                                            ⏰ {{ $movie['runtime'] }}分
                                        </span>
                                    @endif
                                </div>

                                @if(!empty($movie['director']))
                                    <div class="text-[11px] text-gray-400 truncate">
                                        🎬 監督: <span class="text-gray-200 font-semibold">{{ $movie['director'] }}</span>
                                    </div>
                                @endif

                                @if(!empty($movie['genres']) && is_array($movie['genres']))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($movie['genres'] as $genre)
                                            <span
                                                class="text-[10px] bg-gray-800/90 text-gray-300 px-2 py-0.5 rounded border border-gray-700/60">
                                                {{ is_array($genre) ? ($genre['name'] ?? '') : $genre }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- あらすじエリア -->
                            <div class="space-y-1.5 flex-1 min-h-0 flex flex-col pt-1">
                                <h3
                                    class="text-xs sm:text-sm font-bold text-amber-400 uppercase tracking-wider flex items-center gap-1 flex-shrink-0">
                                    <i class="fa-solid fa-book-open"></i> STORY / あらすじ
                                </h3>
                                <div
                                    class="bg-[#1a2332] p-3 rounded-xl border border-gray-800/80 flex-1 overflow-y-auto custom-scrollbar max-h-48 sm:max-h-60">
                                    <p class="text-sm sm:text-base text-gray-200 leading-relaxed font-normal">
                                        {{ (!empty($movie['overview']) && trim($movie['overview']) !== '') ? $movie['overview'] : '※日本語あらすじ情報は準備中です。' }}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- 👉 右：レビュー投稿フォーム（鑑賞後 / 鑑賞前 タブ切り替え） -->
                <div
                    class="bg-[#121824] border border-gray-800/90 rounded-2xl p-3.5 sm:p-5 shadow-xl flex flex-col justify-between w-full space-y-3">
                    <div class="flex-shrink-0 space-y-2">
                        <h2 class="text-xs sm:text-sm font-bold text-white flex items-center gap-1.5">
                            <i class="fa-solid fa-pen-to-square text-amber-500"></i>
                            この映画のレビュー・メモを投稿する
                        </h2>

                        <!-- 🔀 鑑賞前 / 鑑賞後 タブ切り替え -->
                        <div class="grid grid-cols-2 gap-1 bg-[#1a2332] p-1 rounded-xl border border-gray-800">
                            <button type="button" id="tabWatched" onclick="switchStatus('watched')"
                                class="py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 bg-amber-500 text-black shadow-md flex items-center justify-center gap-1">
                                🍿 観た（鑑賞後）
                            </button>
                            <button type="button" id="tabWantToWatch" onclick="switchStatus('want_to_watch')"
                                class="py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 text-gray-400 hover:text-white flex items-center justify-center gap-1">
                                ✨ 観たい（鑑賞前）
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('reviews.store', $movie['id']) }}" method="POST"
                        class="flex-1 flex flex-col justify-between space-y-3 min-h-0">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
                        <input type="hidden" name="movie_title" value="{{ $movie['title'] }}">
                        <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] ?? '' }}">
                        <input type="hidden" name="status" id="review_status" value="{{ old('status', 'watched') }}">
                        <div id="selectedMoodsInputContainer"></div>

                        <div class="space-y-3">
                            <!-- 【共通エリア】評価・期待度スライダー -->
                            <div id="ratingSection" class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label for="ratingSlider" id="ratingTitle"
                                        class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                        評価（1.0 〜 10.0）
                                    </label>
                                    <div
                                        class="text-amber-400 font-extrabold text-xs sm:text-sm flex items-center gap-1">
                                        <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                                        <span id="ratingValue">{{ number_format(old('rating', 8.0), 1) }}</span>
                                    </div>
                                </div>

                                <input type="range" name="rating" id="ratingSlider" min="1.0" max="10.0" step="0.1"
                                    value="{{ old('rating', '8.0') }}"
                                    class="w-full h-2 rounded-lg appearance-none cursor-pointer focus:outline-none transition-all duration-75"
                                    oninput="updateRatingDisplay(this)">

                                <div class="bg-[#1a2332] border border-gray-800/80 rounded-lg px-2.5 py-1 text-center">
                                    <span id="ratingLabel"
                                        class="text-[11px] font-bold text-amber-400 transition-colors duration-150">
                                        ✨ 超おすすめ！観て後悔なし
                                    </span>
                                </div>
                            </div>

                            <!-- 【共通エリア】気分・期待タグ -->
                            <div id="moodTagSection" class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <span id="moodTagLabel"
                                        class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                        観たあとの気分タグ（最大4つ選択可）
                                    </span>
                                    <span class="text-[10px] text-gray-500 font-bold" id="tagCountText">0/4</span>
                                </div>
                                <div id="moodTagContainer" class="flex flex-wrap gap-1.5 pt-1">
                                    <!-- JSでタグ生成 -->
                                </div>
                            </div>

                            <!-- 【共通】コメント入力欄 -->
                            <div class="space-y-1">
                                <label for="comment" id="commentLabel"
                                    class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    レビュー感想コメント
                                </label>
                                <textarea id="comment" name="comment" required placeholder="この映画を観た感想や見どころを書いてみましょう..."
                                    class="w-full h-24 bg-[#1a2332] border border-gray-700/80 rounded-xl p-2.5 text-xs text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none leading-relaxed resize-none custom-scrollbar">{{ old('comment') }}</textarea>
                            </div>
                        </div>

                        <!-- 投稿ボタン -->
                        <div class="pt-1">
                            <button type="submit" id="submitBtn"
                                class="w-full bg-amber-500 hover:bg-amber-400 text-black font-extrabold px-4 py-2.5 rounded-xl text-xs sm:text-sm transition shadow-lg flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                <span id="submitBtnText">レビューを投稿する</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- 下部：投稿一覧へ移動ボタン -->
            <div class="flex justify-center pt-1">
                <a href="#reviews"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-400 hover:text-amber-300 bg-[#121824] border border-amber-500/20 px-4 py-2 rounded-full shadow-md transition hover:scale-105">
                    <i class="fa-solid fa-chevron-down text-amber-400 text-[10px]"></i>
                    この映画のレビュー一覧を見る（{{ count($reviews) }}件）
                </a>
            </div>

            <!-- 2. 下段：レビュー一覧 -->
            <div id="reviews" class="space-y-4 w-full pt-3 border-t border-gray-800">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                    @forelse($reviews as $review)
                                    @php
                                        $user = $review->user;
                                        $avatarUrl = $user->avatar_url ?? $user->avatar ?? $user->profile_photo_url ?? null;

                                        // 1. ステータス値のチェック
                                        $statusVal = strtolower(trim($review->status ?? ''));

                                        // 2. ムードタグの中身チェック
                                        $moodVal = is_object($review) ? ($review->mood ?? $review->moods ?? '') : '';
                                        $moodStr = is_array($moodVal) ? implode(',', $moodVal) : (string) $moodVal;

                                        // ステータスがwant系、またはタグ名に「しそう」が含まれる場合は「観たい（鑑賞前）」と判定
                                        $isWantToWatch = in_array($statusVal, ['want_to_watch', 'want', 'want-to-watch', '1', 'before'])
                                            || str_contains($moodStr, 'しそう');
                                    @endphp

                                    <div id="review-{{ $review->id }}"
                                        class="bg-[#121824] border border-gray-800 rounded-2xl p-3.5 sm:p-5 shadow-xl space-y-3 w-full flex flex-col justify-between scroll-mt-6 transition-all duration-300">

                                        <div class="space-y-2">
                                            <!-- ヘッダー（ユーザー情報 ＆ スコア） -->
                                            <div class="flex items-center justify-between border-b border-gray-800 pb-2">
                                                <div class="flex items-center gap-2">
                                                    @if($avatarUrl)
                                                        <img src="{{ asset($avatarUrl) }}"
                                                            alt="{{ $user->nickname ?? $user->name ?? 'User' }}"
                                                            class="w-7 h-7 sm:w-8 sm:h-8 rounded-full object-cover border border-amber-500/40">
                                                    @else
                                                        <div
                                                            class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center font-bold text-xs">
                                                            {{ mb_substr($user->nickname ?? $user->name ?? '匿', 0, 1) }}
                                                        </div>
                                                    @endif

                                                    <div>
                                                        <span class="font-bold text-xs sm:text-sm text-gray-200 block">
                                                            {{ $user->nickname ?? $user->name ?? '匿名ユーザー' }}
                                                        </span>
                                                        <span class="text-[9px] sm:text-[10px] text-gray-500">
                                                            {{ is_object($review) && $review->created_at ? $review->created_at->diffForHumans() : '' }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- 1. スコア / 期待度 バッジ（紫 vs 金） -->
                                                <div class="flex items-center gap-1.5">
                                                    @if($isWantToWatch)
                                                        <!-- 観たい（紫） -->
                                                        <span
                                                            class="bg-purple-500/20 border border-purple-500/40 text-purple-300 px-2 py-0.5 rounded-full text-[10px] font-bold flex items-center gap-1">
                                                            ✨ 期待度: {{ number_format($review->rating ?? 0, 1) }}
                                                        </span>
                                                    @else
                                                        <!-- 観た（金） -->
                                                        <div
                                                            class="bg-amber-500/10 border border-amber-500/30 text-amber-400 px-2 py-0.5 rounded-full text-xs font-extrabold flex items-center gap-1">
                                                            <i class="fa-solid fa-star text-[9px]"></i>
                                                            <span>{{ is_object($review) ? number_format($review->rating, 1) : '8.0' }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- 2. 気分タグ（個別のタグ文字列でも「しそう」があれば紫に固定） -->
                                            @php
                                                if (is_array($moodVal)) {
                                                    $moodList = $moodVal;
                                                } else {
                                                    $moodList = explode(',', $moodVal);
                                                }
                                            @endphp
                                            @if(!empty(array_filter($moodList)))
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($moodList as $m)
                                                        @php
                                                            $cleanTag = trim(str_replace('#', '', $m));
                                                            // 個別タグに「しそう」が入っているか、または全体判定が観たいの場合
                                                            $isPurpleTag = $isWantToWatch || str_contains($cleanTag, 'しそう');
                                                        @endphp
                                                        @if($cleanTag)
                                                            <span
                                                                class="{{ $isPurpleTag ? 'bg-purple-500/20 text-purple-300 border-purple-500/40' : 'bg-amber-500/20 text-amber-300 border-amber-500/40' }} border text-[10px] sm:text-[11px] px-2 py-0.5 rounded-md font-semibold">
                                                                #{{ $cleanTag }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- コメント本文 -->
                                            <p
                                                class="text-xs sm:text-sm text-gray-200 leading-relaxed bg-[#1a2332] p-2.5 sm:p-3 rounded-xl border border-gray-800/80">
                                                {{ $review->comment }}
                                            </p>
                                        </div>

                                        <!-- いいね & コメント開閉エリア -->
                                        <div class="flex items-center justify-between pt-2 border-t border-gray-800/60 text-xs">

                                            <!-- 1. いいねボタン -->
                                            @php
                                                $isLikedByMe = method_exists($review, 'isLikedBy') ? $review->isLikedBy(Auth::user()) : false;
                                                $likesCount = method_exists($review, 'likes') ? $review->likes->count() : 0;
                                            @endphp
                                            <form action="{{ route('reviews.like', $review->id ?? $review->review_id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="flex items-center gap-1.5 px-2.5 py-1 rounded-full border transition duration-200 
                                                        {{ $isLikedByMe
                        ? 'bg-rose-500/10 border-rose-500/40 text-rose-500 hover:bg-rose-500/20'
                        : 'bg-gray-800/80 border-gray-700 text-gray-300 hover:text-rose-400 hover:border-gray-600' }}">
                                                    <i
                                                        class="{{ $isLikedByMe ? 'fa-solid text-rose-500' : 'fa-regular' }} fa-heart text-xs"></i>
                                                    <span class="font-bold text-[11px]">{{ $likesCount }}</span>
                                                </button>
                                            </form>

                                            <!-- 2. コメント開閉ボタン -->
                                            @php
                                                $commentsCount = $review->comments_count ?? (method_exists($review, 'comments') ? $review->comments->count() : 0);
                                                $hasComments = $commentsCount > 0;
                                            @endphp
                                            <button type="button" onclick="toggleCommentBox({{ $review->id }})" class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-800/80 border border-gray-700 hover:border-amber-500/40 transition duration-200 
                                                        {{ $hasComments ? 'text-amber-400' : 'text-gray-400 hover:text-amber-400' }}">
                                                <i class="fa-regular fa-comment text-xs {{ $hasComments ? 'text-amber-400' : '' }}"></i>
                                                <span class="font-bold text-[11px]">{{ $commentsCount }} 件</span>
                                                <i class="fa-solid fa-chevron-down text-[9px] ml-0.5 transition-transform duration-200"
                                                    id="comment-arrow-{{ $review->id }}"></i>
                                            </button>
                                        </div>

                                        <!-- コメントエリア -->
                                        <div id="comment-box-{{ $review->id }}"
                                            class="hidden pt-2.5 border-t border-gray-800/80 space-y-2.5">
                                            @if(method_exists($review, 'comments') && $review->comments->count() > 0)
                                                <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 custom-scrollbar">
                                                    @foreach($review->comments as $comment)
                                                        <div class="bg-[#1a2332] p-2 rounded-lg border border-gray-800 text-xs space-y-0.5">
                                                            <div class="flex items-center justify-between text-gray-400 text-[9px]">
                                                                <span class="font-bold text-amber-400">
                                                                    {{ $comment->user->nickname ?? $comment->user->name ?? 'ユーザー' }}
                                                                </span>
                                                                <span>{{ $comment->created_at ? $comment->created_at->diffForHumans() : '' }}</span>
                                                            </div>
                                                            <p class="text-gray-200 leading-snug text-[11px]">
                                                                {{ $comment->comment ?? $comment->body }}
                                                            </p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-[10px] text-gray-500 text-center py-0.5">まだコメントはありません。</p>
                                            @endif

                                            <form action="{{ route('reviews.comments.store', $review->id) }}" method="POST"
                                                class="flex gap-1.5">
                                                @csrf
                                                <input type="text" name="comment" required placeholder="コメントを書く..." autocomplete="off"
                                                    class="flex-1 bg-[#1a2332] border border-gray-700 rounded-lg px-2.5 py-1 text-xs text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500">
                                                <button type="submit"
                                                    class="bg-amber-500 hover:bg-amber-400 text-black font-bold px-2.5 py-1 rounded-lg text-xs transition flex-shrink-0">
                                                    送信
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                    @empty
                        <div
                            class="col-span-full bg-[#121824] border border-gray-800 rounded-2xl p-8 text-center text-gray-400 text-xs w-full space-y-2">
                            <i class="fa-solid fa-comment-slash text-2xl text-gray-600"></i>
                            <p>まだレビューが投稿されていません。最初のレビューを投稿してみましょう！</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- JavaScript 処理 -->
    <script>
        // タグ定義
        const watchedMoodTags = ['号泣', 'スカッと', 'ハラハラ', 'キュン'];
        const wantMoodTags = ['号泣しそう', 'スカッとしそう', 'ハラハラしそう', 'キュンとしそう'];

        let selectedMoods = [];
        let currentStatus = '{{ old("status", "watched") }}';

        function goBackOrHome() {
            if (document.referrer && document.referrer.indexOf(window.location.host) !== -1) {
                history.back();
            } else {
                window.location.href = '/';
            }
        }

        function switchStatus(status) {
            currentStatus = status;
            const statusInput = document.getElementById('review_status');
            if (statusInput) statusInput.value = status;

            selectedMoods = [];

            const tabWatched = document.getElementById('tabWatched');
            const tabWantToWatch = document.getElementById('tabWantToWatch');
            const ratingTitle = document.getElementById('ratingTitle');
            const moodTagLabel = document.getElementById('moodTagLabel');
            const commentLabel = document.getElementById('commentLabel');
            const commentInput = document.getElementById('comment');
            const submitBtn = document.getElementById('submitBtn');
            const submitBtnText = document.getElementById('submitBtnText');

            if (status === 'watched') {
                // 🍿 「観た」：イエロー・ゴールド系
                if (tabWatched) tabWatched.className = 'py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 bg-amber-500 text-black shadow-md flex items-center justify-center gap-1 cursor-pointer';
                if (tabWantToWatch) tabWantToWatch.className = 'py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 text-gray-400 hover:text-white flex items-center justify-center gap-1 cursor-pointer';

                if (ratingTitle) ratingTitle.innerText = '評価（1.0 〜 10.0）';
                if (moodTagLabel) moodTagLabel.innerText = '観たあとの気分タグ（最大4つ選択可）';
                if (commentLabel) commentLabel.innerText = 'レビュー感想コメント';
                if (commentInput) {
                    commentInput.placeholder = 'この映画を観た感想や見どころを書いてみましょう...';
                    commentInput.className = 'w-full bg-[#121824] border border-gray-700 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-white rounded-xl p-3 text-xs focus:outline-none transition';
                }

                if (submitBtn) submitBtn.className = 'w-full py-3 bg-amber-500 hover:bg-amber-400 text-black font-extrabold rounded-xl text-xs transition shadow-lg flex items-center justify-center gap-2 cursor-pointer';
                if (submitBtnText) submitBtnText.innerText = 'レビューを投稿する';
            } else {
                // ✨ 「観たい」：パープル系
                if (tabWantToWatch) tabWantToWatch.className = 'py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 bg-purple-600 text-white shadow-md flex items-center justify-center gap-1 cursor-pointer';
                if (tabWatched) tabWatched.className = 'py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 text-gray-400 hover:text-white flex items-center justify-center gap-1 cursor-pointer';

                if (ratingTitle) ratingTitle.innerText = '期待度（1.0 〜 10.0）';
                if (moodTagLabel) moodTagLabel.innerText = '期待するポイントタグ（最大4つ選択可）';
                if (commentLabel) commentLabel.innerText = '観たい理由・期待メモ';
                if (commentInput) {
                    commentInput.placeholder = 'この映画に期待することや観たい理由を書いてみましょう...';
                    commentInput.className = 'w-full bg-[#121824] border border-gray-700 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 text-white rounded-xl p-3 text-xs focus:outline-none transition';
                }

                if (submitBtn) submitBtn.className = 'w-full py-3 bg-purple-600 hover:bg-purple-500 text-white font-extrabold rounded-xl text-xs transition shadow-lg flex items-center justify-center gap-2 cursor-pointer';
                if (submitBtnText) submitBtnText.innerText = '観たいメモを保存する';
            }

            renderMoodTags();
            const ratingSlider = document.getElementById('ratingSlider');
            if (ratingSlider) updateRatingDisplay(ratingSlider);
        }

        function renderMoodTags() {
            const container = document.getElementById('moodTagContainer');
            if (!container) return;

            const tags = currentStatus === 'watched' ? watchedMoodTags : wantMoodTags;
            container.innerHTML = '';

            tags.forEach(tag => {
                const isSelected = selectedMoods.includes(tag);
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.onclick = () => toggleMoodTag(tag);

                if (currentStatus === 'watched') {
                    // 「観た」選択時：イエロータグ
                    btn.className = isSelected
                        ? 'text-[10px] sm:text-[11px] font-bold px-3 py-1 rounded-lg border transition duration-150 bg-amber-500 text-black border-amber-400 shadow-md cursor-pointer'
                        : 'text-[10px] sm:text-[11px] font-medium px-3 py-1 rounded-lg border transition duration-150 bg-[#1a2332] text-gray-300 border-gray-700/80 hover:border-gray-500 cursor-pointer';
                } else {
                    // 「観たい」選択時：パープルタグ
                    btn.className = isSelected
                        ? 'text-[10px] sm:text-[11px] font-bold px-3 py-1 rounded-lg border transition duration-150 bg-purple-600 text-white border-purple-400 shadow-md cursor-pointer'
                        : 'text-[10px] sm:text-[11px] font-medium px-3 py-1 rounded-lg border transition duration-150 bg-[#1a2332] text-gray-300 border-gray-700/80 hover:border-gray-500 cursor-pointer';
                }

                btn.innerText = '#' + tag;
                container.appendChild(btn);
            });

            const tagCountText = document.getElementById('tagCountText');
            if (tagCountText) tagCountText.innerText = `${selectedMoods.length}/4`;

            const hiddenContainer = document.getElementById('selectedMoodsInputContainer');
            if (hiddenContainer) {
                hiddenContainer.innerHTML = '';
                selectedMoods.forEach(mood => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'moods[]';
                    input.value = '#' + mood;
                    hiddenContainer.appendChild(input);
                });
            }
        }

        function toggleMoodTag(tag) {
            const idx = selectedMoods.indexOf(tag);
            if (idx > -1) {
                selectedMoods.splice(idx, 1);
            } else {
                if (selectedMoods.length >= 4) {
                    alert('タグは最大4つまで選択できます。');
                    return;
                }
                selectedMoods.push(tag);
            }
            renderMoodTags();
        }

        function updateRatingDisplay(slider) {
            if (!slider) return;
            const val = parseFloat(slider.value).toFixed(1);
            const ratingValue = document.getElementById('ratingValue');
            if (ratingValue) ratingValue.innerText = val;

            const percentage = ((val - 1.0) / 9.0) * 100;

            // ステータスによってメーター（スライダー）のゲージ色を変更
            const activeColor = currentStatus === 'watched' ? '#f59e0b' : '#a855f7';
            slider.style.background = `linear-gradient(to right, ${activeColor} ${percentage}%, #374151 ${percentage}%)`;

            const label = document.getElementById('ratingLabel');
            if (label) {
                if (currentStatus === 'watched') {
                    if (val >= 9.0) label.innerText = '✨ 超おすすめ！名作間違いなし';
                    else if (val >= 7.5) label.innerText = '👍 かなり面白かった！';
                    else if (val >= 5.0) label.innerText = '🙂 普通に楽しめた';
                    else label.innerText = '🤔 好みが分かれる作品';
                } else {
                    if (val >= 9.0) label.innerText = '🔥 絶対に観たい！期待大';
                    else if (val >= 7.5) label.innerText = '✨ かなり気になっている';
                    else if (val >= 5.0) label.innerText = '👀 機会があれば観たい';
                    else label.innerText = '💭 ちょっとチェックしておく';
                }
            }
        }

        function toggleCommentBox(id) {
            const box = document.getElementById(`comment-box-${id}`);
            const arrow = document.getElementById(`comment-arrow-${id}`);
            if (!box) return;
            if (box.classList.contains('hidden')) {
                box.classList.remove('hidden');
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            } else {
                box.classList.add('hidden');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            switchStatus(currentStatus);
        });
    </script>
</x-app-layout>