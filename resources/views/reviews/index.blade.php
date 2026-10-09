<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">

                {{-- 前に戻るボタン --}}
                <button type="button" onclick="history.back()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                           bg-gray-800 hover:bg-gray-700
                           text-gray-300 hover:text-white
                           text-xs font-bold transition
                           border border-gray-700">
                    <span>←</span>
                    <span>戻る</span>
                </button>

                <h2 class="font-semibold text-lg sm:text-xl text-yellow-400 leading-tight">
                    コミュニティ
                </h2>

            </div>
        </div>
    </x-slot>


    {{-- =========================================================
    遷移時のGlowアニメーション
    ========================================================== --}}
    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes glow-highlight {
            0% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.9);
                border-color: rgba(245, 158, 11, 1);
                transform: scale(1.01);
            }

            50% {
                box-shadow: 0 0 25px 6px rgba(245, 158, 11, 0.6);
                border-color: rgba(251, 191, 36, 1);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
                border-color: rgba(31, 41, 55, 1);
                transform: scale(1);
            }
        }

        .highlight-card {
            animation: glow-highlight 3s ease-in-out;
        }

        /* スクロールバー */
        .community-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .community-scrollbar::-webkit-scrollbar-track {
            background: #0b0f17;
            border-radius: 9999px;
        }

        .community-scrollbar::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 9999px;
        }

        .community-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        .community-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #374151 #0b0f17;
        }
    </style>


    {{-- =========================================================
    コミュニティ メイン
    ========================================================== --}}
    <div class="min-h-screen bg-[#0b0f17] text-white relative py-6 sm:py-8">

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- =================================================
            ページ説明
            ================================================== --}}
            <div class="mb-5 text-center">

                <div class="inline-flex items-center gap-2
                            text-amber-400 text-sm font-extrabold
                            tracking-wide">

                    <i class="fa-solid fa-comments"></i>

                    <span>みんなの映画レビュー</span>

                </div>

                <p class="mt-1.5 text-xs text-gray-500">
                    映画の感想や「観たい！」という気持ちを共有しよう
                </p>

            </div>


            {{-- =================================================
            レビュー一覧
            ================================================== --}}
            <div class="space-y-5">

                @forelse ($reviews as $review)

                            @php
                                /*
                                |--------------------------------------------------------------------------
                                | 基本情報
                                |--------------------------------------------------------------------------
                                */

                                $displayTitle = $review->movie_title
                                    ?? $review->movie->title
                                    ?? $review->title
                                    ?? null;

                                $user = $review->user;

                                /*
                                |--------------------------------------------------------------------------
                                | いいね・コメント状態
                                |--------------------------------------------------------------------------
                                */

                                $isLikedByMe =
                                    Auth::check()
                                    && $review->likes
                                    && $review->likes->contains('user_id', Auth::id());

                                $hasCommentedByMe =
                                    Auth::check()
                                    && $review->comments
                                    && $review->comments->contains('user_id', Auth::id());

                                /*
                                |--------------------------------------------------------------------------
                                | 鑑賞前 / 鑑賞後 判定
                                |--------------------------------------------------------------------------
                                */

                                $statusVal = strtolower(trim($review->status ?? ''));

                                $rawMood = $review->moods
                                    ?? $review->mood
                                    ?? $review->tag
                                    ?? '';

                                $moodStr = is_array($rawMood)
                                    ? implode(',', $rawMood)
                                    : (string) $rawMood;

                                $isWantToWatch =
                                    in_array($statusVal, [
                                        'want_to_watch',
                                        'want',
                                        'want-to-watch',
                                        '1',
                                        'before'
                                    ])
                                    || str_contains($moodStr, 'しそう');

                                /*
                                |--------------------------------------------------------------------------
                                | 気分タグ
                                |--------------------------------------------------------------------------
                                */

                                $moodList = is_array($rawMood)
                                    ? $rawMood
                                    : explode(',', $rawMood);

                                /*
                                |--------------------------------------------------------------------------
                                | 件数
                                |--------------------------------------------------------------------------
                                */

                                $commentsCount = $review->comments
                                    ? $review->comments->count()
                                    : 0;
                            @endphp


                            {{-- =================================================
                            レビューカード
                            ================================================== --}}
                            <div id="review-{{ $review->id }}" x-data="{ openComments: false }" class="bg-[#111827]
                                           border border-gray-800
                                           rounded-2xl
                                           p-4 sm:p-5
                                           shadow-xl
                                           space-y-4
                                           transition-all duration-300">


                                {{-- =================================================
                                1. ユーザー情報 ＆ 評価
                                ================================================== --}}
                                <div class="flex items-center justify-between gap-3">

                                    {{-- ユーザー --}}
                                    <div class="flex items-center gap-3 min-w-0">

                                        @php
                                            $avatarUrl =
                                                $user->profile_photo_path
                                                ?? $user->avatar
                                                ?? null;
                                        @endphp

                                        @if(!empty($user->profile_photo_path))

                                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                                alt="{{ $user->name ?? 'User' }}" class="w-10 h-10 rounded-full object-cover
                                                               border border-yellow-500/40
                                                               shrink-0">

                                        @elseif(!empty($avatarUrl))

                                            <img src="{{ asset($avatarUrl) }}" alt="{{ $user->name ?? 'User' }}" class="w-10 h-10 rounded-full object-cover
                                                               border border-yellow-500/40
                                                               shrink-0">

                                        @else

                                            <div class="w-10 h-10 rounded-full
                                                               bg-amber-600/20
                                                               text-amber-400
                                                               border border-amber-500/40
                                                               flex items-center justify-center
                                                               font-bold text-sm
                                                               shrink-0">

                                                {{ mb_substr($user->nickname ?? $user->name ?? '匿', 0, 1) }}

                                            </div>

                                        @endif


                                        <div class="min-w-0">

                                            <div class="font-bold text-sm text-gray-200 truncate">
                                                {{ $user->nickname ?? $user->name ?? '匿名ユーザー' }}
                                            </div>

                                            <div class="text-[11px] text-gray-500">
                                                {{ $review->created_at ? $review->created_at->diffForHumans() : '' }}
                                            </div>

                                        </div>

                                    </div>


                                    {{-- 評価 --}}
                                    @if($review->rating)

                                        @if($isWantToWatch)

                                            {{-- 鑑賞前 --}}
                                            <div class="shrink-0
                                                                   px-3 py-1.5
                                                                   rounded-full
                                                                   bg-purple-500/10
                                                                   border border-purple-500/40
                                                                   text-purple-300
                                                                   font-extrabold
                                                                   text-xs sm:text-sm
                                                                   flex items-center gap-1">

                                                <span>✨</span>

                                                <span>
                                                    期待度 {{ number_format($review->rating, 1) }}
                                                </span>

                                            </div>

                                        @else

                                            {{-- 鑑賞後 --}}
                                            <div class="shrink-0
                                                                   px-3 py-1.5
                                                                   rounded-full
                                                                   bg-yellow-500/10
                                                                   border border-yellow-500/40
                                                                   text-yellow-400
                                                                   font-extrabold
                                                                   text-xs sm:text-sm
                                                                   flex items-center gap-1">

                                                <span>★</span>

                                                <span>
                                                    {{ number_format($review->rating, 1) }}
                                                </span>

                                            </div>

                                        @endif

                                    @endif

                                </div>


                                {{-- =================================================
                                2. 映画タイトル ＆ 気分タグ
                                ================================================== --}}
                                <div class="flex flex-col sm:flex-row
                                               sm:items-center
                                               sm:justify-between
                                               gap-2.5">


                                    {{-- 映画タイトル --}}
                                    <a href="{{ route('movies.show', $review->movie_id) }}#review-{{ $review->id }}" class="inline-flex items-center gap-2
                                                   w-fit max-w-full
                                                   px-3 py-1.5
                                                   rounded-xl
                                                   bg-indigo-950/80
                                                   hover:bg-indigo-900
                                                   text-indigo-200
                                                   hover:text-yellow-300
                                                   border border-indigo-700/70
                                                   text-sm sm:text-base
                                                   font-extrabold
                                                   transition
                                                   shadow-md
                                                   group">

                                        <span class="shrink-0">🎬</span>

                                        <span class="group-hover:underline truncate">
                                            {{ !empty($displayTitle)
                    ? $displayTitle
                    : '作品詳細を見る (ID: ' . $review->movie_id . ')' }}
                                        </span>

                                    </a>


                                    {{-- 気分タグ --}}
                                    @if(!empty(array_filter($moodList)))

                                        <div class="flex flex-wrap gap-1.5">

                                            @foreach($moodList as $m)

                                                @if(trim($m))

                                                    @if($isWantToWatch)

                                                        {{-- 鑑賞前：紫 --}}
                                                        <span class="inline-flex items-center
                                                                                       px-2.5 py-1
                                                                                       rounded-md
                                                                                       text-[11px]
                                                                                       font-semibold
                                                                                       bg-purple-500/10
                                                                                       text-purple-300
                                                                                       border border-purple-500/30">

                                                            #{{ trim(str_replace('#', '', $m)) }}

                                                        </span>

                                                    @else

                                                        {{-- 鑑賞後：金色 --}}
                                                        <span class="inline-flex items-center
                                                                                       px-2.5 py-1
                                                                                       rounded-md
                                                                                       text-[11px]
                                                                                       font-semibold
                                                                                       bg-amber-500/10
                                                                                       text-amber-300
                                                                                       border border-amber-500/30">

                                                            #{{ trim(str_replace('#', '', $m)) }}

                                                        </span>

                                                    @endif

                                                @endif

                                            @endforeach

                                        </div>

                                    @endif

                                </div>


                                {{-- =================================================
                                3. レビュー本文
                                ================================================== --}}
                                <div class="bg-[#182232]
                                               border border-gray-800/80
                                               rounded-xl
                                               p-4
                                               text-gray-200
                                               text-sm sm:text-base
                                               leading-relaxed
                                               whitespace-pre-line">

                                    {{ $review->comment ?? $review->content }}

                                </div>


                                {{-- =================================================
                                4. いいね ＆ コメント
                                ================================================== --}}
                                <div class="pt-3
                                               border-t border-gray-800/60
                                               flex items-center justify-between">


                                    {{-- いいね --}}
                                    <form action="{{ route('reviews.like', $review->id) }}" method="POST" class="inline">

                                        @csrf

                                        <button type="submit"
                                            class="px-3.5 py-1.5
                                                       rounded-full
                                                       transition
                                                       flex items-center gap-1.5
                                                       text-xs
                                                       border
                                                       {{ $isLikedByMe
                    ? 'bg-rose-500/20 text-rose-400 border-rose-500/50'
                    : 'bg-gray-800/80 hover:bg-gray-700 text-gray-300 hover:text-red-400 border-gray-700/50' }}">

                                            <span class="{{ $isLikedByMe
                    ? 'text-rose-500 font-bold'
                    : 'text-gray-400' }}">
                                                ♥
                                            </span>

                                            <span class="font-bold">
                                                {{ $review->likes ? $review->likes->count() : 0 }}
                                            </span>

                                        </button>

                                    </form>


                                    {{-- コメント --}}
                                    <button type="button" @click="openComments = !openComments"
                                        class="flex items-center gap-1.5
                                                   text-xs
                                                   transition
                                                   px-3 py-1.5
                                                   rounded-full
                                                   border
                                                   {{ $hasCommentedByMe
                    ? 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50'
                    : 'text-gray-400 hover:text-yellow-400 bg-gray-800/80 border-gray-700/50' }}">

                                        <span class="{{ $hasCommentedByMe
                    ? 'text-yellow-400'
                    : 'text-gray-400' }}">
                                            💬
                                        </span>

                                        <span class="font-bold">
                                            {{ $commentsCount }} 件
                                        </span>

                                        <span class="text-[10px]" x-text="openComments ? '▲' : '▼'">
                                            ▼
                                        </span>

                                    </button>

                                </div>


                                {{-- =================================================
                                5. コメント展開
                                ================================================== --}}
                                <div x-show="openComments" x-transition class="mt-3 pt-3
                                               border-t border-gray-800/50
                                               space-y-3">


                                    {{-- コメント一覧 --}}
                                    @if($review->comments && $review->comments->count() > 0)

                                        <div class="space-y-2
                                                           max-h-48
                                                           overflow-y-auto
                                                           pr-1
                                                           community-scrollbar">

                                            @foreach($review->comments as $comment)

                                                <div x-data="{
                                                                    editing: false,
                                                                    content: @json($comment->comment)
                                                                }" class="bg-gray-800/40
                                                                       border border-gray-700/40
                                                                       p-3
                                                                       rounded-lg
                                                                       text-xs">


                                                    {{-- 通常表示 --}}
                                                    <div x-show="!editing" class="flex justify-between items-start gap-2">

                                                        <div class="min-w-0 leading-relaxed">

                                                            <span class="font-semibold text-yellow-400">
                                                                {{ $comment->user->nickname ?? $comment->user->name ?? 'ユーザー' }}:
                                                            </span>

                                                            <span class="text-gray-200 ml-1" x-text="content">
                                                            </span>

                                                        </div>


                                                        @if(Auth::id() === $comment->user_id)

                                                            <div class="flex items-center gap-2
                                                                                       shrink-0 ml-2">

                                                                {{-- 編集 --}}
                                                                <button type="button" @click="editing = true" class="text-[10px]
                                                                                           text-gray-400
                                                                                           hover:text-yellow-400
                                                                                           transition">

                                                                    編集

                                                                </button>


                                                                {{-- 削除 --}}
                                                                <form action="{{ route('reviews.comments.destroy', $comment->id) }}"
                                                                    method="POST" onsubmit="return confirm('コメントを削除しますか？')">

                                                                    @csrf
                                                                    @method('DELETE')

                                                                    <button type="submit" class="text-[10px]
                                                                                               text-red-400
                                                                                               hover:text-red-300
                                                                                               hover:underline">

                                                                        削除

                                                                    </button>

                                                                </form>

                                                            </div>

                                                        @endif

                                                    </div>


                                                    {{-- 編集フォーム --}}
                                                    <div x-show="editing" x-cloak class="mt-1">

                                                        <form action="{{ route('reviews.comments.update', $comment->id) }}" method="POST"
                                                            class="flex gap-2">

                                                            @csrf
                                                            @method('PUT')

                                                            <input type="text" name="comment" x-model="content" required class="flex-1
                                                                                   bg-gray-900
                                                                                   border border-yellow-500/50
                                                                                   rounded-lg
                                                                                   px-2.5 py-1.5
                                                                                   text-xs
                                                                                   text-white
                                                                                   focus:outline-none
                                                                                   focus:border-yellow-400">

                                                            <button type="button" @click="editing = false" class="px-2.5 py-1.5
                                                                                   bg-gray-700
                                                                                   hover:bg-gray-600
                                                                                   text-gray-300
                                                                                   rounded-lg
                                                                                   text-[10px]">

                                                                キャンセル

                                                            </button>

                                                            <button type="submit" class="px-2.5 py-1.5
                                                                                   bg-yellow-500
                                                                                   hover:bg-yellow-400
                                                                                   text-black
                                                                                   font-bold
                                                                                   rounded-lg
                                                                                   text-[10px]">

                                                                保存

                                                            </button>

                                                        </form>

                                                    </div>

                                                </div>

                                            @endforeach

                                        </div>

                                    @else

                                        <div class="text-xs text-gray-500 text-center py-1">
                                            まだコメントはありません。
                                        </div>

                                    @endif


                                    {{-- 新規コメント --}}
                                    <form action="{{ route('reviews.comments.store', $review->id) }}" method="POST"
                                        class="flex gap-2">

                                        @csrf

                                        <input type="text" name="comment" placeholder="コメントを入力..." required class="flex-1
                                                       bg-gray-800
                                                       border border-gray-700
                                                       rounded-lg
                                                       px-3 py-2
                                                       text-xs
                                                       text-white
                                                       placeholder-gray-500
                                                       focus:outline-none
                                                       focus:border-yellow-500">

                                        <button type="submit" class="bg-yellow-500
                                                       hover:bg-yellow-400
                                                       text-black
                                                       font-bold
                                                       px-3.5 py-2
                                                       rounded-lg
                                                       text-xs
                                                       transition
                                                       shrink-0">

                                            送信

                                        </button>

                                    </form>

                                </div>

                            </div>

                @empty

                    {{-- =================================================
                    投稿なし
                    ================================================== --}}
                    <div class="bg-[#111827]
                                   border border-gray-800
                                   p-8
                                   rounded-2xl
                                   text-center">

                        <div class="text-3xl mb-3">
                            🎬
                        </div>

                        <p class="text-gray-400 text-sm">
                            まだ投稿がありません。
                        </p>

                        <p class="text-gray-600 text-xs mt-1">
                            映画を観て、最初のレビューを投稿してみましょう。
                        </p>

                    </div>

                @endforelse


                {{-- =================================================
                ページネーション
                ================================================== --}}
                @if($reviews->hasPages())

                    <div class="pt-2">
                        {{ $reviews->links() }}
                    </div>

                @endif

            </div>

        </div>


        {{-- =========================================================
        右下：TOPへ戻る
        ========================================================== --}}
        <div class="fixed bottom-6 right-6 z-50">

            <button type="button" onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" title="一番上へスクロール" class="w-11 h-11
                       bg-yellow-500
                       hover:bg-yellow-400
                       text-black
                       font-bold
                       text-lg
                       rounded-full
                       shadow-2xl
                       flex items-center justify-center
                       transition
                       hover:scale-110">

                ↑

            </button>

        </div>

    </div>


    {{-- =========================================================
    遷移先カードのGlow ＆ スクロール
    ========================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const hash = window.location.hash;

            if (!hash) {
                return;
            }

            const targetCard = document.querySelector(hash);

            if (!targetCard) {
                return;
            }

            setTimeout(() => {

                targetCard.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                targetCard.classList.add('highlight-card');

            }, 200);

        });
    </script>

</x-app-layout>