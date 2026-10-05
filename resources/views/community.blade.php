<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                {{-- 前に戻るボタン（ヘッダー部） --}}
                <button onclick="history.back()"
                    class="px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white text-xs font-bold transition flex items-center space-x-1 border border-gray-700">
                    <span>←</span>
                    <span>戻る</span>
                </button>
                <h2 class="font-semibold text-xl text-yellow-400 leading-tight">
                    コミュニティ
                </h2>
            </div>
        </div>
    </x-slot>

    {{-- ✨ 遷移時の発光（Glow）アニメーション定義 --}}
    <style>
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
    </style>

    <div class="py-8 bg-black min-h-screen text-white relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @forelse ($reviews as $review)
                {{-- 映画タイトルのフォールバック判定 --}}
                @php
                    $displayTitle = $review->movie_title
                        ?? $review->movie->title
                        ?? $review->title
                        ?? null;
                @endphp

                <div id="review-{{ $review->id }}" x-data="{ openComments: false }"
                    class="bg-[#111827] border border-gray-800 rounded-2xl p-5 shadow-xl space-y-3.5 transition-all duration-300">

                    {{-- 1. 上部：ユーザー情報・日時 ＆ ★評価バッジ --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            @php
                                $user = $review->user;
                                $avatarUrl = $user->profile_photo_path ?? $user->avatar ?? null;
                            @endphp

                            @if(!empty($user->profile_photo_path))
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name ?? 'User' }}"
                                    class="w-10 h-10 rounded-full object-cover border border-yellow-500/40">
                            @elseif(!empty($avatarUrl))
                                <img src="{{ asset($avatarUrl) }}" alt="{{ $user->name ?? 'User' }}"
                                    class="w-10 h-10 rounded-full object-cover border border-yellow-500/40">
                            @else
                                <div
                                    class="w-10 h-10 rounded-full bg-amber-600/30 text-amber-400 border border-amber-500/50 flex items-center justify-center font-bold text-sm">
                                    {{ mb_substr($user->nickname ?? $user->name ?? '匿', 0, 1) }}
                                </div>
                            @endif

                            <div>
                                <div class="font-bold text-sm text-gray-200">
                                    {{ $user->nickname ?? $user->name ?? '匿名ユーザー' }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $review->created_at ? $review->created_at->diffForHumans() : '' }}
                                </div>
                            </div>
                        </div>

                        {{-- ★ 評価バッジ --}}
                        @if($review->rating)
                            <div
                                class="px-3.5 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/40 text-yellow-400 font-extrabold text-sm sm:text-base flex items-center space-x-1">
                                <span>★</span>
                                <span>{{ number_format($review->rating, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- 2. 中段：映画タイトル（大きく表示） ＆ タグ --}}
                    <div class="flex flex-wrap items-center justify-between gap-2.5 pt-1">
                        {{-- 🎬 映画タイトルリンク --}}
                        <a href="{{ route('movies.show', $review->movie_id) }}#review-{{ $review->id }}"
                            class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-xl bg-indigo-950/80 hover:bg-indigo-900 text-indigo-200 hover:text-yellow-300 border border-indigo-700/70 text-base sm:text-lg font-extrabold transition shadow-md group">
                            <span>🎬</span>
                            <span class="group-hover:underline">
                                {{ !empty($displayTitle) ? $displayTitle : '作品詳細を見る (ID: ' . $review->movie_id . ')' }}
                            </span>
                            <span
                                class="text-xs text-indigo-400 group-hover:text-yellow-400 transition-transform group-hover:translate-x-0.5">➔</span>
                        </a>

                        {{-- 🏷️ タグ分裂表示 --}}
                        @php
                            $rawMood = $review->moods ?? $review->mood ?? $review->tag ?? '';
                            $moodList = is_array($rawMood) ? $rawMood : explode(',', $rawMood);
                        @endphp

                        @if(!empty(array_filter($moodList)))
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($moodList as $m)
                                    @if(trim($m))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-amber-500/10 text-amber-300 border border-amber-500/30">
                                            #{{ trim(str_replace('#', '', $m)) }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- 3. 本文エリア --}}
                    <div
                        class="bg-[#182232] border border-gray-800/80 rounded-xl p-4 text-gray-200 text-sm sm:text-base leading-relaxed whitespace-pre-line my-2">
                        {{ $review->comment ?? $review->content }}
                    </div>

                    {{-- 4. 下部アクション：いいね ＆ コメント --}}
                    <div class="pt-2 border-t border-gray-800/60 flex items-center justify-between">
                        <form action="{{ route('reviews.like', $review->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-3.5 py-1 rounded-full bg-gray-800/80 hover:bg-gray-700 text-gray-300 hover:text-red-400 transition flex items-center space-x-1.5 text-xs border border-gray-700/50">
                                <span class="text-red-400">♥</span>
                                <span class="font-bold">{{ $review->likes ? $review->likes->count() : 0 }}</span>
                            </button>
                        </form>

                        @php
                            $commentsCount = $review->comments ? $review->comments->count() : 0;
                        @endphp
                        <button @click="openComments = !openComments"
                            class="text-gray-400 hover:text-yellow-400 flex items-center space-x-1.5 text-xs transition px-3 py-1 rounded-full bg-gray-800/80 border border-gray-700/50">
                            <span>💬</span>
                            <span class="font-bold">{{ $commentsCount }} 件</span>
                            <span class="text-[10px]" x-text="openComments ? '▲' : '▼'">▼</span>
                        </button>
                    </div>

                    {{-- 5. コメント展開エリア --}}
                    <div x-show="openComments" x-transition class="mt-3 pt-3 border-t border-gray-800/50 space-y-3">
                        @if($review->comments && $review->comments->count() > 0)
                            <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                                @foreach($review->comments as $comment)
                                    <div
                                        class="bg-gray-800/40 border border-gray-700/40 p-2.5 rounded-lg text-xs flex justify-between items-start">
                                        <div>
                                            <span class="font-semibold text-yellow-400">
                                                {{ $comment->user->nickname ?? $comment->user->name ?? 'ユーザー' }}:
                                            </span>
                                            <span class="text-gray-200 ml-1">
                                                {{ $comment->comment }}
                                            </span>
                                        </div>
                                        @if(Auth::id() === $comment->user_id)
                                            <form action="{{ route('reviews.comments.destroy', $comment->id) }}" method="POST"
                                                onsubmit="return confirm('コメントを削除しますか？')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[10px] text-red-400 hover:underline ml-2">削除</button>
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-xs text-gray-500 text-center py-1">まだコメントはありません。</div>
                        @endif

                        <form action="{{ route('reviews.comments.store', $review->id) }}" method="POST"
                            class="flex space-x-2">
                            @csrf
                            <input type="text" name="comment" placeholder="コメントを入力..." required
                                class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-3 py-1.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-yellow-500">
                            <button type="submit"
                                class="bg-yellow-500 text-black font-bold px-3 py-1.5 rounded-lg text-xs hover:bg-yellow-400 transition flex-shrink-0">
                                送信
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <div class="bg-[#111827] border border-gray-800 p-8 rounded-2xl text-center text-gray-400 text-sm">
                    まだ投稿がありません。
                </div>
            @endforelse

            <div class="mt-6">
                {{ $reviews->links() }}
            </div>

        </div>

        {{-- 右下固定：一つ前に戻る ＆ Topへ戻るボタングループ --}}
        <div class="fixed bottom-6 right-6 flex flex-col space-y-3 z-50">
            <button onclick="history.back()" title="一つ前に戻る"
                class="w-11 h-11 bg-gray-800 hover:bg-gray-700 border border-gray-600 text-white font-bold text-sm rounded-full shadow-2xl flex items-center justify-center transition transform hover:scale-110">
                ←
            </button>
            <button onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" title="一番上へスクロール"
                class="w-11 h-11 bg-yellow-500 hover:bg-yellow-400 text-black font-bold text-lg rounded-full shadow-2xl flex items-center justify-center transition transform hover:scale-110">
                ↑
            </button>
        </div>
    </div>

    {{-- ✨ 遷移先カードの発光 ＆ スクロール処理 --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash;
            if (hash) {
                const targetCard = document.querySelector(hash);
                if (targetCard) {
                    setTimeout(() => {
                        targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        targetCard.classList.add('highlight-card');
                    }, 200);
                }
            }
        });
    </script>
</x-app-layout>