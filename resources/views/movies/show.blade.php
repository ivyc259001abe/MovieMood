<x-app-layout>

    <style>
        /* =========================================================
           MovieMood 共通カラー
        ========================================================= */

        :root {
            --mood-gold: #f59e0b;
            --mood-gold-light: #fbbf24;
            --mood-purple: #a855f7;
            --mood-purple-light: #c084fc;
        }

        /* =========================================================
           スライダー
        ========================================================= */

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
        }

        /* =========================================================
           カスタムスクロールバー
        ========================================================= */

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

        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #374151 #0b0e14;
        }

        
/* =========================================================
   レビューのハイライト
========================================================= */

/* 移動先のレビューがヘッダーに隠れないようにする */
[id^="review-"] {
    scroll-margin-top: 110px;
}

/* レビューカード全体を金色に光らせる */
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

/* 対象のレビューカードだけにアニメーションを適用 */
[id^="review-"].review-target-highlight {
    animation: review-gold-glow 3.5s ease-in-out;
    position: relative;
    z-index: 10;
}

        /* =========================================================
           ユーザーアイコン
        ========================================================= */

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
            display: block;
            flex-shrink: 0;
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

        
/* =========================================================
   PC表示：左右のカードの高さを揃える
========================================================= */

@media (min-width: 1024px) {

    /* 左右のカードを同じ行の高さに合わせる */
    .movie-review-grid {
        align-items: stretch;
    }

    /* 左右のカード */
    .movie-info-card,
    .review-post-card {
        align-self: stretch;
        min-width: 0;
        min-height: 0;
        height: auto;
        box-sizing: border-box;
    }

    /* 左側の映画情報カードを縦方向に伸ばす */
    .movie-info-card {
        display: flex;
        flex-direction: column;
    }

    /* ポスターと映画情報を横並びにする */
    .movie-info-inner {
        display: grid;
        grid-template-columns: 180px minmax(0, 1fr);
        align-items: stretch;
        flex: 1 1 auto;
        min-width: 0;
        min-height: 0;
        height: auto;
    }

    /* ポスター側 */
    .poster-column {
        display: flex;
        flex-direction: column;
        align-self: stretch;
        min-width: 0;
        min-height: 0;
        height: 100%;
    }

    /* シェア・TMDBボタン */
    .poster-share {
        margin-top: 8px;
    }

    /* 映画情報側 */
    .movie-info-inner > div:last-child {
        display: flex;
        flex-direction: column;
        min-width: 0;
        min-height: 0;
    }

    /* あらすじセクション */
    .movie-story-section {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-width: 0;
        min-height: 0;
    }

    /* 右側のレビュー投稿カード */
    .review-post-card {
        display: flex;
        flex-direction: column;
    }

    /* レビュー投稿フォームをカード内で伸ばす */
    .review-post-card > form {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
    }
}

/* =========================================================
   あらすじ：画面幅に関係なく高さを固定してスクロール
========================================================= */

.movie-story-box {
    height: 192px;
    min-height: 192px;
    max-height: 192px;
    flex: 0 0 192px;
    overflow-x: hidden;
    overflow-y: auto;
    box-sizing: border-box;
    padding-right: 8px;
    scrollbar-width: thin;
    scrollbar-color: #475569 #1a2332;
}

/* Chrome・Edge用スクロールバー */
.movie-story-box::-webkit-scrollbar {
    width: 6px;
}

.movie-story-box::-webkit-scrollbar-track {
    background: #1a2332;
    border-radius: 8px;
}

.movie-story-box::-webkit-scrollbar-thumb {
    background: #475569;
    border-radius: 8px;
}

.movie-story-box::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}```html
<style>
    /* =========================================================
       MovieMood 共通カラー
    ========================================================= */

    :root {
        --mood-gold: #f59e0b;
        --mood-gold-light: #fbbf24;
        --mood-purple: #a855f7;
        --mood-purple-light: #c084fc;
    }

    /* =========================================================
       評価スライダー
    ========================================================= */

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
    }

    /* =========================================================
       共通スクロールバー
    ========================================================= */

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

    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #374151 #0b0e14;
    }

    /* =========================================================
       レビューへの移動・ハイライト
    ========================================================= */

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

    /* =========================================================
       ユーザーアイコン
    ========================================================= */

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
        display: block;
        flex-shrink: 0;
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

    /* =========================================================
       あらすじ
       画面幅に関係なく高さを192pxに固定し、
       長い文章はボックス内でスクロールする
    ========================================================= */

    .movie-story-box {
        height: 192px;
        min-height: 192px;
        max-height: 192px;
        flex: 0 0 192px;
        overflow-x: hidden;
        overflow-y: auto;
        box-sizing: border-box;
        padding-right: 8px;
        scrollbar-width: thin;
        scrollbar-color: #475569 #1a2332;
    }

    .movie-story-box::-webkit-scrollbar {
        width: 6px;
    }

    .movie-story-box::-webkit-scrollbar-track {
        background: #1a2332;
        border-radius: 8px;
    }

    .movie-story-box::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 8px;
    }

    .movie-story-box::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    /* =========================================================
       PC表示：左右のカードの高さを揃える
    ========================================================= */

    @media (min-width: 1024px) {

        /* 左右のカードを同じ行の高さに揃える */
        .movie-review-grid {
            align-items: stretch;
        }

        .movie-info-card,
        .review-post-card {
            align-self: stretch;
            min-width: 0;
            min-height: 0;
            box-sizing: border-box;
        }

        /* 左側の映画情報カード */
        .movie-info-card {
            display: flex;
            flex-direction: column;
        }

        .movie-info-inner {
            display: grid;
            grid-template-columns: 180px minmax(0, 1fr);
            align-items: stretch;
            flex: 1 1 auto;
            min-width: 0;
            min-height: 0;
        }

        /* ポスター・シェア・TMDB */
        .poster-column {
            display: flex;
            flex-direction: column;
            align-self: stretch;
            min-width: 0;
            min-height: 0;
        }

        .poster-share {
            margin-top: 8px;
        }

        /* 右側の映画情報とあらすじ */
        .movie-info-inner > div:last-child {
            display: flex;
            flex-direction: column;
            min-width: 0;
            min-height: 0;
        }

        .movie-story-section {
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
            min-width: 0;
            min-height: 0;
        }

        /* 右側のレビュー投稿カード */
        .review-post-card {
            display: flex;
            flex-direction: column;
        }

        .review-post-card > form {
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
            min-height: 0;
        }
    }
</style>
```

