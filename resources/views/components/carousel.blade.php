@props(['popularMovies' => []])

<div class="w-full">
    <!-- 🌟 見出しテキスト -->
    <div class="text-center mb-1">
        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
            POPULAR MOVIES <span class="text-gray-600 font-normal">（タップで詳細へ）</span>
        </span>
    </div>

    <!-- 🎬 スリム化されたカルーセル本体 -->
    <div class="carousel-container overflow-hidden w-full relative py-0.5">
        <div class="carousel-track flex space-x-2.5 w-max">
            @php
                $moviesList = !empty($popularMovies) ? array_merge($popularMovies, $popularMovies) : [];
            @endphp

            @forelse($moviesList as $movie)
                <a href="{{ route('movies.show', $movie['id']) }}"
                    class="group flex-shrink-0 w-16 sm:w-20 flex flex-col items-center">
                    <!-- ポスターサイズを横64px / 縦96px（スマホ時は横64px / 縦80px）に固定 -->
                    <div
                        class="overflow-hidden rounded-md border border-gray-800 shadow group-hover:border-amber-500 transition duration-300">
                        <img src="{{ !empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w200' . $movie['poster_path'] : 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=200&q=80' }}"
                            alt="{{ $movie['title'] ?? '映画' }}"
                            class="w-16 sm:w-20 h-20 sm:h-24 object-cover group-hover:scale-105 transition duration-300"
                            loading="lazy">
                    </div>
                    <p
                        class="text-[9px] font-semibold text-gray-400 group-hover:text-amber-400 truncate w-16 sm:w-20 text-center mt-0.5">
                        {{ $movie['title'] ?? '' }}
                    </p>
                </a>
            @empty
                <p class="text-xs text-gray-500 py-1">映画情報を読み込めませんでした。</p>
            @endforelse
        </div>
    </div>
</div>

<style>
    .carousel-track {
        animation: scroll 60s linear infinite;
    }

    .carousel-container:hover .carousel-track {
        animation-play-state: paused;
    }

    @keyframes scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }
</style>