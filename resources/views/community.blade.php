<x-app-layout>
    <div class="py-6 space-y-6 max-w-4xl mx-auto sm:px-6 lg:px-8">

        <!-- コミュニティヘッダー -->
        <div class="bg-[#121824] border border-gray-800 rounded-2xl p-6 shadow-xl text-center">
            <h1 class="text-2xl font-bold text-amber-500 flex items-center justify-center gap-2">
                <i class="fa-solid fa-users"></i> みんなの感情タイムライン
            </h1>
            <p class="text-xs text-gray-400 mt-1">～ 今のムードから観たい映画を見つけよう ～</p>
        </div>

        <!-- 気分で絞り込みフィルタボタン -->
        <div
            class="bg-[#121824] border border-gray-800 rounded-2xl p-4 shadow-lg flex flex-wrap items-center justify-between gap-3">
            <span class="text-xs font-bold text-gray-400 flex items-center gap-1.5">
                <i class="fa-solid fa-filter text-amber-500"></i> 気分で絞り込み:
            </span>
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <a href="{{ route('community.index') }}"
                    class="px-3 py-1.5 rounded-full font-medium transition {{ !request('mood') ? 'bg-amber-500 text-black font-bold' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }}">
                    すべて
                </a>
                <a href="{{ route('community.index', ['mood' => '号泣']) }}"
                    class="px-3 py-1.5 rounded-full font-medium border border-gray-700 transition {{ request('mood') == '号泣' ? 'bg-amber-500 text-black border-amber-500 font-bold' : 'bg-[#1a2332] text-amber-400 hover:border-amber-500' }}">
                    😭 #号泣
                </a>
                <a href="{{ route('community.index', ['mood' => 'スカッと']) }}"
                    class="px-3 py-1.5 rounded-full font-medium border border-gray-700 transition {{ request('mood') == 'スカッと' ? 'bg-amber-500 text-black border-amber-500 font-bold' : 'bg-[#1a2332] text-amber-400 hover:border-amber-500' }}">
                    😆 #スカッと
                </a>
                <a href="{{ route('community.index', ['mood' => 'ハラハラ']) }}"
                    class="px-3 py-1.5 rounded-full font-medium border border-gray-700 transition {{ request('mood') == 'ハラハラ' ? 'bg-amber-500 text-black border-amber-500 font-bold' : 'bg-[#1a2332] text-amber-400 hover:border-amber-500' }}">
                    😱 #ハラハラ
                </a>
                <a href="{{ route('community.index', ['mood' => 'キュン']) }}"
                    class="px-3 py-1.5 rounded-full font-medium border border-gray-700 transition {{ request('mood') == 'キュン' ? 'bg-amber-500 text-black border-amber-500 font-bold' : 'bg-[#1a2332] text-amber-400 hover:border-amber-500' }}">
                    💖 #キュン
                </a>
            </div>
        </div>

        <!-- タイムライン見出し＆件数 -->
        <div class="flex items-center justify-between px-1">
            <span class="text-xs font-bold text-gray-400">
                <i class="fa-solid fa-list-ul text-amber-500 mr-1"></i> タイムライン
            </span>
            <span class="text-xs text-gray-400">
                全 <span class="text-amber-500 font-bold">{{ $reviews->total() }}</span> 件
            </span>
        </div>

        <!-- タイムラインカード一覧 -->
        <div class="space-y-4">
            @forelse ($reviews as $review)
                @php
                    $movieId = $review->tmdb_id ?? $review->movie_id ?? $review->tmdb_movie_id ?? null;

                    // ユーザーのアイコン画像パスを取得 (avatar, icon, icon_path の順で判定)
                    $userIcon = $review->user->avatar ?? $review->user->icon ?? $review->user->icon_path ?? null;

                    // いいね済み判定
                    $isLiked = method_exists($review, 'isLikedBy') ? $review->isLikedBy(Auth::user()) : false;

                    // ログイン中のユーザーがコメント済みか判定
                    $hasCommented = Auth::check() && method_exists($review, 'comments')
                        ? $review->comments->contains('user_id', Auth::id())
                        : false;
                @endphp

                <!-- Alpine.js でコメントセクションの開閉状態（showComments）を管理 -->
                <div x-data="{ showComments: false }"
                    class="bg-[#121824] border border-gray-800 rounded-2xl p-5 shadow-xl space-y-3">

                    <!-- 上段：ヘッダー（ユーザー情報・★評価） -->
                    <div class="flex items-start justify-between border-b border-gray-800/80 pb-3">
                        <div class="flex items-center gap-3">

                            <!-- アバターアイコン -->
                            @if ($userIcon && file_exists(public_path($userIcon)))
                                <img src="{{ asset($userIcon) }}" alt="{{ $review->user->name ?? 'User' }}"
                                    class="w-9 h-9 rounded-full object-cover border border-amber-500/40 shadow-inner">
                            @elseif ($userIcon && (str_starts_with($userIcon, 'http://') || str_starts_with($userIcon, 'https://')))
                                <img src="{{ $userIcon }}" alt="{{ $review->user->name ?? 'User' }}"
                                    class="w-9 h-9 rounded-full object-cover border border-amber-500/40 shadow-inner">
                            @else
                                <div
                                    class="w-9 h-9 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-500 flex items-center justify-center font-bold text-xs shadow-inner">
                                    {{ mb_substr($review->user->nickname ?? $review->user->name ?? '匿', 0, 1) }}
                                </div>
                            @endif

                            <div>
                                <span class="font-bold text-sm text-gray-200 block leading-tight">
                                    {{ $review->user->nickname ?? $review->user->name ?? '匿名ユーザー' }}
                                </span>
                                <span class="text-[10px] text-gray-500">
                                    {{ $review->created_at ? $review->created_at->diffForHumans() : '' }}
                                </span>
                            </div>
                        </div>

                        <!-- ★評価バッジ -->
                        <div
                            class="bg-amber-500/10 border border-amber-500/30 text-amber-400 px-3 py-1 rounded-full text-xs font-extrabold flex items-center gap-1 shadow-sm">
                            <i class="fa-solid fa-star text-amber-400"></i>
                            <span>{{ number_format($review->rating, 1) }}</span>
                        </div>
                    </div>

                    <!-- 中段：映画タイトル & 気分タグ -->
                    <div>
                        <h2 class="text-base font-bold text-white transition mb-1.5">
                            @if($movieId)
                                <!-- 映画詳細ページへのリンク -->
                                <a href="{{ route('movies.show', $movieId) }}"
                                    class="inline-flex items-center gap-2 hover:text-amber-400 hover:underline transition">
                                    <i class="fa-solid fa-film text-xs text-amber-500"></i>
                                    {{ $review->movie_title ?? ($review->movie->title ?? '映画作品') }}
                                </a>
                            @else
                                <span class="flex items-center gap-2">
                                    <i class="fa-solid fa-film text-xs text-amber-500"></i>
                                    {{ $review->movie_title ?? ($review->movie->title ?? '映画作品') }}
                                </span>
                            @endif
                        </h2>

                        <!-- 気分タグ -->
                        @if(!empty($review->moods) || !empty($review->mood))
                            <div class="flex flex-wrap gap-1.5 my-2">
                                @php
                                    $moodList = is_array($review->moods) ? $review->moods : explode(',', $review->moods ?? $review->mood ?? '');
                                @endphp
                                @foreach($moodList as $m)
                                    @if(trim($m))
                                        <span
                                            class="bg-amber-500/20 text-amber-300 border border-amber-500/40 text-[11px] px-2.5 py-0.5 rounded-md font-semibold">
                                            #{{ trim(str_replace('#', '', $m)) }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- 下段：レビュー本文 -->
                    <div
                        class="bg-[#1a2332] p-4 rounded-xl border border-gray-800/80 text-sm text-gray-200 leading-relaxed shadow-inner">
                        {{ $review->comment }}
                    </div>

                    <!-- アクションエリア（いいね・コメントボタン） -->
                    <div class="flex items-center justify-between pt-3 border-t border-gray-800/60 text-xs">
                        <div class="flex items-center gap-2">
                            <!-- ❤️ いいねボタン -->
                            <form action="{{ route('reviews.like', $review->id ?? $review->review_id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border transition cursor-pointer font-bold {{ $isLiked ? 'bg-rose-500/20 border-rose-500/50 text-rose-400' : 'bg-gray-800/80 border-gray-700 text-gray-400 hover:text-rose-400 hover:border-rose-500/30' }}">
                                    <i class="{{ $isLiked ? 'fa-solid' : 'fa-regular' }} fa-heart text-xs"></i>
                                    <span>{{ $review->likes_count ?? (method_exists($review, 'comments') ? $review->likes->count() : 0) }}</span>
                                </button>
                            </form>

                            <!-- 💬 コメント開閉ボタン -->
                            <button @click="showComments = !showComments"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border transition cursor-pointer font-bold {{ $hasCommented ? 'bg-amber-500/20 border-amber-500/40 text-amber-400' : 'bg-gray-800/80 border-gray-700 text-gray-400 hover:text-amber-400 hover:border-amber-500/40' }}">
                                <i class="{{ $hasCommented ? 'fa-solid' : 'fa-regular' }} fa-comment text-xs"></i>
                                <span>{{ $review->comments_count ?? (method_exists($review, 'comments') ? $review->comments->count() : 0) }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- 💬 コメント表示＆投稿エリア -->
                    <div x-show="showComments" x-cloak class="pt-3 mt-3 border-t border-gray-800/80 space-y-3">

                        <!-- 💬 既存のコメント一覧 -->
                        @if(method_exists($review, 'comments') && $review->comments->count() > 0)
                            <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                                @foreach($review->comments->sortByDesc('created_at') as $comment)
                                    @php
                                        $isMyComment = Auth::check() && $comment->user_id === Auth::id();
                                    @endphp

                                    <div
                                        class="p-2.5 rounded-lg text-xs space-y-1 transition-all duration-300 {{ $isMyComment ? 'bg-amber-500/10 border border-amber-500/50 shadow-[0_0_12px_rgba(245,158,11,0.25)]' : 'bg-[#182130] border border-gray-800' }}">
                                        <div class="flex items-center justify-between text-gray-400">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold {{ $isMyComment ? 'text-amber-400' : 'text-gray-300' }}">
                                                    {{ $comment->user->nickname ?? $comment->user->name ?? '匿名' }}
                                                </span>
                                                @if($isMyComment)
                                                    <span
                                                        class="text-[9px] bg-amber-500 text-black font-extrabold px-1.5 py-0.2 rounded-full">あなた</span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-[9px] text-gray-500">{{ $comment->created_at ? $comment->created_at->diffForHumans() : '' }}</span>

                                                @if($isMyComment)
                                                    <form action="{{ route('reviews.comments.destroy', $comment->id) }}" method="POST"
                                                        onsubmit="return confirm('このコメントを削除してもよろしいですか？');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-gray-500 hover:text-rose-400 transition cursor-pointer p-0.5"
                                                            title="削除">
                                                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="{{ $isMyComment ? 'text-amber-100 font-medium' : 'text-gray-200' }} leading-snug">
                                            {{ $comment->content ?? $comment->comment }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-500 text-center py-1">まだコメントはありません</p>
                        @endif

                        <!-- コメント投稿フォーム -->
                        <form action="{{ route('reviews.comments.store', $review->id ?? $review->review_id) }}"
                            method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="comment" required placeholder="コメントを入力..."
                                class="flex-grow bg-[#182130] border border-gray-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-amber-500 transition">
                            <button type="submit"
                                class="bg-amber-500 hover:bg-amber-400 text-black font-bold text-xs px-3 py-1.5 rounded-lg transition shrink-0 cursor-pointer">
                                送信
                            </button>
                        </form>

                    </div>

                </div>
            @empty
                <div class="bg-[#121824] border border-gray-800 rounded-2xl p-12 text-center">
                    <p class="text-sm text-gray-400">該当する気分のレビューはまだ投稿されていません。</p>
                </div>
            @endforelse
        </div>

        <!-- ページネーションコンテナ -->
        <div class="mt-6 flex justify-center custom-pagination">
            {{ $reviews->links() }}
        </div>

        <style>
            /* ページネーション全体のデザインをダークテーマになじませる */
            .custom-pagination nav {
                background-color: transparent !important;
                box-shadow: none !important;
            }

            .custom-pagination nav div:first-child {
                display: none;
            }

            .custom-pagination nav span[aria-current="page"] span,
            .custom-pagination nav a {
                background-color: #1f2937 !important;
                color: #9ca3af !important;
                border: 1px solid #374151 !important;
                padding: 6px 12px !important;
                font-size: 0.75rem !important;
                border-radius: 0.375rem !important;
                margin: 0 2px !important;
                transition: all 0.2s;
            }

            .custom-pagination nav a:hover {
                background-color: #374151 !important;
                color: #ffffff !important;
            }

            .custom-pagination nav span[aria-current="page"] span {
                background-color: #f59e0b !important;
                color: #000000 !important;
                font-weight: bold !important;
                border-color: #f59e0b !important;
            }
        </style>
        <!-- トップへ戻るボタン (画面右下に固定) -->
        <div x-data="{ showTopBtn: false }"
            x-init="window.addEventListener('scroll', () => { showTopBtn = window.scrollY > 300 })"
            class="fixed bottom-6 right-6 z-50">
            <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })" x-show="showTopBtn"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-4"
                class="bg-amber-500 hover:bg-amber-400 text-black font-bold p-3.5 rounded-full shadow-2xl flex items-center justify-center transition cursor-pointer border border-amber-300/50"
                title="ページ上部へ戻る">
                <i class="fa-solid fa-arrow-up text-base"></i>
            </button>
        </div>

    </div>
</x-app-layout>