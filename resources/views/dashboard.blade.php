<x-app-layout>
    <div class="py-12 pt-28 bg-black min-h-screen flex flex-col items-center text-white">

        <!-- メインカードコンテナ -->
        <div
            class="w-full max-w-md px-6 py-8 bg-gray-900 shadow-md rounded-[2.5rem] border border-gray-700 flex flex-col items-center">

            <!-- ヘッダー -->
            <header class="text-center mb-4">
                <h1 class="text-3xl font-bold text-yellow-500 tracking-wider">MovieMood</h1>
                <p class="text-xs text-gray-400 mt-1">マイページ</p>
            </header>

            <!-- ユーザー情報 -->
            <div class="w-full flex flex-col items-center mb-6">
                <p class="text-yellow-500 font-semibold text-lg mb-3">
                    Welcome {{ Auth::user()->nickname ?? Auth::user()->name }} さん！
                </p>

                <div
                    class="flex items-center space-x-3 bg-gray-800 px-6 py-3 rounded-full border border-gray-700 w-11/12 justify-center">
                    <div
                        class="w-12 h-12 rounded-full bg-white overflow-hidden border-2 border-yellow-500 flex items-center justify-center shadow-md">
                        @if (Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-[10px] text-gray-400">画像なし</span>
                        @endif
                    </div>
                    <span class="text-white font-bold text-lg truncate max-w-[150px]">
                        {{ Auth::user()->nickname ?? Auth::user()->name }}
                    </span>
                </div>
            </div>

            <!-- タブエリア（Alpine.jsによる切り替え） -->
            <div x-data="{ tab: 'likes' }" class="w-full">
                <!-- タブボタン -->
                <div class="flex border-b border-gray-700 mb-4 justify-around">
                    <button @click="tab = 'reviews'"
                        :class="tab === 'reviews' ? 'text-yellow-500 border-b-2 border-yellow-500 font-bold' : 'text-gray-400'"
                        class="pb-2 px-2 text-sm transition">
                        投稿 ({{ count($myReviews ?? []) }})
                    </button>
                    <button @click="tab = 'likes'"
                        :class="tab === 'likes' ? 'text-yellow-500 border-b-2 border-yellow-500 font-bold' : 'text-gray-400'"
                        class="pb-2 px-2 text-sm transition">
                        LIKES ({{ count($likedReviews ?? []) }})
                    </button>
                    <button @click="tab = 'watchlist'"
                        :class="tab === 'watchlist' ? 'text-yellow-500 border-b-2 border-yellow-500 font-bold' : 'text-gray-400'"
                        class="pb-2 px-2 text-sm transition">
                        観たい ({{ count($watchlistMovies ?? []) }})
                    </button>
                </div>

                <!-- 1. 自分の投稿一覧 -->
                <div x-show="tab === 'reviews'" class="space-y-3">
                    @forelse($myReviews ?? [] as $review)
                        <div class="bg-gray-800 p-3 rounded-xl border border-gray-700 text-left">
                            <div class="flex justify-between items-center mb-1">
                                <span
                                    class="font-bold text-sm text-yellow-400 truncate max-w-[200px]">{{ $review->movie_title }}</span>
                                <span class="text-xs text-amber-400 font-bold">★
                                    {{ number_format($review->rating, 1) }}</span>
                            </div>
                            <p class="text-xs text-gray-300 mt-1 line-clamp-2">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <p class="text-center text-xs text-gray-500 py-4">投稿したレビューはありません</p>
                    @endforelse
                </div>

                <!-- 2. LIKES（いいねしたレビュー一覧） -->
                <div x-show="tab === 'likes'" class="space-y-3">
                    @forelse($likedReviews ?? [] as $review)
                        <div class="bg-gray-800 p-3 rounded-xl border border-gray-700 text-left">
                            <div class="flex justify-between items-center mb-1">
                                <span
                                    class="font-bold text-sm text-yellow-400 truncate max-w-[180px]">{{ $review->movie_title }}</span>
                                <span class="text-xs text-amber-400 font-bold">★
                                    {{ number_format($review->rating, 1) }}</span>
                            </div>
                            <p class="text-xs text-gray-300 mt-1 line-clamp-2">{{ $review->comment }}</p>
                            <div class="mt-2 flex justify-between items-center text-[10px] text-gray-400">
                                <span>投稿者: {{ $review->user->nickname ?? $review->user->name ?? '匿名' }}</span>
                                <a href="{{ route('movies.show', $review->tmdb_id) }}"
                                    class="text-yellow-500 hover:underline">映画を見る ＞</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-xs text-gray-500 py-4">いいねしたレビューはありません</p>
                    @endforelse
                </div>

                <!-- 3. ウォッチリスト一覧 -->
                <div x-show="tab === 'watchlist'" class="space-y-3">
                    @forelse($watchlistMovies ?? [] as $movie)
                        <div class="bg-gray-800 p-3 rounded-xl border border-gray-700 flex justify-between items-center">
                            <span class="font-bold text-sm text-white truncate max-w-[200px]">{{ $movie->title }}</span>
                            <a href="{{ route('movies.show', $movie->tmdb_id) }}"
                                class="text-xs text-yellow-500 hover:underline">詳細 ＞</a>
                        </div>
                    @empty
                        <p class="text-center text-xs text-gray-500 py-4">ウォッチリストは空です</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- 下部アクションボタン -->
        <div class="w-full max-w-md flex items-center justify-between px-6 mt-6">
            <a href="{{ route('profile.edit') }}"
                class="flex items-center space-x-1.5 px-3 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-full text-xs shadow-md transition">
                <span>編集⚙️</span>
            </a>

            <a href="{{ route('community.index') }}"
                class="flex items-center space-x-1.5 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-black font-bold rounded-full text-xs shadow-md transition">
                <span>コミュニティへ 👥</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full text-xs shadow-md transition">
                    ログアウト
                </button>
            </form>
        </div>

    </div>
</x-app-layout>