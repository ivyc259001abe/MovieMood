<x-app-layout :popularMovies="$popularMovies ?? []">
    <div class="max-w-6xl mx-auto space-y-6">

        <!-- 👤 プロフィールヘッダーカード -->
        <div
            class="bg-gray-900/90 rounded-2xl p-6 border border-gray-800 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">

            <!-- 左側：アバター ＆ ユーザー情報 -->
            <div class="flex items-center gap-5 w-full sm:w-auto">
                <div
                    class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-amber-500 overflow-hidden bg-gray-800 flex items-center justify-center shadow-md flex-shrink-0">
                    @php
                        $user = auth()->user();
                        $avatarPath = $user->avatar ?? $user->icon ?? $user->avatar_url ?? null;
                        $hasAvatar = false;
                        if ($avatarPath) {
                            if (str_starts_with($avatarPath, 'http')) {
                                $userIcon = $avatarPath;
                                $hasAvatar = true;
                            } elseif (file_exists(public_path($avatarPath))) {
                                $userIcon = asset($avatarPath);
                                $hasAvatar = true;
                            }
                        }
                    @endphp

                    @if($hasAvatar)
                        <img src="{{ $userIcon }}" alt="プロフィール画像" class="w-full h-full object-cover">
                    @else
                        <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    @endif
                </div>

                <div class="space-y-1">
                    <span class="text-xs text-amber-500 font-bold tracking-wider uppercase block">MY PROFILE</span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-white">
                        {{ auth()->user()->nickname ?? auth()->user()->name ?? 'ユーザー' }}
                    </h1>
                    <p class="text-xs text-gray-400 font-medium">
                        {{ auth()->user()->email }}
                    </p>
                </div>
            </div>

            <!-- 右側：アカウント編集ボタン -->
            <div class="w-full sm:w-auto flex justify-end">
                <a href="{{ route('profile.edit') }}"
                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-800 hover:bg-amber-500 hover:text-black text-amber-400 font-bold rounded-full text-xs transition border border-gray-700 shadow-md">
                    <i class="fa-solid fa-gear"></i> アカウントを編集
                </a>
            </div>

        </div>

        <!-- 📑 下部コンテンツ共有エリア（WATCHLIST / MY REVIEWS / LIKES） -->
        <div class="bg-gray-900/90 rounded-2xl border border-gray-800 shadow-xl overflow-hidden"
            x-data="{ tab: 'watchlist' }">

            <!-- タブ切り替えボタン -->
            <div class="flex border-b border-gray-800 text-xs font-bold text-center bg-black/40">
                <button type="button" @click="tab = 'watchlist'"
                    :class="tab === 'watchlist' ? 'text-amber-500 border-b-2 border-amber-500 bg-gray-900/50' : 'text-gray-400 hover:text-gray-200'"
                    class="flex-1 py-4 px-2 transition flex items-center justify-center gap-2">
                    <span>📌</span>
                    <span>WATCHLIST</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-800 text-gray-300">
                        {{ is_array($watchlist ?? null) || ($watchlist ?? null) instanceof \Countable ? count($watchlist) : 0 }}
                    </span>
                </button>

                <button type="button" @click="tab = 'reviews'"
                    :class="tab === 'reviews' ? 'text-amber-500 border-b-2 border-amber-500 bg-gray-900/50' : 'text-gray-400 hover:text-gray-200'"
                    class="flex-1 py-4 px-2 transition flex items-center justify-center gap-2">
                    <span>✍️</span>
                    <span>MY REVIEWS</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-800 text-gray-300">
                        {{ is_array($myReviews ?? null) || ($myReviews ?? null) instanceof \Countable ? count($myReviews) : 0 }}
                    </span>
                </button>

                <button type="button" @click="tab = 'likes'"
                    :class="tab === 'likes' ? 'text-amber-500 border-b-2 border-amber-500 bg-gray-900/50' : 'text-gray-400 hover:text-gray-200'"
                    class="flex-1 py-4 px-2 transition flex items-center justify-center gap-2">
                    <span>💖</span>
                    <span>LIKES</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-800 text-gray-300">
                        {{ is_array($likedMovies ?? null) || ($likedMovies ?? null) instanceof \Countable ? count($likedMovies) : 0 }}
                    </span>
                </button>
            </div>

            <!-- 1️⃣ タブ：ウォッチリスト表示エリア -->
            <div x-show="tab === 'watchlist'" class="p-6">
                @if(!empty($watchlist) && count($watchlist) > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($watchlist as $movie)
                            <a href="{{ route('movies.show', $movie['id'] ?? $movie->id) }}" class="group block space-y-2">
                                <div
                                    class="relative overflow-hidden rounded-xl border border-gray-800 shadow-md group-hover:border-amber-500 transition">
                                    <img src="{{ !empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w300' . $movie['poster_path'] : 'https://via.placeholder.com/300x450' }}"
                                        alt="{{ $movie['title'] ?? '映画' }}"
                                        class="w-full h-44 object-cover group-hover:scale-105 transition duration-300">
                                </div>
                                <p class="text-xs font-bold text-gray-300 group-hover:text-amber-500 truncate text-center">
                                    {{ $movie['title'] ?? '無題' }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center space-y-2">
                        <div class="text-3xl text-gray-600">📌</div>
                        <p class="text-xs text-gray-400 font-bold">ウォッチリストに登録された映画はありません。</p>
                        <p class="text-[11px] text-gray-500">気になる映画を見つけて保存してみましょう！</p>
                    </div>
                @endif
            </div>

            <!-- 2️⃣ タブ：自分の投稿レビュー表示エリア -->
            <div x-show="tab === 'reviews'" class="p-6" style="display: none;">
                @if(!empty($myReviews) && count($myReviews) > 0)
                    <div class="space-y-4">
                        @foreach($myReviews as $review)
                            <div
                                class="p-4 bg-black/40 rounded-xl border border-gray-800 space-y-2 transition hover:border-gray-700">
                                <div class="flex items-center justify-between border-b border-gray-800/80 pb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-amber-500 font-bold text-xs">🎬</span>
                                        <a href="{{ route('movies.show', $review->movie_id ?? 1) }}"
                                            class="text-xs font-bold text-gray-200 hover:text-amber-400 transition">
                                            {{ $review->movie_title ?? '対象の映画' }}
                                        </a>
                                    </div>
                                    <span class="text-xs text-amber-400 font-bold">⭐
                                        {{ number_format($review->rating ?? 0, 1) }}</span>
                                </div>
                                <p class="text-xs text-gray-300 leading-relaxed pt-1">
                                    {{ $review->comment ?? $review->body }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center space-y-2">
                        <div class="text-3xl text-gray-600">✍️</div>
                        <p class="text-xs text-gray-400 font-bold">まだ投稿したレビューはありません。</p>
                        <p class="text-[11px] text-gray-500">観た映画の感想を共有してみましょう！</p>
                    </div>
                @endif
            </div>

            <!-- 3️⃣ タブ：いいねした映画表示エリア -->
            <div x-show="tab === 'likes'" class="p-6" style="display: none;">
                @if(!empty($likedMovies) && count($likedMovies) > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($likedMovies as $movie)
                            <a href="{{ route('movies.show', $movie['id'] ?? $movie->id) }}" class="group block space-y-2">
                                <div
                                    class="relative overflow-hidden rounded-xl border border-gray-800 shadow-md group-hover:border-amber-500 transition">
                                    <img src="{{ !empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w300' . $movie['poster_path'] : 'https://via.placeholder.com/300x450' }}"
                                        alt="{{ $movie['title'] ?? '映画' }}"
                                        class="w-full h-44 object-cover group-hover:scale-105 transition duration-300">
                                </div>
                                <p class="text-xs font-bold text-gray-300 group-hover:text-amber-500 truncate text-center">
                                    {{ $movie['title'] ?? '無題' }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center space-y-2">
                        <div class="text-3xl text-gray-600">💖</div>
                        <p class="text-xs text-gray-400 font-bold">いいねした映画はありません。</p>
                        <p class="text-[11px] text-gray-500">お気に入りの映画にいいねを押してみましょう！</p>
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-app-layout>