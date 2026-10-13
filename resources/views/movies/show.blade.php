<x-app-layout>
    <script>
        (function () {
            'use strict';

            const watchedTags = [
                { value: '号泣', label: '😭 #号泣' },
                { value: 'スカッと', label: '✨ #スカッと' },
                { value: 'ハラハラ', label: '😱 #ハラハラ' },
                { value: 'キュン', label: '💗 #キュン' }
            ];

            const wantTags = [
                { value: '号泣しそう', label: '😭 #号泣しそう' },
                { value: 'スカッとしそう', label: '✨ #スカッとしそう' },
                { value: 'ハラハラしそう', label: '😱 #ハラハラしそう' },
                { value: 'キュンとしそう', label: '💗 #キュンとしそう' }
            ];

            const watchedMessages = [
                { min: 9, text: '🌟 最高！何度でも観たい！' },
                { min: 8, text: '✨ 超おすすめ！観て後悔なし' },
                { min: 7, text: '😊 おすすめ！観てよかった' },
                { min: 6, text: '👍 なかなか楽しめた！' },
                { min: 5, text: '🙂 まずまず楽しめた' },
                { min: 4, text: '🤔 好みが分かれそう' },
                { min: 3, text: '💭 少し物足りなかった' },
                { min: 2, text: '😔 あまり合わなかった' },
                { min: 1, text: '💧 自分には合わなかった' }
            ];

            const wantMessages = [
                { min: 9, text: '💖 絶対に観たい！期待大！' },
                { min: 8, text: '✨ かなり期待している！' },
                { min: 7, text: '🍿 観るのが楽しみ！' },
                { min: 6, text: '😊 面白そうで気になる！' },
                { min: 5, text: '👀 まずはチェックしたい' },
                { min: 4, text: '🤔 時間があれば観たい' },
                { min: 3, text: '💭 少し気になっている' },
                { min: 2, text: '🌱 まだ迷っている' },
                { min: 1, text: '📝 これから検討したい' }
            ];

            let currentStatus = 'watched';
            let selectedMoods = [];

            function getElement(id) {
                return document.getElementById(id);
            }

            function getMessage(rating, messages) {
                for (const item of messages) {
                    if (rating >= item.min) {
                        return item.text;
                    }
                }
                return messages[messages.length - 1].text;
            }

            function updateRatingDisplay(slider) {
                const ratingSlider = slider || getElement('ratingSlider');
                if (!ratingSlider) return;

                const rating = Number(ratingSlider.value);
                const isWant = currentStatus === 'want_to_watch';
                const color = isWant ? '#a855f7' : '#f59e0b';
                const percentage = ((rating - 1) / 9) * 100;

                const ratingValue = getElement('ratingValue');
                const ratingLabel = getElement('ratingLabel');
                const ratingValueWrapper = getElement('ratingValueWrapper');
                const ratingIcon = getElement('ratingIcon');

                if (ratingValue) {
                    ratingValue.textContent = rating.toFixed(1);
                }

                if (ratingLabel) {
                    ratingLabel.textContent = getMessage(
                        rating,
                        isWant ? wantMessages : watchedMessages
                    );
                    ratingLabel.style.color = color;
                }

                if (ratingValueWrapper) {
                    ratingValueWrapper.style.color = color;
                }

                if (ratingIcon) {
                    ratingIcon.className = isWant
                        ? 'fa-solid fa-heart text-[10px]'
                        : 'fa-solid fa-star text-[10px]';
                    ratingIcon.style.color = color;
                }

                ratingSlider.style.background =
                    `linear-gradient(to right, ${color} ${percentage}%, #374151 ${percentage}%)`;

                ratingSlider.style.setProperty('--slider-thumb-color', color);
            }

            function renderMoodTags() {
                const container = getElement('moodTagContainer');
                const hiddenContainer = getElement('selectedMoodsInputContainer');
                const countText = getElement('tagCountText');

                if (!container || !hiddenContainer) return;

                const tags = currentStatus === 'want_to_watch'
                    ? wantTags
                    : watchedTags;

                container.replaceChildren();
                hiddenContainer.replaceChildren();

                tags.forEach(function (tag) {
                    const isSelected = selectedMoods.includes(tag.value);

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.textContent = tag.label;

                    button.className =
                        'rounded-lg border px-2.5 py-1.5 text-[11px] font-bold transition cursor-pointer ' +
                        (isSelected
                            ? currentStatus === 'want_to_watch'
                                ? 'bg-purple-500/20 border-purple-400 text-purple-300'
                                : 'bg-amber-500/20 border-amber-400 text-amber-300'
                            : 'bg-gray-800/70 border-gray-700 text-gray-400 hover:text-white');

                    button.setAttribute('aria-pressed', String(isSelected));

                    button.addEventListener('click', function () {
                        const index = selectedMoods.indexOf(tag.value);

                        if (index !== -1) {
                            selectedMoods.splice(index, 1);
                        } else {
                            if (selectedMoods.length >= 4) {
                                return;
                            }
                            selectedMoods.push(tag.value);
                        }

                        renderMoodTags();
                    });

                    container.appendChild(button);

                    if (isSelected) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'moods[]';
                        hiddenInput.value = tag.value;

                        hiddenContainer.appendChild(hiddenInput);
                    }
                });

                if (countText) {
                    countText.textContent = selectedMoods.length + '/4';
                    countText.style.color =
                        currentStatus === 'want_to_watch' ? '#c084fc' : '#fbbf24';
                }
            }

            function switchStatus(status) {
                if (status !== 'watched' && status !== 'want_to_watch') {
                    return;
                }

                currentStatus = status;

                const isWant = status === 'want_to_watch';
                const color = isWant ? '#a855f7' : '#f59e0b';

                const statusInput = getElement('review_status');
                const tabWatched = getElement('tabWatched');
                const tabWant = getElement('tabWantToWatch');
                const ratingTitle = getElement('ratingTitle');
                const moodTagLabel = getElement('moodTagLabel');
                const commentLabel = getElement('commentLabel');
                const comment = getElement('comment');
                const submitBtn = getElement('submitBtn');
                const submitBtnText = getElement('submitBtnText');

                if (statusInput) {
                    statusInput.value = status;
                }

                if (tabWatched) {
                    tabWatched.className =
                        'py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 flex items-center justify-center gap-1 cursor-pointer ' +
                        (!isWant
                            ? 'bg-amber-500 text-black shadow-md'
                            : 'text-gray-400 hover:text-white');
                    tabWatched.setAttribute('aria-pressed', String(!isWant));
                }

                if (tabWant) {
                    tabWant.className =
                        'py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 flex items-center justify-center gap-1 cursor-pointer ' +
                        (isWant
                            ? 'bg-purple-500 text-white shadow-md'
                            : 'text-gray-400 hover:text-white');
                    tabWant.setAttribute('aria-pressed', String(isWant));
                }

                if (ratingTitle) {
                    ratingTitle.textContent = isWant
                        ? '期待度（1.0 〜 10.0）'
                        : '評価（1.0 〜 10.0）';
                }

                if (moodTagLabel) {
                    moodTagLabel.textContent = isWant
                        ? '観る前の期待タグ（最大4つ選択可）'
                        : '観たあとの気分タグ（最大4つ選択可）';
                }

                if (commentLabel) {
                    commentLabel.textContent = isWant
                        ? '期待・気になるポイント'
                        : 'レビュー感想コメント';
                }

                if (comment) {
                    comment.placeholder = isWant
                        ? 'この映画で気になっていることや、期待しているポイントを書いてみましょう...'
                        : 'この映画を観た感想や見どころを書いてみましょう...';
                }

                if (submitBtn) {
                    submitBtn.className =
                        'w-full font-extrabold px-4 py-2.5 rounded-xl text-xs sm:text-sm transition shadow-lg flex items-center justify-center gap-1.5 cursor-pointer ' +
                        (isWant
                            ? 'bg-purple-500 hover:bg-purple-400 text-white'
                            : 'bg-amber-500 hover:bg-amber-400 text-black');
                }

                if (submitBtnText) {
                    submitBtnText.textContent = isWant
                        ? '期待メモを投稿する'
                        : 'レビューを投稿する';
                }

                const ratingSlider = getElement('ratingSlider');

                if (ratingSlider) {
                    ratingSlider.style.setProperty(
                        '--slider-thumb-color',
                        color
                    );
                }

                selectedMoods = [];
                renderMoodTags();
                updateRatingDisplay(ratingSlider);
            }

            window.switchStatus = switchStatus;
            window.updateRatingDisplay = updateRatingDisplay;

            function initialize() {
                const statusInput = getElement('review_status');
                const ratingSlider = getElement('ratingSlider');

                if (!statusInput || !ratingSlider) {
                    return;
                }

                if (ratingSlider.dataset.moodInitialized === 'true') {
                    return;
                }

                ratingSlider.dataset.moodInitialized = 'true';

                const initialStatus = statusInput.value === 'want_to_watch'
                    ? 'want_to_watch'
                    : 'watched';

                switchStatus(initialStatus);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initialize);
            } else {
                initialize();
            }
        })();
    </script>

    <style>
        :root {
            --mood-gold: #f59e0b;
            --mood-gold-light: #fbbf24;
            --mood-purple: #a855f7;
            --mood-purple-light: #c084fc;
        }

        /* 評価スライダー */
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
            background: #fff;
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
            background: #fff;
            border: 2px solid #f59e0b;
            cursor: pointer;
            box-shadow: 0 0 8px rgba(245, 158, 11, 0.8);
        }

        /* 共通スクロールバー */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #374151 #0b0e14;
        }

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

        /* 通知・ターゲットハイライト（発光アニメーション） */
        [id^="review-"] {
            scroll-margin-top: 110px;
        }

        @keyframes review-gold-glow {
            0% {
                background-color: rgba(245, 158, 11, 0.35);
                border-color: #f59e0b;
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.9);
                outline: 2px solid #f59e0b;
                outline-offset: 3px;
            }

            35% {
                background-color: rgba(245, 158, 11, 0.2);
                border-color: #fbbf24;
                box-shadow: 0 0 30px 8px rgba(245, 158, 11, 0.55);
                outline: 2px solid #fbbf24;
                outline-offset: 3px;
            }

            100% {
                background-color: #121824;
                border-color: #1f2937;
                box-shadow: 0 0 0 0 transparent;
                outline-color: transparent;
                outline-offset: 0;
            }
        }

        [id^="review-"].review-target-highlight {
            animation: review-gold-glow 3.5s ease-in-out;
            position: relative;
            z-index: 10;
        }

        /* ユーザーアイコン（中央揃えのズレ解消） */
        .review-avatar {
            width: 32px;
            height: 32px;
            min-width: 32px;
            min-height: 32px;
            max-width: 32px;
            max-height: 32px;
            border-radius: 9999px;
            object-fit: cover;
            object-position: center;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0;
            line-height: 1 !important;
        }

        @media (max-width: 639px) {
            .review-avatar {
                width: 28px;
                height: 28px;
                min-width: 28px;
                min-height: 28px;
                max-width: 28px;
                max-height: 28px;
            }
        }

        .poster-column {
            display: flex !important;
            flex-direction: column !important;
            flex-shrink: 0;
        }

        .poster-share {
            margin-top: 8px !important;
        }

        .movie-story-section {
            margin-top: 12px !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 0px !important;
            min-height: 0 !important;
        }

        .movie-story-box {
            flex: 1 1 0px !important;
            min-height: 120px !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            box-sizing: border-box;
            padding-right: 8px;
        }

        @media (min-width: 1024px) {
            .movie-review-grid {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                align-items: stretch !important;
            }

            .movie-review-grid>.movie-info-card,
            .movie-review-grid>.review-post-card {
                height: 100% !important;
                display: flex !important;
                flex-direction: column !important;
                box-sizing: border-box !important;
                min-height: 0 !important;
            }

            .movie-info-card>.movie-info-inner {
                height: 100% !important;
                display: grid !important;
                grid-template-columns: 180px minmax(0, 1fr) !important;
                gap: 16px !important;
                align-items: start !important;
                min-height: 0 !important;
            }

            .movie-info-inner>div:last-child {
                display: flex !important;
                flex-direction: column !important;
                height: 100% !important;
                min-height: 0 !important;
            }

            .review-post-card>form {
                display: flex !important;
                flex-direction: column !important;
                flex: 1 1 auto !important;
            }

            .review-post-card>form>div:last-child {
                margin-top: auto !important;
            }
        }
    </style>

    <div class="min-h-screen bg-[#0b0f17] text-gray-100 py-3 px-2 sm:px-6 lg:px-8 w-full max-w-full overflow-x-hidden">

        <div class="max-w-7xl mx-auto space-y-3.5 w-full">

            {{-- 前のページへ戻る --}}
            <div>
                <button type="button"
                    onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('home') }}'"
                    class="inline-flex items-center gap-1.5 bg-[#121824] hover:bg-gray-800 text-gray-300 border border-gray-800 font-bold px-3 py-1.5 rounded-full text-xs transition cursor-pointer shadow-lg">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    前のページへ戻る
                </button>
            </div>

            {{-- 1. 上段：映画情報 ＋ レビュー投稿 --}}
            <div class="movie-review-grid grid grid-cols-1 lg:grid-cols-2 gap-4 items-stretch w-full">

                {{-- 左：映画情報 --}}
                <div
                    class="movie-info-card bg-[#121824] border border-gray-800/90 rounded-2xl p-3.5 sm:p-5 shadow-xl w-full h-full flex flex-col">
                    <div class="movie-info-inner grid grid-cols-1 sm:grid-cols-[180px_minmax(0,1fr)] gap-4 h-full">

                        {{-- ポスター側 --}}
                        <div class="poster-column flex flex-col h-full">
                            <div
                                class="w-full h-[250px] sm:h-[270px] rounded-xl overflow-hidden shadow-2xl border border-gray-700/40 bg-gray-900">
                                <img src="{{ !empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : asset('images/no-poster.png') }}"
                                    alt="{{ $movie['title'] }}" class="w-full h-full object-cover">
                            </div>

                            {{-- 気になる！ボタン --}}
                            <div class="mt-2.5">
                                <form action="{{ route('watchlist.toggle') }}" method="POST" class="w-full">
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
                                        class="w-full py-1.5 px-2 rounded-lg text-[11px] font-extrabold transition duration-200 flex items-center justify-center gap-1.5 border shadow-md cursor-pointer {{ $inWatchlist ? 'bg-amber-500 text-black border-amber-400 hover:bg-amber-400' : 'bg-gray-800/90 text-amber-400 border-amber-500/50 hover:bg-amber-500 hover:text-black' }}">
                                        <i
                                            class="fa-{{ $inWatchlist ? 'solid' : 'regular' }} fa-bookmark text-[10px]"></i>
                                        <span>{{ $inWatchlist ? '気になる！登録中' : '＋ 気になる！' }}</span>
                                    </button>
                                </form>
                            </div>

                            {{-- どこで見れる？ --}}
                            <div class="mt-2">
                                <div
                                    class="bg-[#1a2332] border border-gray-800/80 rounded-lg py-1.5 px-2.5 text-center">
                                    <div
                                        class="text-[10px] text-amber-400 font-bold uppercase tracking-wider mb-0.5 flex items-center justify-center gap-1">
                                        <i class="fa-solid fa-tv text-[9px]"></i>
                                        どこで見れる？
                                    </div>
                                    <div class="text-[10px] sm:text-[11px] font-semibold text-gray-300 leading-tight">
                                        🎬 劇場公開 / 配信準備中
                                    </div>
                                </div>
                            </div>

                            {{-- シェア / TMDB --}}
                            <div class="poster-share grid grid-cols-2 gap-1.5">
                                <a href="https://twitter.com/intent/tweet?text={{ urlencode('『' . $movie['title'] . '』をチェック！ #MovieMood') }}&url={{ urlencode(request()->fullUrl()) }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="bg-gray-800/90 hover:bg-gray-700 border border-gray-700/80 text-gray-300 hover:text-white rounded-lg py-1.5 px-1 text-[11px] font-bold transition flex items-center justify-center gap-1">
                                    <i class="fa-solid fa-share-nodes text-[9px]"></i>
                                    シェア
                                </a>

                                <a href="https://www.themoviedb.org/movie/{{ $movie['id'] }}" target="_blank"
                                    rel="noopener noreferrer"
                                    class="bg-gray-800/90 hover:bg-gray-700 border border-gray-700/80 text-gray-300 hover:text-white rounded-lg py-1.5 px-1 text-[11px] font-bold transition flex items-center justify-center gap-1">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                    TMDB
                                </a>
                            </div>
                        </div>

                        {{-- 映画情報側 --}}
                        <div class="min-w-0 flex flex-col">
                            <div class="space-y-2">
                                <div class="w-full">
                                    <h1
                                        class="text-base sm:text-lg font-bold text-white tracking-tight leading-snug break-words">
                                        {{ $movie['title'] }}
                                    </h1>
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

                            {{-- STORY / あらすじ --}}
                            <div class="movie-story-section mt-3 min-w-0">
                                <h3
                                    class="text-xs sm:text-sm font-bold text-amber-400 uppercase tracking-wider flex items-center gap-1 mb-1.5">
                                    <i class="fa-solid fa-book-open"></i>
                                    STORY / あらすじ
                                </h3>

                                <div
                                    class="movie-story-box bg-[#1a2332] p-3 rounded-xl border border-gray-800/80 custom-scrollbar">
                                    <p class="text-sm text-gray-200 leading-relaxed font-normal">
                                        {{ (!empty($movie['overview']) && trim($movie['overview']) !== '') ? $movie['overview'] : '※日本語あらすじ情報は準備中です。' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 右：レビュー投稿フォーム --}}
                <div
                    class="review-post-card bg-[#121824] border border-gray-800/90 rounded-2xl p-3.5 sm:p-5 shadow-xl flex flex-col w-full h-full">

                    <div class="flex-shrink-0 space-y-2">
                        <h2 class="text-xs sm:text-sm font-bold text-white flex items-center gap-1.5">
                            <i class="fa-solid fa-pen-to-square text-amber-500"></i>
                            この映画のレビュー・メモを投稿する
                        </h2>

                        <div class="grid grid-cols-2 gap-1 bg-[#1a2332] p-1 rounded-xl border border-gray-800">
                            <button type="button" id="tabWatched" onclick="switchStatus('watched')"
                                class="py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 bg-amber-500 text-black shadow-md flex items-center justify-center gap-1 cursor-pointer">
                                🍿 観た（鑑賞後）
                            </button>

                            <button type="button" id="tabWantToWatch" onclick="switchStatus('want_to_watch')"
                                class="py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 text-gray-400 hover:text-white flex items-center justify-center gap-1 cursor-pointer">
                                ✨ 観たい（鑑賞前）
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('reviews.store', ['id' => $movie['id']]) }}" method="POST"
                        class="flex-1 flex flex-col min-h-0">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
                        <input type="hidden" name="movie_title" value="{{ $movie['title'] }}">
                        <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] ?? '' }}">
                        <input type="hidden" name="status" id="review_status" value="{{ old('status', 'watched') }}">

                        <div id="selectedMoodsInputContainer"></div>

                        <div class="space-y-3 mt-3 flex-1 flex flex-col justify-between">

                            {{-- 評価 --}}
                            <div id="ratingSection" class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label for="ratingSlider" id="ratingTitle"
                                        class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                        評価（1.0 〜 10.0）
                                    </label>
                                    <div id="ratingValueWrapper"
                                        class="text-amber-400 font-extrabold text-xs sm:text-sm flex items-center gap-1">
                                        <i id="ratingIcon" class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                                        <span id="ratingValue">{{ number_format(old('rating', 8.0), 1) }}</span>
                                    </div>
                                </div>

                                <input type="range" name="rating" id="ratingSlider" min="1.0" max="10.0" step="0.1"
                                    value="{{ old('rating', '8.0') }}"
                                    class="w-full h-2 rounded-lg appearance-none cursor-pointer focus:outline-none"
                                    oninput="updateRatingDisplay(this)">

                                <div class="bg-[#1a2332] border border-gray-800/80 rounded-lg px-2.5 py-1 text-center">
                                    <span id="ratingLabel" class="text-[11px] font-bold text-amber-400">
                                        ✨ 超おすすめ！観て後悔なし
                                    </span>
                                </div>
                            </div>

                            {{-- 気分タグ --}}
                            <div id="moodTagSection" class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <span id="moodTagLabel"
                                        class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                        観たあとの気分タグ（最大4つ選択可）
                                    </span>
                                    <span class="text-[10px] text-gray-500 font-bold" id="tagCountText">0/4</span>
                                </div>
                                <div id="moodTagContainer" class="flex flex-wrap gap-1.5 pt-1"></div>
                            </div>

                            {{-- コメント --}}
                            <div class="space-y-1">
                                <label for="comment" id="commentLabel"
                                    class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    レビュー感想コメント
                                </label>
                                <textarea id="comment" name="comment" required placeholder="この映画を観た感想や見どころを書いてみましょう..."
                                    class="w-full h-24 bg-[#1a2332] border border-gray-700/80 rounded-xl p-2.5 text-xs text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none leading-relaxed resize-none custom-scrollbar">{{ old('comment') }}</textarea>
                            </div>

                            {{-- 投稿ボタン --}}
                            <div class="mt-auto pt-3">
                                <button type="submit" id="submitBtn"
                                    class="w-full bg-amber-500 hover:bg-amber-400 text-black font-extrabold px-4 py-2.5 rounded-xl text-xs sm:text-sm transition shadow-lg flex items-center justify-center gap-1.5 cursor-pointer">
                                    <i class="fa-solid fa-paper-plane text-xs"></i>
                                    <span id="submitBtnText">レビューを投稿する</span>
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            {{-- 2. レビュー一覧（コミュニティ仕様完全統一） --}}
            <div id="reviews" class="space-y-4 w-full pt-3 border-t border-gray-800 scroll-mt-6">

                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-comments text-amber-500"></i>
                        <h2 class="text-sm sm:text-base font-extrabold text-white">
                            みんなのレビュー
                        </h2>
                        <span
                            class="bg-gray-800 border border-gray-700 text-gray-300 px-2 py-0.5 rounded-full text-[10px] font-bold">
                            {{ count($reviews) }}件
                        </span>
                    </div>

                    @if(count($reviews) > 0)
                        <span class="text-[10px] text-gray-500 hidden sm:inline">
                            この映画への感想・期待メモ
                        </span>
                    @endif
                </div>

                {{-- レビューカード (2列グリッド) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">

                    @forelse($reviews as $review)

                        @php
                            $user = $review->user;

                            $avatarUrl = $user->avatar_url
                                ?? $user->avatar
                                ?? $user->profile_photo_url
                                ?? null;

                            if ($avatarUrl) {
                                $avatarSrc = preg_match('/^https?:\/\//', $avatarUrl)
                                    ? $avatarUrl
                                    : asset($avatarUrl);
                            } else {
                                $avatarSrc = null;
                            }

                            $statusVal = strtolower(trim($review->status ?? ''));
                            $moodVal = $review->mood ?? $review->moods ?? '';
                            $moodStr = is_array($moodVal) ? implode(',', $moodVal) : (string) $moodVal;

                            $isWantToWatch = in_array($statusVal, ['want_to_watch', 'want', 'want-to-watch', '1', 'before'])
                                || str_contains($moodStr, 'しそう');

                            $moodList = is_array($moodVal) ? $moodVal : explode(',', $moodStr);

                            $likes = $review->likes ?? collect();
                            $comments = $review->comments ?? collect();

                            $likesCount = $likes->count();
                            $commentsCount = $comments->count();

                            $isLikedByMe = auth()->check() && $likes->contains('user_id', auth()->id());
                            $hasCommentedByMe = auth()->check() && $comments->contains('user_id', auth()->id());
                        @endphp

                        <div id="review-{{ $review->id }}"
                            class="bg-[#121824] border border-gray-800/90 rounded-2xl p-4 sm:p-5 shadow-xl space-y-3 w-full flex flex-col scroll-mt-6 transition-all duration-300">

                            {{-- ユーザー・評価・本文 --}}
                            <div class="space-y-3">

                                {{-- ユーザー ＋ 評価バッジ --}}
                                <div class="flex items-center justify-between border-b border-gray-800/60 pb-2.5">

                                    <div class="flex items-center gap-2.5 min-w-0">

                                        {{-- アイコン画像あり / なし（イニシャル中央揃え修正） --}}
                                        @if($avatarSrc)
                                            <img src="{{ $avatarSrc }}" alt="{{ $user->nickname ?? $user->name ?? 'User' }}"
                                                class="review-avatar {{ $isWantToWatch ? 'border border-purple-500/40' : 'border border-amber-500/40' }}">
                                        @else
                                            <div
                                                class="review-avatar {{ $isWantToWatch ? 'bg-purple-500/20 border border-purple-500/40 text-purple-300' : 'bg-amber-500/20 border border-amber-500/40 text-amber-400' }} flex items-center justify-center font-black text-xs leading-none select-none text-center">
                                                <span>{{ mb_substr($user->nickname ?? $user->name ?? '匿', 0, 1) }}</span>
                                            </div>
                                        @endif

                                        <div class="min-w-0">
                                            <span
                                                class="font-bold text-xs sm:text-sm text-gray-200 block truncate leading-tight">
                                                {{ $user->nickname ?? $user->name ?? '匿名ユーザー' }}
                                            </span>
                                            <span class="text-[10px] text-gray-500">
                                                {{ $review->created_at ? $review->created_at->diffForHumans() : '' }}
                                            </span>
                                        </div>

                                    </div>

                                    {{-- スコア・期待度バッジ（2枚目と同じ配色デザイン） --}}
                                    <div class="flex items-center gap-1.5 flex-shrink-0">
                                        @if($isWantToWatch)
                                            <span
                                                class="bg-purple-500/15 border border-purple-500/40 text-purple-300 px-2.5 py-0.5 rounded-full text-[11px] font-black flex items-center gap-1 shadow-sm">
                                                ✨ 期待度: {{ number_format((float) ($review->rating ?? 0), 1) }}
                                            </span>
                                        @else
                                            <div
                                                class="bg-amber-500/15 border border-amber-500/40 text-amber-400 px-2.5 py-0.5 rounded-full text-xs font-black flex items-center gap-1 shadow-sm">
                                                <i class="fa-solid fa-star text-[10px]"></i>
                                                <span>{{ number_format((float) ($review->rating ?? 0), 1) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                </div>

                                {{-- 気分タグ --}}
                                @if(!empty(array_filter($moodList)))
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($moodList as $m)
                                            @php
                                                $cleanTag = trim(str_replace('#', '', $m));
                                                $isPurpleTag = $isWantToWatch || str_contains($cleanTag, 'しそう');
                                            @endphp
                                            @if($cleanTag)
                                                <span
                                                    class="{{ $isPurpleTag ? 'bg-purple-500/15 text-purple-200 border-purple-500/40' : 'bg-amber-500/15 text-amber-300 border-amber-500/40' }} border text-[11px] px-2.5 py-0.5 rounded-md font-bold shadow-sm">
                                                    #{{ $cleanTag }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                {{-- レビュー本文 --}}
                                <p
                                    class="text-xs sm:text-sm text-gray-200 leading-relaxed font-normal whitespace-pre-wrap break-words py-1">
                                    {{ $review->comment ?? $review->content ?? '' }}
                                </p>

                            </div>

                            {{-- いいね・コメント操作（コミュニティデザイン完全同期） --}}
                            <div x-data="{ openComments: window.location.hash === '#review-{{ $review->id }}-comments' }"
                                class="pt-3 border-t border-gray-800/80 mt-auto">

                                <div class="flex items-center justify-between w-full">

                                    {{-- 左側：いいね（自分の投稿には押せない ＆ 1件以上でピンク着色） --}}
                                    @auth
                                        @if(auth()->id() === (int) $review->user_id)
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-bold cursor-not-allowed opacity-80 {{ $likesCount > 0 ? 'bg-pink-500/20 border-pink-500/50 text-pink-400' : 'bg-gray-800/60 border-gray-700/60 text-gray-400' }}"
                                                title="自分の投稿にはいいねできません">
                                                <i
                                                    class="{{ $likesCount > 0 ? 'fa-solid' : 'fa-regular' }} fa-heart text-xs text-pink-500"></i>
                                                <span>{{ $likesCount }}</span>
                                            </div>
                                        @else
                                            <form action="{{ route('reviews.like', $review->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-bold transition cursor-pointer {{ ($isLikedByMe || $likesCount > 0) ? 'bg-pink-500/20 border-pink-500/50 text-pink-400 hover:bg-pink-500/30' : 'bg-gray-800/60 border-gray-700/60 text-gray-400 hover:text-pink-400 hover:border-pink-500/40' }}">
                                                    <i
                                                        class="{{ ($isLikedByMe || $likesCount > 0) ? 'fa-solid' : 'fa-regular' }} fa-heart text-xs text-pink-500"></i>
                                                    <span>{{ $likesCount }}</span>
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-bold transition {{ $likesCount > 0 ? 'bg-pink-500/20 border-pink-500/50 text-pink-400' : 'bg-gray-800/60 border-gray-700/60 text-gray-400 hover:text-pink-400' }}">
                                            <i
                                                class="{{ $likesCount > 0 ? 'fa-solid' : 'fa-regular' }} fa-heart text-xs text-pink-500"></i>
                                            <span>{{ $likesCount }}</span>
                                        </a>
                                    @endauth

                                    {{-- 右側：コメント（1件以上でゴールド着色） --}}
                                    <button type="button" @click="openComments = !openComments"
                                        :aria-expanded="openComments.toString()"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-bold transition cursor-pointer {{ $commentsCount > 0 ? 'bg-amber-500/20 border-amber-500/50 text-amber-400 hover:bg-amber-500/30' : 'bg-gray-800/60 border-gray-700/60 text-gray-300 hover:text-amber-400 hover:border-amber-500/50' }}">
                                        <i
                                            class="fa-regular fa-comment text-xs {{ $commentsCount > 0 ? 'text-amber-400' : 'text-gray-400' }}"></i>
                                        <span>{{ $commentsCount }}件</span>
                                        <i class="fa-solid fa-chevron-down text-[9px] transition-transform"
                                            :class="{ 'rotate-180': openComments }">
                                        </i>
                                    </button>

                                </div>

                                {{-- コメント表示・投稿エリア --}}
                                <div id="review-{{ $review->id }}-comments" x-show="openComments" x-cloak x-transition
                                    class="mt-3 space-y-3">

                                    {{-- 既存コメント一覧 --}}
                                    @forelse($comments as $comment)
                                        <div class="bg-gray-800/40 border border-gray-700/40 p-2.5 rounded-lg text-xs"
                                            x-data="{ editing: false, content: {{ Illuminate\Support\Js::from($comment->comment ?? '') }} }">

                                            {{-- コメント表示 --}}
                                            <div x-show="!editing" class="flex justify-between items-start gap-3">
                                                <div class="min-w-0 flex-1 break-words">
                                                    <span class="font-semibold text-amber-400">
                                                        {{ $comment->user->nickname ?? $comment->user->name ?? 'ユーザー' }}:
                                                    </span>
                                                    <span class="text-gray-200 ml-1 whitespace-pre-wrap"
                                                        x-text="content"></span>
                                                    @if($comment->created_at)
                                                        <span class="block text-[10px] text-gray-500 mt-1">
                                                            {{ $comment->created_at->diffForHumans() }}
                                                        </span>
                                                    @endif
                                                </div>

                                                {{-- 自分のコメント編集・削除 --}}
                                                @if(auth()->check() && (int) auth()->id() === (int) $comment->user_id)
                                                    <div class="flex items-center gap-2 shrink-0">
                                                        <button type="button" @click="editing = true"
                                                            class="text-amber-400 hover:text-amber-300" aria-label="コメントを編集">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                        <form action="{{ route('reviews.comments.destroy', $comment->id) }}"
                                                            method="POST" onsubmit="return confirm('このコメントを削除してもよろしいですか？');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-400 hover:text-red-300"
                                                                aria-label="コメントを削除">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- コメント編集フォーム --}}
                                            @if(auth()->check() && (int) auth()->id() === (int) $comment->user_id)
                                                <div x-show="editing" x-cloak class="mt-2">
                                                    <form action="{{ route('reviews.comments.update', $comment->id) }}"
                                                        method="POST" class="space-y-2">
                                                        @csrf
                                                        @method('PUT')
                                                        <textarea name="comment" x-model="content" required maxlength="1000"
                                                            rows="2"
                                                            class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-amber-500 resize-y"></textarea>
                                                        <div class="flex justify-end gap-2">
                                                            <button type="button"
                                                                @click="editing = false; content = {{ Illuminate\Support\Js::from($comment->comment ?? '') }}"
                                                                class="rounded-lg border border-gray-600 bg-gray-700 px-3 py-1.5 text-xs font-bold text-gray-200 hover:bg-gray-600">
                                                                キャンセル
                                                            </button>
                                                            <button type="submit"
                                                                class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-bold text-black hover:bg-amber-400">
                                                                <i class="fa-solid fa-check mr-1"></i>保存
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="text-[11px] text-gray-500 py-1">まだコメントはありません。最初のコメントを投稿してみましょう！</p>
                                    @endforelse

                                    {{-- 新規コメント入力フォーム --}}
                                    @auth
                                        <form action="{{ route('reviews.comments.store', $review->id) }}" method="POST"
                                            class="space-y-2">
                                            @csrf
                                            <textarea name="comment" required maxlength="1000" rows="2"
                                                placeholder="このレビューにコメントする..."
                                                class="w-full bg-[#1a2332] border border-gray-700/80 rounded-xl p-2.5 text-xs text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none resize-y"></textarea>
                                            <div class="flex justify-end">
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-400 text-black font-bold px-3 py-1.5 rounded-lg text-xs transition">
                                                    <i class="fa-solid fa-paper-plane"></i>コメントを投稿
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <p class="text-[11px] text-gray-500">コメントを投稿するには<a href="{{ route('login') }}"
                                                class="text-amber-400 hover:text-amber-300 underline">ログイン</a>してください。</p>
                                    @endauth
                                </div>

                            </div>

                        </div>

                    @empty

                        <div
                            class="col-span-full bg-[#121824] border border-gray-800 rounded-2xl p-8 text-center text-gray-400">
                            <p class="text-xs sm:text-sm font-semibold">まだレビューが投稿されていません。</p>
                            <p class="text-[11px] text-gray-500 mt-1">最初のレビューを投稿してみましょう！</p>
                        </div>

                    @endforelse

                </div>

            </div>
        </div>
    </div>

    {{-- JavaScript：通知遷移スクロール ＆ 黄金発光アニメーション --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hash = window.location.hash;
            if (hash) {
                const targetElement = document.querySelector(hash);
                if (targetElement) {
                    setTimeout(function () {
                        targetElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        targetElement.classList.add('review-target-highlight');
                    }, 300);
                }
            }
        });
    </script>
</x-app-layout>