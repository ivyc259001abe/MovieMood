
<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button
                    onclick="history.back()"
                    class="px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white text-xs font-bold transition flex items-center gap-1 border border-gray-700">
                    <span>←</span>
                    <span>戻る</span>
                </button>

                <h2 class="font-semibold text-xl text-amber-400 leading-tight">
                    コミュニティ
                </h2>
            </div>
        </div>
    </x-slot>

    <style>
        [x-cloak] {
            display: none !important;
        }

        html {
            scroll-behavior: smooth;
        }

        /* レビューカードのハイライト */
        @keyframes glow-highlight {
            0% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.8);
                border-color: rgba(245, 158, 11, 1);
            }

            50% {
                box-shadow: 0 0 24px 5px rgba(245, 158, 11, 0.4);
                border-color: rgba(251, 191, 36, 1);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
                border-color: rgba(55, 65, 81, 1);
            }
        }

        .highlight-card {
            animation: glow-highlight 2.5s ease-in-out;
        }

        /* ページトップボタン */
        #back-to-top {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        #back-to-top.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
    </style>

    {{-- ページ全体 --}}
    <div class="min-h-screen bg-black text-white py-6 sm:py-8">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- コミュニティヘッダー --}}
            <div class="bg-gray-900/90 rounded-2xl p-5 sm:p-6 border border-gray-800 shadow-xl">

                <div class="flex items-center justify-between gap-4">

                    <div class="min-w-0">
                        <span class="text-[10px] sm:text-xs text-amber-500 font-bold tracking-wider uppercase block">
                            MOVIEMOOD COMMUNITY
                        </span>

                        <h1 class="text-xl sm:text-2xl font-extrabold text-white mt-1">
                            みんなの映画レビュー
                        </h1>

                        <p class="text-xs sm:text-sm text-gray-400 mt-2">
                            映画の感想や期待度を共有しよう
                        </p>
                    </div>

                    <div class="hidden sm:flex w-14 h-14 rounded-full bg-amber-500/10 border border-amber-500/30 items-center justify-center text-2xl flex-shrink-0">
                        🎬
                    </div>

                </div>

            </div>

            {{-- コミュニティメインパネル --}}
            <div class="bg-gray-900/90 rounded-2xl border border-gray-800 shadow-xl overflow-hidden">

                {{-- レビュー一覧の見出し --}}
                <div class="flex items-center justify-between gap-3 px-4 sm:px-6 py-4 border-b border-gray-800 bg-black/40">

                    <div class="flex items-center gap-2 min-w-0">
                        <span class="text-amber-500 text-lg">✍</span>

                        <h2 class="text-sm sm:text-base font-extrabold text-white">
                            COMMUNITY FEED
                        </h2>
                    </div>

                    <span class="text-[10px] sm:text-xs text-gray-400 font-bold flex-shrink-0">
                        {{ $reviews->total() }} 件の投稿
                    </span>

                </div>

                {{-- レビュー一覧 --}}
                <div class="p-4 sm:p-6 space-y-4 sm:space-y-5">

                    @forelse ($reviews as $review)

                        @php
                            /*
                             * 映画タイトル
                             */
                            $displayTitle =
                                $review->movie_title
                                ?? $review->movie?->title
                                ?? $review->title
                                ?? null;

                            /*
                             * いいね状態
                             */
                            $isLikedByMe =
                                Auth::check()
                                && $review->likes
                                && $review->likes->contains(
                                    'user_id',
                                    Auth::id()
                                );

                            /*
                             * コメント状態
                             */
                            $hasCommentedByMe =
                                Auth::check()
                                && $review->comments
                                && $review->comments->contains(
                                    'user_id',
                                    Auth::id()
                                );

                            /*
                             * 鑑賞前・鑑賞後の判定
                             */
                            $statusVal = strtolower(
                                trim($review->status ?? '')
                            );

                            $rawMood =
                                $review->moods
                                ?? $review->mood
                                ?? $review->tag
                                ?? '';

                            $moodStr = is_array($rawMood)
                                ? implode(',', $rawMood)
                                : (string) $rawMood;

                            $isWantToWatch =
                                in_array(
                                    $statusVal,
                                    [
                                        'want_to_watch',
                                        'want',
                                        'want-to-watch',
                                        '1',
                                        'before'
                                    ]
                                )
                                || str_contains($moodStr, 'しそう');

                            /*
                             * 投稿者
                             */
                            $user = $review->user;

                            /*
                             * コメント数
                             */
                            $commentsCount = $review->comments
                                ? $review->comments->count()
                                : 0;

                            /*
                             * タグ一覧
                             */
                            $moodList = is_array($rawMood)
                                ? $rawMood
                                : explode(',', $rawMood);
                        @endphp

                        {{-- レビューカード --}}
                        <article
                            id="review-{{ $review->id }}"
                            x-data="{
                                openComments: window.location.hash === '#review-{{ $review->id }}'
                            }"
                            class="profile-item bg-black/40 border border-gray-800 rounded-xl p-4 sm:p-5 space-y-4 transition duration-300 hover:border-gray-700">

                            {{-- 投稿者情報・評価 --}}
                            <div class="flex items-center justify-between gap-3">

                                <div class="flex items-center gap-3 min-w-0">

                                    {{-- プロフィール画像 --}}
                                    @php
                                        $avatarUrl =
                                            $user?->profile_photo_path
                                            ?? $user?->avatar
                                            ?? null;
                                    @endphp

                                    @if(!empty($user?->profile_photo_path))

                                        <img
                                            src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                            alt="{{ $user->name ?? 'User' }}"
                                            class="w-10 h-10 rounded-full object-cover border {{ $isWantToWatch ? 'border-purple-500/50' : 'border-amber-500/50' }} flex-shrink-0"
                                            onerror="this.onerror=null;">

                                    @elseif(!empty($avatarUrl))

                                        <img
                                            src="{{ str_starts_with($avatarUrl, 'http') ? $avatarUrl : asset($avatarUrl) }}"
                                            alt="{{ $user->name ?? 'User' }}"
                                            class="w-10 h-10 rounded-full object-cover border {{ $isWantToWatch ? 'border-purple-500/50' : 'border-amber-500/50' }} flex-shrink-0"
                                            onerror="this.onerror=null;">

                                    @else

                                        <div class="w-10 h-10 rounded-full {{ $isWantToWatch ? 'bg-purple-500/20 text-purple-300 border-purple-500/40' : 'bg-amber-500/10 text-amber-400 border-amber-500/40' }} border flex items-center justify-center font-bold text-sm flex-shrink-0">
                                            {{ mb_substr($user?->nickname ?? $user?->name ?? '匿', 0, 1) }}
                                        </div>

                                    @endif

                                    {{-- 名前・投稿日 --}}
                                    <div class="min-w-0">
                                        <div class="font-bold text-sm text-gray-200 truncate">
                                            {{ $user?->nickname ?? $user?->name ?? '匿名ユーザー' }}
                                        </div>

                                        <div class="text-xs text-gray-500 mt-0.5">
                                            {{ $review->created_at?->diffForHumans() ?? '' }}
                                        </div>
                                    </div>

                                </div>

                                {{-- 評価 --}}
                                @if($review->rating)

                                    @if($isWantToWatch)

                                        <div class="flex-shrink-0 px-3 py-1.5 rounded-xl bg-purple-500/10 border border-purple-500/30 text-purple-300 text-xs sm:text-sm font-extrabold">
                                            <span>✨</span>
                                            <span>
                                                期待度 {{ number_format($review->rating, 1) }}
                                            </span>
                                        </div>

                                    @else

                                        <div class="flex-shrink-0 px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 text-sm font-extrabold">
                                            ★ {{ number_format($review->rating, 1) }}
                                        </div>

                                    @endif

                                @endif

                            </div>

                            {{-- 映画タイトル・タグ --}}
                            <div class="space-y-3">

                                {{-- 映画タイトル --}}
                                <div>
                                    @if($review->movie_id)

                                        <a
                                            href="{{ route('movies.show', $review->movie_id) }}#review-{{ $review->id }}"
                                            class="inline-flex items-center gap-2 text-base sm:text-lg font-extrabold text-gray-100 hover:text-amber-400 transition break-words">

                                            <span class="text-amber-500 flex-shrink-0">🎬</span>

                                            <span>
                                                {{ $displayTitle ?: '作品詳細を見る (ID: ' . $review->movie_id . ')' }}
                                            </span>

                                            <span class="text-xs text-gray-500">↗</span>
                                        </a>

                                    @else

                                        <div class="inline-flex items-center gap-2 text-base sm:text-lg font-extrabold text-gray-100 break-words">
                                            <span class="text-amber-500 flex-shrink-0">🎬</span>
                                            <span>{{ $displayTitle ?: '映画レビュー' }}</span>
                                        </div>

                                    @endif
                                </div>

                                {{-- 気分タグ --}}
                                @if(!empty(array_filter($moodList, fn($m) => trim((string) $m) !== '')))

                                    <div class="flex flex-wrap gap-1.5">

                                        @foreach($moodList as $m)

                                            @if(trim((string) $m) !== '')

                                                @php
                                                    $cleanTag = trim(
                                                        str_replace('#', '', $m)
                                                    );

                                                    $isPurpleTag =
                                                        $isWantToWatch
                                                        || str_contains($cleanTag, 'しそう');
                                                @endphp

                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold border {{ $isPurpleTag ? 'bg-purple-500/10 text-purple-300 border-purple-500/30' : 'bg-amber-500/10 text-amber-300 border-amber-500/30' }}">
                                                    #{{ $cleanTag }}
                                                </span>

                                            @endif

                                        @endforeach

                                    </div>

                                @endif

                            </div>

                            {{-- レビュー本文 --}}
                            <div class="bg-gray-900/70 border border-gray-800 rounded-xl p-4">

                                <p class="text-[10px] text-amber-500 font-bold tracking-wider mb-2">
                                    {{ $isWantToWatch ? 'MY EXPECTATION' : 'MY REVIEW' }}
                                </p>

                                <div class="text-sm text-gray-300 leading-7 whitespace-pre-line break-words">
                                    {{ $review->comment ?? $review->content }}
                                </div>

                            </div>

                            {{-- いいね・コメント操作 --}}
                            <div class="flex items-center justify-between gap-3 pt-3 border-t border-gray-800">

                                {{-- いいね --}}
                                <form
                                    action="{{ route('reviews.like', $review->id) }}"
                                    method="POST"
                                    class="inline-flex">

                                    @csrf

                                    <button
                                        type="submit"
                                        aria-label="いいね"
                                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-full border text-xs font-bold transition {{ $isLikedByMe ? 'bg-rose-500/10 text-rose-400 border-rose-500/40' : 'bg-gray-900/70 text-gray-400 border-gray-700 hover:text-rose-400 hover:border-rose-500/40' }}">

                                        <span class="text-base">♥</span>

                                        <span>
                                            {{ $review->likes ? $review->likes->count() : 0 }}
                                        </span>

                                    </button>

                                </form>

                                {{-- コメント開閉 --}}
                                <button
                                    type="button"
                                    @click="openComments = !openComments"
                                    :aria-expanded="openComments.toString()"
                                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-full border text-xs font-bold transition {{ $hasCommentedByMe ? 'bg-amber-500/10 text-amber-400 border-amber-500/40' : 'bg-gray-900/70 text-gray-400 border-gray-700 hover:text-amber-400 hover:border-amber-500/40' }}">

                                    <span>💬</span>
                                    <span>{{ $commentsCount }} 件</span>
                                    <span class="text-[10px]" x-text="openComments ? '▲' : '▼'">▼</span>

                                </button>

                            </div>

                            {{-- コメント欄 --}}
                            <div
                                x-show="openComments"
                                x-transition
                                x-cloak
                                class="pt-4 border-t border-gray-800 space-y-3">

                                <h3 class="text-xs text-gray-300 font-bold">
                                    コメント
                                </h3>

                                {{-- コメント一覧 --}}
                                @if($review->comments && $review->comments->count() > 0)

                                    <div class="space-y-2 max-h-60 overflow-y-auto pr-1">

                                        @foreach($review->comments as $comment)

                                            <div
                                                x-data="{
                                                    editing: false,
                                                    originalContent: {{ Illuminate\Support\Js::from($comment->comment ?? '') }},
                                                    content: {{ Illuminate\Support\Js::from($comment->comment ?? '') }}
                                                }"
                                                class="bg-black/30 border border-gray-800 p-3 rounded-xl text-xs">

                                                {{-- 通常表示 --}}
                                                <div
                                                    x-show="!editing"
                                                    class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">

                                                    <div class="min-w-0 flex-1 break-words">

                                                        <span class="font-bold text-amber-400">
                                                            {{ $comment->user?->nickname ?? $comment->user?->name ?? 'ユーザー' }}
                                                        </span>

                                                        <p
                                                            class="text-gray-300 whitespace-pre-wrap leading-relaxed mt-1"
                                                            x-text="content"></p>

                                                    </div>

                                                    {{-- 自分のコメントだけ操作可能 --}}
                                                    @if((int) auth()->id() === (int) $comment->user_id)

                                                        <div class="flex items-center gap-2 flex-shrink-0">

                                                            {{-- 編集 --}}
                                                            <button
                                                                type="button"
                                                                @click="editing = true"
                                                                class="inline-flex items-center gap-1 rounded-lg border border-amber-500/30 bg-amber-500/10 px-3 py-1.5 text-xs font-bold text-amber-400 transition hover:bg-amber-500/20">

                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                                編集

                                                            </button>

                                                            {{-- 削除 --}}
                                                            <form
                                                                action="{{ route('reviews.comments.destroy', $comment->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('このコメントを削除してもよろしいですか？');">

                                                                @csrf
                                                                @method('DELETE')

                                                                <button
                                                                    type="submit"
                                                                    class="inline-flex items-center gap-1 rounded-lg border border-red-500/30 bg-red-500/10 px-3 py-1.5 text-xs font-bold text-red-400 transition hover:bg-red-500/20">

                                                                    <i class="fa-solid fa-trash"></i>
                                                                    削除

                                                                </button>

                                                            </form>

                                                        </div>

                                                    @endif

                                                </div>

                                                {{-- 編集フォーム --}}
                                                <div x-show="editing" x-cloak class="mt-2">

                                                    <form
                                                        action="{{ route('reviews.comments.update', $comment->id) }}"
                                                        method="POST"
                                                        class="flex flex-col sm:flex-row gap-2">

                                                        @csrf
                                                        @method('PUT')

                                                        <input
                                                            type="text"
                                                            name="comment"
                                                            x-model="content"
                                                            required
                                                            maxlength="1000"
                                                            class="flex-1 min-w-0 bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-amber-500">

                                                        <div class="flex items-center gap-2">

                                                            {{-- キャンセル --}}
                                                            <button
                                                                type="button"
                                                                @click="editing = false; content = originalContent"
                                                                class="rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-xs font-bold text-gray-300 transition hover:bg-gray-700">

                                                                キャンセル

                                                            </button>

                                                            {{-- 保存 --}}
                                                            <button
                                                                type="submit"
                                                                class="rounded-lg bg-amber-500 px-3 py-2 text-xs font-bold text-black transition hover:bg-amber-400">

                                                                保存

                                                            </button>

                                                        </div>

                                                    </form>

                                                </div>

                                            </div>

                                        @endforeach

                                    </div>

                                @else

                                    <div class="bg-black/30 border border-gray-800 rounded-xl p-4 text-center">
                                        <p class="text-xs text-gray-500">
                                            まだコメントはありません。
                                        </p>
                                    </div>

                                @endif

                                {{-- コメント投稿 --}}
                                <form
                                    action="{{ route('reviews.comments.store', $review->id) }}"
                                    method="POST"
                                    class="flex gap-2">

                                    @csrf

                                    <input
                                        type="text"
                                        name="comment"
                                        placeholder="コメントを入力..."
                                        required
                                        maxlength="1000"
                                        class="flex-1 min-w-0 bg-black/30 border border-gray-700 rounded-xl px-3 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-amber-500">

                                    <button
                                        type="submit"
                                        class="flex-shrink-0 bg-amber-500 text-black font-bold px-4 py-2.5 rounded-xl text-xs hover:bg-amber-400 transition">

                                        送信

                                    </button>

                                </form>

                            </div>

                        </article>

                    @empty

                        {{-- 投稿がない場合 --}}
                        <div class="py-12 text-center space-y-3">

                            <div class="text-3xl text-gray-600">
                                🎬
                            </div>

                            <h3 class="text-sm font-bold text-gray-200">
                                まだ投稿がありません
                            </h3>

                            <p class="text-xs text-gray-400">
                                最初の映画レビューを投稿してみましょう。
                            </p>

                        </div>

                    @endforelse

                </div>

                {{-- ページネーション：マイページと統一 --}}
                @if($reviews->hasPages())

                    <div class="mt-6 mb-6 flex items-center justify-center gap-1.5 text-xs">

                        {{-- 前のページ --}}
                        @if($reviews->onFirstPage())

                            <span class="px-2 py-1 rounded bg-gray-800/60 text-gray-600 cursor-not-allowed">
                                ‹
                            </span>

                        @else

                            <a
                                href="{{ $reviews->previousPageUrl() }}"
                                class="px-2 py-1 rounded bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition">
                                ‹
                            </a>

                        @endif

                        {{-- ページ番号 --}}
                        @foreach($reviews->getUrlRange(1, $reviews->lastPage()) as $page => $url)

                            @if($page == $reviews->currentPage())

                                <span
                                    aria-current="page"
                                    class="px-2.5 py-1 rounded bg-amber-500 text-gray-950 font-bold">
                                    {{ $page }}
                                </span>

                            @else

                                <a
                                    href="{{ $url }}"
                                    class="px-2.5 py-1 rounded bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700 transition">
                                    {{ $page }}
                                </a>

                            @endif

                        @endforeach

                        {{-- 次のページ --}}
                        @if($reviews->hasMorePages())

                            <a
                                href="{{ $reviews->nextPageUrl() }}"
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

            </div>

        </div>

        {{-- ページトップボタン --}}
        <div class="fixed bottom-5 right-5 sm:bottom-6 sm:right-6 z-50">

            <button
                type="button"
                id="back-to-top"
                onclick="scrollToTop()"
                title="一番上へスクロール"
                aria-label="ページの一番上へ戻る"
                class="w-11 h-11 bg-amber-500 hover:bg-amber-400 text-black font-bold text-lg rounded-full shadow-xl flex items-center justify-center transition hover:scale-105">

                ↑

            </button>

        </div>

    </div>

    {{-- レビューのハイライト・ページトップボタン --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /*
             * 1. レビューのハイライト
             */
            const hash = window.location.hash;

            if (hash && /^#review-\d+$/.test(hash)) {
                const targetCard = document.querySelector(hash);

                if (targetCard) {
                    setTimeout(() => {
                        targetCard.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        targetCard.classList.add('highlight-card');
                    }, 200);
                }
            }

            /*
             * 2. ページトップボタン
             */
            const backToTopButton = document.getElementById('back-to-top');

            if (!backToTopButton) {
                return;
            }

            let hideTimer = null;

            const HIDE_DELAY = 2500;
            const SHOW_SCROLL_POSITION = 200;

            function updateButtonVisibility() {
                if (window.scrollY > SHOW_SCROLL_POSITION) {
                    backToTopButton.classList.add('is-visible');
                } else {
                    backToTopButton.classList.remove('is-visible');
                    clearTimeout(hideTimer);
                }
            }

            function scheduleHide() {
                clearTimeout(hideTimer);

                hideTimer = setTimeout(() => {
                    if (window.scrollY > SHOW_SCROLL_POSITION) {
                        backToTopButton.classList.remove('is-visible');
                    }
                }, HIDE_DELAY);
            }

            window.addEventListener('scroll', () => {
                updateButtonVisibility();

                if (window.scrollY > SHOW_SCROLL_POSITION) {
                    scheduleHide();
                }
            }, { passive: true });

            ['mousemove', 'mousedown', 'touchstart', 'keydown'].forEach(eventName => {
                document.addEventListener(eventName, () => {
                    if (window.scrollY > SHOW_SCROLL_POSITION) {
                        backToTopButton.classList.add('is-visible');
                        scheduleHide();
                    }
                }, { passive: true });
            });

            window.scrollToTop = function () {
                clearTimeout(hideTimer);

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });

                backToTopButton.classList.remove('is-visible');
            };

            updateButtonVisibility();

            if (window.scrollY > SHOW_SCROLL_POSITION) {
                scheduleHide();
            }
        });
    </script>

</x-app-layout>