/* =========================================================
   PC表示：左右のカードの高さを揃える
========================================================= */

@media (min-width: 1024px) {
    .movie-review-grid {
        align-items: stretch;
    }

    .movie-info-card,
    .review-post-card {
        height: 100%;
        min-height: 0;
    }

    .movie-info-inner {
        flex: 1 1 auto;
        height: 100%;
        min-height: 0;
    }

    .poster-column {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .poster-share {
        margin-top: 8px;
    }

    .movie-story-section {
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
    }

    .movie-info-inner > div:last-child {
        min-height: 0;
    }
}

            
/* あらすじ：高さを制限し、長い文章はスクロール */
.movie-story-box {
    height: 192px;
    max-height: 192px;
    min-height: 0;
    overflow-y: auto;
    overflow-x: hidden;
    flex: 0 0 192px;
    padding-right: 8px;
    box-sizing: border-box;
    scrollbar-width: thin;
    scrollbar-color: #475569 #121824;
}

/* Chrome・Edge用スクロールバー */
.movie-story-box::-webkit-scrollbar {
    width: 6px;
}

.movie-story-box::-webkit-scrollbar-track {
    background: #121824;
    border-radius: 8px;
}

.movie-story-box::-webkit-scrollbar-thumb {
    background: #475569;
    border-radius: 8px;
}

.movie-story-box::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}
        }
    </style>

    <div class="min-h-screen bg-[#0b0f17] text-gray-100 py-3 px-2 sm:px-6 lg:px-8 w-full max-w-full overflow-x-hidden">

        <div class="max-w-7xl mx-auto space-y-3.5 w-full">

            {{-- 前のページへ戻る --}}
            <div>
                <button
                    type="button"
                    onclick="goBackOrHome()"
                    class="inline-flex items-center gap-1.5 bg-[#121824] hover:bg-gray-800 text-gray-300 border border-gray-800 font-bold px-3 py-1.5 rounded-full text-xs transition cursor-pointer shadow-lg">

                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    前のページへ戻る
                </button>
            </div>

            {{-- =====================================================
                 1. 上段：映画情報 ＋ レビュー投稿
            ====================================================== --}}

            <div class="movie-review-grid grid grid-cols-1 lg:grid-cols-2 gap-4 items-stretch w-full">

                {{-- =================================================
                     左：映画情報
                ================================================== --}}

                <div class="movie-info-card bg-[#121824] border border-gray-800/90 rounded-2xl p-3.5 sm:p-5 shadow-xl w-full h-full flex flex-col">

                    <div class="movie-info-inner grid grid-cols-1 sm:grid-cols-[180px_minmax(0,1fr)] gap-4 h-full">

                        {{-- ポスター側 --}}
                        <div class="poster-column flex flex-col h-full">

                            {{-- ポスター --}}
                            <div class="w-full h-[250px] sm:h-[270px] rounded-xl overflow-hidden shadow-2xl border border-gray-700/40 bg-gray-900">

                                <img
                                    src="{{ !empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : asset('images/no-poster.png') }}"
                                    alt="{{ $movie['title'] }}"
                                    class="w-full h-full object-cover">

                            </div>

                            {{-- どこで見れる？ --}}
                            <div class="mt-2.5">
                                <div class="bg-[#1a2332] border border-gray-800/80 rounded-lg py-2 px-2.5 text-center">

                                    <div class="text-[10px] text-amber-400 font-bold uppercase tracking-wider mb-0.5 flex items-center justify-center gap-1">
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

                                <a
                                    href="https://twitter.com/intent/tweet?text={{ urlencode('『' . $movie['title'] . '』をチェック！ #MovieMood') }}&url={{ urlencode(request()->fullUrl()) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="bg-gray-800/90 hover:bg-gray-700 border border-gray-700/80 text-gray-300 hover:text-white rounded-lg py-2 px-1 text-[11px] font-bold transition flex items-center justify-center gap-1">

                                    <i class="fa-solid fa-share-nodes text-[9px]"></i>
                                    シェア
                                </a>

                                <a
                                    href="https://www.themoviedb.org/movie/{{ $movie['id'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="bg-gray-800/90 hover:bg-gray-700 border border-gray-700/80 text-gray-300 hover:text-white rounded-lg py-2 px-1 text-[11px] font-bold transition flex items-center justify-center gap-1">

                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                    TMDB
                                </a>

                            </div>

                        </div>

                        {{-- 映画情報側 --}}
                        <div class="min-w-0 flex flex-col">

                            {{-- 上部情報 --}}
                            <div class="space-y-2">

                                {{-- タイトル ＋ 気になる --}}
                                <div class="flex items-start justify-between gap-2">

                                    <h1 class="text-base sm:text-lg font-bold text-white tracking-tight leading-snug min-w-0">
                                        {{ $movie['title'] }}
                                    </h1>

                                    {{-- 気になる --}}
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
                                                    $inWatchlist = $user
                                                        ->watchlists()
                                                        ->where('movie_id', $movie['id'])
                                                        ->exists();
                                                } elseif (method_exists($user, 'watchlist')) {
                                                    $inWatchlist = $user
                                                        ->watchlist()
                                                        ->where('movie_id', $movie['id'])
                                                        ->exists();
                                                }
                                            }
                                        @endphp

                                        <button
                                            type="submit"
                                            class="py-1 px-2.5 rounded-full text-[11px] font-bold transition duration-200 flex items-center gap-1 border shadow-sm bg-amber-500 text-black border-amber-400 hover:bg-amber-400">

                                            <i class="fa-{{ $inWatchlist ? 'solid' : 'regular' }} fa-bookmark text-[10px]"></i>

                                            <span>
                                                {{ $inWatchlist ? '登録中' : '＋ 気になる！' }}
                                            </span>
                                        </button>

                                    </form>

                                </div>

                                {{-- TMDB / 公開日 / runtime --}}
                                <div class="flex flex-wrap items-center gap-1.5 pt-0.5">

                                    <span class="bg-amber-500/10 border border-amber-500/30 text-amber-400 px-2 py-0.5 rounded-full text-[11px] font-extrabold flex items-center gap-1">
                                        <i class="fa-solid fa-star text-amber-400 text-[9px]"></i>
                                        TMDB: {{ number_format($movie['vote_average'] ?? 0, 1) }}
                                    </span>

                                    <span class="bg-gray-800 border border-gray-700/80 text-gray-300 text-[11px] px-2 py-0.5 rounded-full">
                                        📅 {{ isset($movie['release_date']) ? substr($movie['release_date'], 0, 7) : '未定' }}
                                    </span>

                                    @if(!empty($movie['runtime']))
                                        <span class="bg-gray-800 border border-gray-700/80 text-gray-300 text-[11px] px-2 py-0.5 rounded-full">
                                            ⏰ {{ $movie['runtime'] }}分
                                        </span>
                                    @endif

                                </div>

                                {{-- 監督 --}}
                                @if(!empty($movie['director']))
                                    <div class="text-[11px] text-gray-400 truncate">
                                        🎬 監督:
                                        <span class="text-gray-200 font-semibold">
                                            {{ $movie['director'] }}
                                        </span>
                                    </div>
                                @endif

                                {{-- ジャンル --}}
                                @if(!empty($movie['genres']) && is_array($movie['genres']))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($movie['genres'] as $genre)
                                            <span class="text-[10px] bg-gray-800/90 text-gray-300 px-2 py-0.5 rounded border border-gray-700/60">
                                                {{ is_array($genre) ? ($genre['name'] ?? '') : $genre }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                            </div>

                            {{-- STORY / あらすじ --}}
                            <div class="movie-story-section mt-3 min-w-0">

                                <h3 class="text-xs sm:text-sm font-bold text-amber-400 uppercase tracking-wider flex items-center gap-1 mb-1.5">
                                    <i class="fa-solid fa-book-open"></i>
                                    STORY / あらすじ
                                </h3>

                                <div class="movie-story-box bg-[#1a2332] p-3 rounded-xl border border-gray-800/80 overflow-y-auto custom-scrollbar">

                                    <p class="text-sm text-gray-200 leading-relaxed font-normal">
                                        {{ (!empty($movie['overview']) && trim($movie['overview']) !== '') ? $movie['overview'] : '※日本語あらすじ情報は準備中です。' }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- =================================================
                     右：レビュー投稿フォーム
                ================================================== --}}

                <div class="review-post-card bg-[#121824] border border-gray-800/90 rounded-2xl p-3.5 sm:p-5 shadow-xl flex flex-col w-full h-full">

                    <div class="flex-shrink-0 space-y-2">

                        <h2 class="text-xs sm:text-sm font-bold text-white flex items-center gap-1.5">
                            <i class="fa-solid fa-pen-to-square text-amber-500"></i>
                            この映画のレビュー・メモを投稿する
                        </h2>

                        {{-- タブ --}}
                        <div class="grid grid-cols-2 gap-1 bg-[#1a2332] p-1 rounded-xl border border-gray-800">

                            <button
                                type="button"
                                id="tabWatched"
                                onclick="switchStatus('watched')"
                                class="py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 bg-amber-500 text-black shadow-md flex items-center justify-center gap-1 cursor-pointer">
                                🍿 観た（鑑賞後）
                            </button>

                            <button
                                type="button"
                                id="tabWantToWatch"
                                onclick="switchStatus('want_to_watch')"
                                class="py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 text-gray-400 hover:text-white flex items-center justify-center gap-1 cursor-pointer">
                                ✨ 観たい（鑑賞前）
                            </button>

                        </div>

                    </div>

                    <form
                        action="{{ route('reviews.store', ['id' => $movie['id']]) }}"
                        method="POST"
                        class="flex-1 flex flex-col min-h-0">

                        @csrf

                        <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
                        <input type="hidden" name="movie_title" value="{{ $movie['title'] }}">
                        <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] ?? '' }}">
                        <input type="hidden" name="status" id="review_status" value="{{ old('status', 'watched') }}">

                        <div id="selectedMoodsInputContainer"></div>

                        <div class="space-y-3 mt-3">

                            {{-- 評価 --}}
                            <div id="ratingSection" class="space-y-1.5">

                                <div class="flex items-center justify-between">

                                    <label
                                        for="ratingSlider"
                                        id="ratingTitle"
                                        class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                        評価（1.0 〜 10.0）
                                    </label>

                                    <div id="ratingValueWrapper" class="text-amber-400 font-extrabold text-xs sm:text-sm flex items-center gap-1">
                                        <i id="ratingIcon" class="fa-solid fa-star text-amber-400 text-[10px]"></i>

                                        <span id="ratingValue">
                                            {{ number_format(old('rating', 8.0), 1) }}
                                        </span>
                                    </div>

                                </div>

                                <input
                                    type="range"
                                    name="rating"
                                    id="ratingSlider"
                                    min="1.0"
                                    max="10.0"
                                    step="0.1"
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

                                    <span id="moodTagLabel" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                        観たあとの気分タグ（最大4つ選択可）
                                    </span>

                                    <span class="text-[10px] text-gray-500 font-bold" id="tagCountText">
                                        0/4
                                    </span>

                                </div>

                                <div id="moodTagContainer" class="flex flex-wrap gap-1.5 pt-1"></div>

                            </div>

                            {{-- コメント --}}
                            <div class="space-y-1">

                                <label
                                    for="comment"
                                    id="commentLabel"
                                    class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    レビュー感想コメント
                                </label>

                                <textarea
                                    id="comment"
                                    name="comment"
                                    required
                                    placeholder="この映画を観た感想や見どころを書いてみましょう..."
                                    class="w-full h-24 bg-[#1a2332] border border-gray-700/80 rounded-xl p-2.5 text-xs text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none leading-relaxed resize-none custom-scrollbar">{{ old('comment') }}</textarea>

                            </div>

                        </div>

                        {{-- 投稿ボタン --}}
                        <div class="mt-auto pt-3">

                            <button
                                type="submit"
                                id="submitBtn"
                                class="w-full bg-amber-500 hover:bg-amber-400 text-black font-extrabold px-4 py-2.5 rounded-xl text-xs sm:text-sm transition shadow-lg flex items-center justify-center gap-1.5 cursor-pointer">

                                <i class="fa-solid fa-paper-plane text-xs"></i>

                                <span id="submitBtnText">
                                    レビューを投稿する
                                </span>

                            </button>

                        </div>

                    </form>

                </div>

            </div>

            {{-- =====================================================
                 2. レビュー一覧
            ====================================================== --}}

            <div id="reviews" class="space-y-4 w-full pt-3 border-t border-gray-800 scroll-mt-6">

                <div class="flex items-center justify-between px-1">

                    <div class="flex items-center gap-2">

                        <i class="fa-solid fa-comments text-amber-500"></i>

                        <h2 class="text-sm sm:text-base font-extrabold text-white">
                            みんなのレビュー
                        </h2>

                        <span class="bg-gray-800 border border-gray-700 text-gray-300 px-2 py-0.5 rounded-full text-[10px] font-bold">
                            {{ count($reviews) }}件
                        </span>

                    </div>

                    @if(count($reviews) > 0)
                        <span class="text-[10px] text-gray-500 hidden sm:inline">
                            この映画への感想・期待メモ
                        </span>
                    @endif

                </div>

                {{-- レビューカード --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">

                    @forelse($reviews as $review)

                        @php
                            $user = $review->user;

                            $avatarUrl =
                                $user->avatar_url
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

                            $moodVal = is_object($review)
                                ? ($review->mood ?? $review->moods ?? '')
                                : '';

                            $moodStr = is_array($moodVal)
                                ? implode(',', $moodVal)
                                : (string) $moodVal;

                            $isWantToWatch =
                                in_array(
                                    $statusVal,
                                    ['want_to_watch', 'want', 'want-to-watch', '1', 'before']
                                )
                                || str_contains($moodStr, 'しそう');
                        @endphp

                        <div
                            id="review-{{ $review->id }}"
                            class="bg-[#121824] border border-gray-800 rounded-2xl p-3.5 sm:p-5 shadow-xl space-y-3 w-full flex flex-col justify-between scroll-mt-6 transition-all duration-300">

                            <div class="space-y-2">

                                {{-- ユーザー ＋ 評価 --}}
                                <div class="flex items-center justify-between border-b border-gray-800 pb-2">

                                    <div class="flex items-center gap-2 min-w-0">

                                        @if($avatarSrc)

                                            <img
                                                src="{{ $avatarSrc }}"
                                                alt="{{ $user->nickname ?? $user->name ?? 'User' }}"
                                                class="review-avatar {{ $isWantToWatch ? 'border border-purple-500/40' : 'border border-amber-500/40' }}">

                                        @else

                                            <div class="review-avatar {{ $isWantToWatch ? 'bg-purple-500/20 border border-purple-500/40 text-purple-300' : 'bg-amber-500/20 border border-amber-500/40 text-amber-400' }} flex items-center justify-center font-bold text-xs">
                                                {{ mb_substr($user->nickname ?? $user->name ?? '匿', 0, 1) }}
                                            </div>

                                        @endif

                                        <div class="min-w-0">

                                            <span class="font-bold text-xs sm:text-sm text-gray-200 block truncate">
                                                {{ $user->nickname ?? $user->name ?? '匿名ユーザー' }}
                                            </span>

                                            <span class="text-[9px] sm:text-[10px] text-gray-500">
                                                {{ $review->created_at ? $review->created_at->diffForHumans() : '' }}
                                            </span>

                                        </div>

                                    </div>

                                    {{-- スコア --}}
                                    <div class="flex items-center gap-1.5 flex-shrink-0">

                                        @if($isWantToWatch)

                                            <span class="bg-purple-500/20 border border-purple-500/40 text-purple-300 px-2 py-0.5 rounded-full text-[10px] font-bold flex items-center gap-1">
                                                ✨ 期待度:
                                                {{ number_format($review->rating ?? 0, 1) }}
                                            </span>

                                        @else

                                            <div class="bg-amber-500/10 border border-amber-500/30 text-amber-400 px-2 py-0.5 rounded-full text-xs font-extrabold flex items-center gap-1">
                                                <i class="fa-solid fa-star text-[9px]"></i>
                                                <span>{{ number_format($review->rating ?? 0, 1) }}</span>
                                            </div>

                                        @endif

                                    </div>

                                </div>

                                {{-- 気分タグ --}}
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

                                                $isPurpleTag =
                                                    $isWantToWatch
                                                    || str_contains($cleanTag, 'しそう');
                                            @endphp

                                            @if($cleanTag)
                                                <span class="{{ $isPurpleTag ? 'bg-purple-500/20 text-purple-300 border-purple-500/40' : 'bg-amber-500/20 text-amber-300 border-amber-500/40' }} border text-[10px] sm:text-[11px] px-2 py-0.5 rounded-md font-semibold">
                                                    #{{ $cleanTag }}
                                                </span>
                                            @endif

                                        @endforeach

                                    </div>

                                @endif

                                {{-- レビュー本文 --}}
                                <p class="text-xs sm:text-sm text-gray-200 leading-relaxed bg-[#1a2332] p-2.5 sm:p-3 rounded-xl border border-gray-800/80 whitespace-pre-wrap">
                                    {{ $review->comment }}
                                </p>

                            </div>

                            {{-- 自分のレビューだけ編集・削除を表示 --}}
                            @auth
                                @if((int) auth()->id() === (int) $review->user_id)

                                    <div class="flex items-center justify-end gap-2 pt-1">

                                        <button
                                            type="button"
                                            onclick="toggleReviewEdit({{ $review->id }})"
                                            class="inline-flex items-center gap-1 rounded-lg border border-amber-500/40 bg-amber-500/10 px-3 py-1.5 text-xs font-bold text-amber-400 transition hover:bg-amber-500/20">

                                            <i class="fa-solid fa-pen-to-square"></i>
                                            編集
                                        </button>

                                        <form
                                            action="{{ route('reviews.destroy', $review->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('このレビューを削除してもよろしいですか？');">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-red-500/40 bg-red-500/10 px-3 py-1.5 text-xs font-bold text-red-400 transition hover:bg-red-500/20">

                                                <i class="fa-solid fa-trash"></i>
                                                削除
                                            </button>

                                        </form>

                                    </div>

                                    {{-- レビュー編集フォーム --}}
                                    <div
                                        id="review-edit-{{ $review->id }}"
                                        class="hidden space-y-3 rounded-xl border border-gray-700 bg-[#0f141d] p-3">

                                        <h3 class="text-sm font-bold text-amber-400">
                                            <i class="fa-solid fa-pen-to-square mr-1"></i>
                                            レビューを編集
                                        </h3>

                                        <form
                                            action="{{ route('reviews.update', $review->id) }}"
                                            method="POST"
                                            class="space-y-3">

                                            @csrf
                                            @method('PUT')

                                            {{-- 鑑賞状況 --}}
                                            <div>

                                                <label
                                                    for="edit-status-{{ $review->id }}"
                                                    class="mb-1 block text-xs font-bold text-gray-400">
                                                    鑑賞状況
                                                </label>

                                                <select
                                                    id="edit-status-{{ $review->id }}"
                                                    name="status"
                                                    class="w-full rounded-lg border border-gray-700 bg-[#1a2332] p-2 text-sm text-gray-100 focus:border-amber-500 focus:outline-none">

                                                    <option value="watched" {{ !$isWantToWatch ? 'selected' : '' }}>
                                                        🍿 観た（鑑賞後）
                                                    </option>

                                                    <option value="want_to_watch" {{ $isWantToWatch ? 'selected' : '' }}>
                                                        ✨ 観たい（鑑賞前）
                                                    </option>

                                                </select>

                                            </div>

                                            {{-- 評価 --}}
                                            <div>

                                                <label
                                                    for="edit-rating-{{ $review->id }}"
                                                    class="mb-1 block text-xs font-bold text-gray-400">
                                                    評価・期待度（1.0〜10.0）
                                                </label>

                                                <input
                                                    id="edit-rating-{{ $review->id }}"
                                                    type="number"
                                                    name="rating"
                                                    min="1"
                                                    max="10"
                                                    step="0.1"
                                                    required
                                                    value="{{ number_format((float) $review->rating, 1, '.', '') }}"
                                                    class="w-full rounded-lg border border-gray-700 bg-[#1a2332] p-2 text-sm text-gray-100 focus:border-amber-500 focus:outline-none">

                                            </div>

                                            {{-- 感情タグ --}}
                                            <div>

                                                <p class="mb-2 text-xs font-bold text-gray-400">
                                                    感情タグ（最大4つ）
                                                </p>

                                                @php
                                                    $editMoodList = array_map(
                                                        fn ($tag) => trim($tag),
                                                        explode(',', (string) ($review->mood ?? ''))
                                                    );

                                                    $editMoodList = array_map(
                                                        fn ($tag) => ltrim($tag, '#'),
                                                        $editMoodList
                                                    );
                                                @endphp

                                                <div class="flex flex-wrap gap-2">

                                                    @foreach([
                                                        '号泣',
                                                        'スカッと',
                                                        'ハラハラ',
                                                        'キュン',
                                                        '号泣しそう',
                                                        'スカッとしそう',
                                                        'ハラハラしそう',
                                                        'キュンとしそう'
                                                    ] as $tag)

                                                        <label class="cursor-pointer">

                                                            <input
                                                                type="checkbox"
                                                                name="moods[]"
                                                                value="#{{ $tag }}"
                                                                {{ in_array($tag, $editMoodList, true) ? 'checked' : '' }}
                                                                onchange="limitEditMoods(this, {{ $review->id }})"
                                                                class="edit-mood-checkbox-{{ $review->id }} accent-amber-500">

                                                            <span class="text-xs text-gray-300">
                                                                #{{ $tag }}
                                                            </span>

                                                        </label>

                                                    @endforeach

                                                </div>

                                                <p
                                                    id="edit-mood-count-{{ $review->id }}"
                                                    class="mt-1 text-[10px] text-gray-500">
                                                    選択数：{{ count(array_filter($editMoodList)) }}/4
                                                </p>

                                            </div>

                                            {{-- 本文 --}}
                                            <div>

                                                <label
                                                    for="edit-comment-{{ $review->id }}"
                                                    class="mb-1 block text-xs font-bold text-gray-400">
                                                    感想・期待メモ
                                                </label>

                                                <textarea
                                                    id="edit-comment-{{ $review->id }}"
                                                    name="comment"
                                                    required
                                                    maxlength="5000"
                                                    rows="4"
                                                    class="w-full rounded-lg border border-gray-700 bg-[#1a2332] p-3 text-sm text-gray-100 focus:border-amber-500 focus:outline-none">{{ $review->comment }}</textarea>

                                            </div>

                                            {{-- 保存・キャンセル --}}
                                            <div class="flex gap-2">

                                                <button
                                                    type="submit"
                                                    class="flex-1 rounded-lg bg-amber-500 px-3 py-2 text-xs font-extrabold text-black transition hover:bg-amber-400">

                                                    <i class="fa-solid fa-check mr-1"></i>
                                                    変更を保存
                                                </button>

                                                <button
                                                    type="button"
                                                    onclick="toggleReviewEdit({{ $review->id }})"
                                                    class="rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-xs font-bold text-gray-300 transition hover:bg-gray-700">

                                                    キャンセル
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                @endif
                            @endauth

                            {{-- いいね・コメント --}}
                            <div class="flex items-center justify-between pt-2 border-t border-gray-800/60 text-xs">

                                @php
                                    $isLikedByMe = method_exists($review, 'isLikedBy')
                                        ? $review->isLikedBy(Auth::user())
                                        : false;

                                    $likesCount = method_exists($review, 'likes')
                                        ? $review->likes->count()
                                        : 0;
                                @endphp

                                {{-- いいね --}}
                                <form
                                    action="{{ route('reviews.like', $review->id ?? $review->review_id) }}"
                                    method="POST">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="flex items-center gap-1.5 px-2.5 py-1 rounded-full border transition duration-200 {{ $isLikedByMe ? 'bg-rose-500/10 border-rose-500/40 text-rose-500 hover:bg-rose-500/20' : 'bg-gray-800/80 border-gray-700 text-gray-300 hover:text-rose-400 hover:border-gray-600' }}">

                                        <i class="{{ $isLikedByMe ? 'fa-solid text-rose-500' : 'fa-regular' }} fa-heart text-xs"></i>

                                        <span class="font-bold text-[11px]">
                                            {{ $likesCount }}
                                        </span>

                                    </button>

                                </form>

                                @php
                                    $commentsCount = $review->comments_count
                                        ?? (
                                            method_exists($review, 'comments')
                                                ? $review->comments->count()
                                                : 0
                                        );

                                    $hasComments = $commentsCount > 0;
                                @endphp

                                {{-- コメント開閉 --}}
                                <button
                                    type="button"
                                    onclick="toggleCommentBox({{ $review->id }})"
                                    class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-800/80 border border-gray-700 hover:border-amber-500/40 transition duration-200 {{ $hasComments ? 'text-amber-400' : 'text-gray-400 hover:text-amber-400' }}">

                                    <i class="fa-regular fa-comment text-xs {{ $hasComments ? 'text-amber-400' : '' }}"></i>

                                    <span class="font-bold text-[11px]">
                                        {{ $commentsCount }} 件
                                    </span>

                                    <i
                                        class="fa-solid fa-chevron-down text-[9px] ml-0.5 transition-transform duration-200"
                                        id="comment-arrow-{{ $review->id }}">
                                    </i>

                                </button>

                            </div>

                            {{-- =================================================
                                 コメント欄
                            ================================================== --}}

                            <div
                                id="comment-box-{{ $review->id }}"
                                class="hidden pt-2.5 border-t border-gray-800/80 space-y-2.5">

                                {{-- 既存コメント一覧 --}}
                                @if(
                                    method_exists($review, 'comments')
                                    && $review->comments->count() > 0
                                )

                                    <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 custom-scrollbar">

                                        @foreach($review->comments as $comment)

                                            <div
                                                id="comment-{{ $comment->id }}"
                                                class="bg-[#1a2332] p-2 rounded-lg border border-gray-800 text-xs space-y-1">

                                                {{-- 投稿者名・投稿日時 --}}
                                                <div class="flex items-center justify-between gap-2 text-gray-400 text-[9px]">

                                                    <span class="font-bold text-amber-400">
                                                        {{ $comment->user->nickname ?? $comment->user->name ?? 'ユーザー' }}
                                                    </span>

                                                    <span>
                                                        {{ $comment->created_at?->diffForHumans() }}
                                                    </span>

                                                </div>

                                                {{-- コメント本文 --}}
                                                <p class="text-gray-200 leading-snug text-[11px] whitespace-pre-wrap">
                                                    {{ $comment->comment ?? $comment->body }}
                                                </p>

                                                {{-- 投稿者本人だけに編集・削除を表示 --}}
                                                @auth
                                                    @if((int) auth()->id() === (int) $comment->user_id)

                                                        <div class="flex items-center justify-end gap-2 pt-1">

                                                            {{-- 編集ボタン --}}
                                                            <button
                                                                type="button"
                                                                onclick="toggleCommentEdit({{ $comment->id }})"
                                                                class="rounded-md border border-amber-500/40 bg-amber-500/10 px-2 py-1 text-[10px] font-bold text-amber-400 hover:bg-amber-500/20">

                                                                <i class="fa-solid fa-pen-to-square mr-1"></i>
                                                                編集
                                                            </button>

                                                            {{-- 削除フォーム --}}
                                                            <form
                                                                action="{{ route('reviews.comments.destroy', $comment->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('このコメントを削除してもよろしいですか？');">

                                                                @csrf
                                                                @method('DELETE')

                                                                <button
                                                                    type="submit"
                                                                    class="rounded-md border border-red-500/40 bg-red-500/10 px-2 py-1 text-[10px] font-bold text-red-400 hover:bg-red-500/20">

                                                                    <i class="fa-solid fa-trash mr-1"></i>
                                                                    削除
                                                                </button>

                                                            </form>

                                                        </div>

                                                        {{-- コメント編集フォーム --}}
                                                        <div
                                                            id="comment-edit-{{ $comment->id }}"
                                                            class="hidden mt-2 space-y-2">

                                                            <form
                                                                action="{{ route('reviews.comments.update', $comment->id) }}"
                                                                method="POST"
                                                                class="space-y-2">

                                                                @csrf
                                                                @method('PUT')

                                                                <textarea
                                                                    name="comment"
                                                                    required
                                                                    maxlength="1000"
                                                                    rows="3"
                                                                    class="w-full rounded-lg border border-gray-700 bg-[#0f141d] p-2 text-xs text-gray-100 focus:border-amber-500 focus:outline-none">{{ $comment->comment ?? $comment->body }}</textarea>

                                                                <div class="flex gap-2">

                                                                    <button
                                                                        type="submit"
                                                                        class="rounded-md bg-amber-500 px-3 py-1.5 text-[10px] font-bold text-black hover:bg-amber-400">

                                                                        変更を保存
                                                                    </button>

                                                                    <button
                                                                        type="button"
                                                                        onclick="toggleCommentEdit({{ $comment->id }})"
                                                                        class="rounded-md border border-gray-700 bg-gray-800 px-3 py-1.5 text-[10px] font-bold text-gray-300 hover:bg-gray-700">

                                                                        キャンセル
                                                                    </button>

                                                                </div>

                                                            </form>

                                                        </div>

                                                    @endif
                                                @endauth

                                            </div>

                                        @endforeach

                                    </div>

                                @else

                                    <p class="text-[10px] text-gray-500 text-center py-0.5">
                                        まだコメントはありません。
                                    </p>

                                @endif

                                {{-- 新しいコメントを投稿 --}}
                                <form
                                    action="{{ route('reviews.comments.store', $review->id) }}"
                                    method="POST"
                                    class="flex gap-1.5">

                                    @csrf

                                    <input
                                        type="text"
                                        name="comment"
                                        required
                                        maxlength="500"
                                        placeholder="コメントを書く..."
                                        autocomplete="off"
                                        class="flex-1 bg-[#1a2332] border border-gray-700 rounded-lg px-2.5 py-1 text-xs text-gray-100 placeholder-gray-500 focus:outline-none focus:border-amber-500">

                                    <button
                                        type="submit"
                                        class="bg-amber-500 hover:bg-amber-400 text-black font-bold px-2.5 py-1 rounded-lg text-xs transition flex-shrink-0">

                                        送信
                                    </button>

                                </form>

                            </div>

                        </div>

                    @empty

                        <div class="col-span-full bg-[#121824] border border-gray-800 rounded-2xl p-8 text-center text-gray-400 text-xs w-full space-y-2">

                            <i class="fa-solid fa-comment-slash text-2xl text-gray-600"></i>

                            <p>
                                まだレビューが投稿されていません。最初のレビューを投稿してみましょう！
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

    {{-- =============================================================
         JavaScript
    ============================================================= --}}

    <script>
        const watchedMoodTags = [
            '号泣',
            'スカッと',
            'ハラハラ',
            'キュン'
        ];

        const wantMoodTags = [
            '号泣しそう',
            'スカッとしそう',
            'ハラハラしそう',
            'キュンとしそう'
        ];

        let selectedMoods = [];

        let currentStatus = @json(old('status', 'watched'));

        /* =========================================================
           戻る
        ========================================================= */

        function goBackOrHome() {
            if (
                document.referrer &&
                document.referrer.indexOf(window.location.host) !== -1
            ) {
                history.back();
            } else {
                window.location.href = '/';
            }
        }

        /* =========================================================
           鑑賞後 / 鑑賞前
        ========================================================= */

        function switchStatus(status) {
            currentStatus = status;

            const statusInput = document.getElementById('review_status');

            if (statusInput) {
                statusInput.value = status;
            }

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
                if (tabWatched) {
                    tabWatched.className =
                        'py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 bg-amber-500 text-black shadow-md flex items-center justify-center gap-1 cursor-pointer';
                }

                if (tabWantToWatch) {
                    tabWantToWatch.className =
                        'py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 text-gray-400 hover:text-white flex items-center justify-center gap-1 cursor-pointer';
                }

                if (ratingTitle) {
                    ratingTitle.innerText = '評価（1.0 〜 10.0）';
                }

                if (moodTagLabel) {
                    moodTagLabel.innerText = '観たあとの気分タグ（最大4つ選択可）';
                }

                if (commentLabel) {
                    commentLabel.innerText = 'レビュー感想コメント';
                }

                if (commentInput) {
                    commentInput.placeholder =
                        'この映画を観た感想や見どころを書いてみましょう...';

                    commentInput.className =
                        'w-full h-24 bg-[#1a2332] border border-gray-700/80 rounded-xl p-2.5 text-xs text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none leading-relaxed resize-none custom-scrollbar';
                }

                if (submitBtn) {
                    submitBtn.className =
                        'w-full bg-amber-500 hover:bg-amber-400 text-black font-extrabold px-4 py-2.5 rounded-xl text-xs sm:text-sm transition shadow-lg flex items-center justify-center gap-1.5 cursor-pointer';
                }

                if (submitBtnText) {
                    submitBtnText.innerText = 'レビューを投稿する';
                }
            } else {
                if (tabWantToWatch) {
                    tabWantToWatch.className =
                        'py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 bg-purple-600 text-white shadow-md flex items-center justify-center gap-1 cursor-pointer';
                }

                if (tabWatched) {
                    tabWatched.className =
                        'py-1.5 text-center text-xs font-bold rounded-lg transition duration-200 text-gray-400 hover:text-white flex items-center justify-center gap-1 cursor-pointer';
                }

                if (ratingTitle) {
                    ratingTitle.innerText = '期待度（1.0 〜 10.0）';
                }

                if (moodTagLabel) {
                    moodTagLabel.innerText = '期待するポイントタグ（最大4つ選択可）';
                }

                if (commentLabel) {
                    commentLabel.innerText = '観たい理由・期待メモ';
                }

                if (commentInput) {
                    commentInput.placeholder =
                        'この映画に期待することや観たい理由を書いてみましょう...';

                    commentInput.className =
                        'w-full h-24 bg-[#1a2332] border border-gray-700/80 rounded-xl p-2.5 text-xs text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none leading-relaxed resize-none custom-scrollbar';
                }

                if (submitBtn) {
                    submitBtn.className =
                        'w-full bg-purple-600 hover:bg-purple-500 text-white font-extrabold px-4 py-2.5 rounded-xl text-xs sm:text-sm transition shadow-lg flex items-center justify-center gap-1.5 cursor-pointer';
                }

                if (submitBtnText) {
                    submitBtnText.innerText = '観たいメモを保存する';
                }
            }

            renderMoodTags();

            const ratingSlider = document.getElementById('ratingSlider');

            if (ratingSlider) {
                updateRatingDisplay(ratingSlider);
            }
        }

        /* =========================================================
           タグ表示
        ========================================================= */

        function renderMoodTags() {
            const container = document.getElementById('moodTagContainer');

            if (!container) return;

            const tags = currentStatus === 'watched'
                ? watchedMoodTags
                : wantMoodTags;

            container.innerHTML = '';

            tags.forEach(tag => {
                const isSelected = selectedMoods.includes(tag);

                const btn = document.createElement('button');

                btn.type = 'button';
                btn.onclick = () => toggleMoodTag(tag);

                if (currentStatus === 'watched') {
                    btn.className = isSelected
                        ? 'text-[10px] sm:text-[11px] font-bold px-3 py-1 rounded-lg border transition duration-150 bg-amber-500 text-black border-amber-400 shadow-md cursor-pointer'
                        : 'text-[10px] sm:text-[11px] font-medium px-3 py-1 rounded-lg border transition duration-150 bg-[#1a2332] text-gray-300 border-gray-700/80 hover:border-gray-500 cursor-pointer';
                } else {
                    btn.className = isSelected
                        ? 'text-[10px] sm:text-[11px] font-bold px-3 py-1 rounded-lg border transition duration-150 bg-purple-600 text-white border-purple-400 shadow-md cursor-pointer'
                        : 'text-[10px] sm:text-[11px] font-medium px-3 py-1 rounded-lg border transition duration-150 bg-[#1a2332] text-gray-300 border-gray-700/80 hover:border-gray-500 cursor-pointer';
                }

                btn.innerText = '#' + tag;
                container.appendChild(btn);
            });

            const tagCountText = document.getElementById('tagCountText');

            if (tagCountText) {
                tagCountText.innerText = `${selectedMoods.length}/4`;
            }

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

        /* =========================================================
           タグ選択
        ========================================================= */

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

        /* =========================================================
           評価・期待度
        ========================================================= */

        function updateRatingDisplay(slider) {
            if (!slider) return;

            const val = parseFloat(slider.value).toFixed(1);
            const ratingValue = document.getElementById('ratingValue');

            if (ratingValue) {
                ratingValue.innerText = val;
            }

            const percentage = ((val - 1.0) / 9.0) * 100;

            const activeColor = currentStatus === 'watched'
                ? '#f59e0b'
                : '#a855f7';

            slider.style.background =
                `linear-gradient(to right, ${activeColor} ${percentage}%, #374151 ${percentage}%)`;

            const ratingValueWrapper = document.getElementById('ratingValueWrapper');

            if (ratingValueWrapper) {
                ratingValueWrapper.className = currentStatus === 'watched'
                    ? 'text-amber-400 font-extrabold text-xs sm:text-sm flex items-center gap-1'
                    : 'text-purple-300 font-extrabold text-xs sm:text-sm flex items-center gap-1';
            }

            const ratingIcon = document.getElementById('ratingIcon');

            if (ratingIcon) {
                ratingIcon.className = currentStatus === 'watched'
                    ? 'fa-solid fa-star text-amber-400 text-[10px]'
                    : 'fa-solid fa-sparkles text-purple-300 text-[10px]';
            }

            const label = document.getElementById('ratingLabel');

            if (!label) return;

            label.className = currentStatus === 'watched'
                ? 'text-[11px] font-bold text-amber-400 transition-colors duration-150'
                : 'text-[11px] font-bold text-purple-300 transition-colors duration-150';

            if (currentStatus === 'watched') {
                if (val >= 9.0) {
                    label.innerText = '✨ 超おすすめ！名作間違いなし';
                } else if (val >= 7.5) {
                    label.innerText = '👍 かなり面白かった！';
                } else if (val >= 5.0) {
                    label.innerText = '🙂 普通に楽しめた';
                } else {
                    label.innerText = '🤔 好みが分かれる作品';
                }
            } else {
                if (val >= 9.0) {
                    label.innerText = '🔥 絶対に観たい！期待大';
                } else if (val >= 7.5) {
                    label.innerText = '✨ かなり気になっている';
                } else if (val >= 5.0) {
                    label.innerText = '👀 機会があれば観たい';
                } else {
                    label.innerText = '💭 ちょっとチェックしておく';
                }
            }
        }

        /* =========================================================
           コメント欄の開閉
        ========================================================= */

        function toggleCommentBox(id) {
            const box = document.getElementById(`comment-box-${id}`);
            const arrow = document.getElementById(`comment-arrow-${id}`);

            if (!box) return;

            if (box.classList.contains('hidden')) {
                box.classList.remove('hidden');

                if (arrow) {
                    arrow.style.transform = 'rotate(180deg)';
                }
            } else {
                box.classList.add('hidden');

                if (arrow) {
                    arrow.style.transform = 'rotate(0deg)';
                }
            }
        }

        /* =========================================================
           コメント編集フォームの開閉
        ========================================================= */

        function toggleCommentEdit(id) {
            const form = document.getElementById(`comment-edit-${id}`);

            if (form) {
                form.classList.toggle('hidden');
            }
        }

        /* =========================================================
           レビュー編集フォームの開閉
        ========================================================= */

        function toggleReviewEdit(id) {
            const form = document.getElementById(`review-edit-${id}`);

            if (!form) {
                return;
            }

            form.classList.toggle('hidden');
        }

        /* =========================================================
           編集時のタグ選択数を最大4つに制限
        ========================================================= */

        function limitEditMoods(checkbox, id) {
            const checkboxes = document.querySelectorAll(
                `.edit-mood-checkbox-${id}`
            );

            const countText = document.getElementById(
                `edit-mood-count-${id}`
            );

            const checkedBoxes = Array.from(checkboxes).filter(
                item => item.checked
            );

            if (checkedBoxes.length > 4) {
                checkbox.checked = false;
                alert('タグは最大4つまで選択できます。');
            }

            const selectedCount = Array.from(checkboxes).filter(
                item => item.checked
            ).length;

            if (countText) {
                countText.textContent = `選択数：${selectedCount}/4`;
            }
        }
        /* =========================================================
           初期化
        ========================================================= */

        document.addEventListener('DOMContentLoaded', () => {
            switchStatus(currentStatus);
        });
    </script>

    {{-- レビューへの移動・金色ハイライト --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function focusReviewFromHash() {
                const hash = window.location.hash;

                if (!hash || !hash.startsWith('#review-')) {
                    return;
                }

                const reviewId = decodeURIComponent(hash.substring(1));
                const review = document.getElementById(reviewId);

                if (!review) {
                    console.warn('対象のレビューが見つかりません:', reviewId);
                    return;
                }

                // ハイライトをリセット
                review.classList.remove('review-target-highlight');
                void review.offsetWidth;

                // 対象のレビュー全体まで移動
                review.scrollIntoView({
                    behavior: 'auto',
                    block: 'start'
                });

                // 移動後に金色の光を適用
                requestAnimationFrame(function () {
                    review.classList.add('review-target-highlight');
                });
            }

            focusReviewFromHash();

            // URLのハッシュが変更された場合にも対応
            window.addEventListener('hashchange', focusReviewFromHash);
        });
    </script>
</x-app-layout>