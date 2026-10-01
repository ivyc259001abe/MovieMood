<x-app-layout>
    {{-- ジャンプした時にレビュー枠を少しハイライトするスタイルの追加 --}}
    <style>
        :target {
            animation: highlight 2s ease-in-out;
        }

        @keyframes highlight {
            0% {
                background-color: rgba(245, 158, 11, 0.25);
                border-color: rgba(245, 158, 11, 0.8);
            }

            100% {
                background-color: #1a2332;
            }
        }
    </style>

    <div class="py-6 sm:py-10 px-4 flex justify-center">
        <!-- メインカード -->
        <div class="w-full max-w-md bg-[#121824] border border-gray-700/80 rounded-2xl p-4 shadow-2xl space-y-4">

            <!-- サブヘッダー (戻る) -->
            <div class="flex items-center justify-between border-b border-gray-800 pb-2">
                <button onclick="history.back()"
                    class="text-xs text-gray-400 hover:text-white flex items-center space-x-1 transition">
                    <span>← 戻る</span>
                </button>
                <span class="text-xs font-bold text-amber-400">すべてのレビュー</span>
            </div>

            <!-- 作品情報ヘッダー -->
            <div class="flex items-center space-x-3 bg-[#1a2332] p-2.5 rounded-xl border border-gray-800">
                @if(!empty($movie['poster_path']))
                    <img src="https://image.tmdb.org/t/p/w200{{ $movie['poster_path'] }}" alt="{{ $movie['title'] ?? '' }}"
                        class="w-12 h-16 object-cover rounded shadow">
                @endif
                <div>
                    <h2 class="text-sm font-bold text-white leading-snug">{{ $movie['title'] ?? '映画作品' }}</h2>
                    <p class="text-[11px] text-amber-400 font-bold mt-0.5">
                        ★ {{ number_format($movie['vote_average'] ?? 0, 1) }} <span class="text-gray-400 text-[9px]">/
                            10</span>
                    </p>
                </div>
            </div>

            <!-- レビュー一覧 -->
            <div class="space-y-2.5">
                @forelse($reviews as $rev)
                    {{-- id="review-{{ $rev->id }}" を付与して直接ジャンプ可能 --}}
                    <div id="review-{{ $rev->id }}"
                        class="bg-[#1a2332] border border-gray-800 rounded-lg p-3 space-y-1.5 text-xs scroll-mt-4 transition-all duration-300">
                        <!-- ユーザー情報 & 気分タグ -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-1.5">
                                <div class="w-4 h-4 rounded-full bg-gray-600 flex items-center justify-center text-[8px]">👤
                                </div>
                                <span class="font-bold text-gray-200 text-[11px]">
                                    {{ $rev->user->nickname ?? $rev->user->name ?? '匿名' }}
                                </span>
                                <span class="text-amber-400 text-[10px] font-bold">★ {{ $rev->rating }}</span>
                            </div>
                            @if(!empty($rev->moods))
                                <span
                                    class="text-[9px] text-amber-400 bg-amber-500/10 border border-amber-500/20 px-1.5 py-0.5 rounded font-bold">
                                    {{ $rev->moods }}
                                </span>
                            @endif
                        </div>

                        <!-- コメント内容 -->
                        <p class="text-gray-300 text-[11px] pl-5 leading-relaxed">
                            {{ $rev->comment }}
                        </p>

                        <!-- 日時 & いいねボタン -->
                        <div class="flex items-center justify-between pt-1 border-t border-gray-800/60 text-[10px] pl-5">
                            <span class="text-gray-400">
                                {{ isset($rev->created_at) ? $rev->created_at->format('Y/m/d H:i') : '' }}
                            </span>

                            @auth
                                <form action="{{ route('reviews.like', $rev->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center space-x-1 px-2 py-0.5 rounded-full border transition {{ ($rev->likes && $rev->likes->contains('user_id', Auth::id())) ? 'bg-red-500/20 text-red-400 border-red-500/40' : 'bg-gray-800 text-gray-400 border-gray-700 hover:text-red-400' }}">
                                        <span>❤️</span>
                                        <span class="font-bold">{{ $rev->likes ? $rev->likes->count() : 0 }}</span>
                                    </button>
                                </form>
                            @else
                                <div class="flex items-center space-x-1 text-gray-400">
                                    <span>❤️</span>
                                    <span class="font-bold">{{ $rev->likes ? $rev->likes->count() : 0 }}</span>
                                </div>
                            @endauth
                        </div>
                    </div>
                @empty
                    <p class="text-center text-xs text-gray-500 py-6">まだレビューが投稿されていません。</p>
                @endforelse
            </div>

            <!-- ページネーション -->
            @if($reviews->hasPages())
                <div class="pt-2">
                    {{ $reviews->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>