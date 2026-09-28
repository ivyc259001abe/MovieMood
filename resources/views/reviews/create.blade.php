<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

        <!-- ← 一つ前に戻る リンク -->
        <div class="mb-2.5">
            <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('movies.show', $movie['id'] ?? $movie->id ?? 0) }}"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>一つ前に戻る</span>
            </a>
        </div>

        @php
            $posterPath = $movie['poster_path'] ?? $movie->poster_path ?? null;
            $posterUrl = $posterPath
                ? (str_starts_with($posterPath, 'http') ? $posterPath : 'https://image.tmdb.org/t/p/w500' . $posterPath)
                : null;
            $voteAverage = $movie['vote_average'] ?? $movie->vote_average ?? null;
            $releaseDate = $movie['release_date'] ?? $movie->release_date ?? null;
            $runtime = $movie['runtime'] ?? $movie->runtime ?? null;
            $director = $movie['director'] ?? $movie->director ?? null;
            $overview = $movie['overview'] ?? $movie->overview ?? null;
        @endphp

        <!-- 🎬 左右完全等幅（5:5 = grid-cols-2） ＆ 高さ完全一致（items-stretch） -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-stretch">

            <!-- 【左側】映画情報カード -->
            <div
                class="bg-gray-900/90 border border-gray-800 rounded-2xl shadow-2xl overflow-hidden relative flex flex-col justify-between">

                <!-- 上部コンテンツ（タイトル・ポスターサムネイル・あらすじ） -->
                <div class="p-4 sm:p-5 space-y-3.5 relative z-10 flex-1">
                    <!-- 上部：小ポスター ＆ タイトル情報 -->
                    <div class="flex gap-3.5 items-start">
                        <!-- サムネイルポスター -->
                        <div
                            class="w-24 sm:w-28 shrink-0 aspect-[2/3] rounded-xl overflow-hidden border border-gray-700/80 shadow-md bg-black/50">
                            @if($posterUrl)
                                <img src="{{ $posterUrl }}" alt="{{ $movie['title'] ?? $movie->title ?? '映画ポスター' }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-500 gap-1">
                                    <i class="fa-solid fa-film text-xl"></i>
                                    <span class="text-[10px]">No Image</span>
                                </div>
                            @endif
                        </div>

                        <!-- タイトル ＆ 詳細情報 -->
                        <div class="space-y-1.5 flex-1 min-w-0">
                            <h1 class="text-lg sm:text-xl font-black text-white leading-tight">
                                {{ $movie['title'] ?? $movie->title ?? '映画タイトル' }}
                            </h1>

                            <div class="flex flex-wrap items-center gap-2.5 text-xs font-bold text-gray-300">
                                @if($voteAverage)
                                    <div class="flex items-center gap-1 text-amber-400">
                                        <i class="fa-solid fa-star"></i>
                                        <span>{{ number_format((float) $voteAverage, 1) }}</span>
                                    </div>
                                @endif

                                @if($releaseDate)
                                    <div class="flex items-center gap-1 text-gray-300">
                                        <i class="fa-regular fa-calendar-days text-amber-500"></i>
                                        <span>{{ date('Y年n月', strtotime($releaseDate)) }}</span>
                                    </div>
                                @endif

                                @if($runtime)
                                    <div class="flex items-center gap-1 text-gray-300">
                                        <i class="fa-regular fa-clock text-amber-500"></i>
                                        <span>{{ $runtime }}分</span>
                                    </div>
                                @endif
                            </div>

                            @if($director)
                                <div class="text-xs text-gray-400">
                                    監督: <span class="text-gray-200 font-medium">{{ $director }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- あらすじエリア（日本語訳が無い場合はその旨を表示） -->
                    <div class="bg-black/60 backdrop-blur-md border border-gray-800 p-3 rounded-xl space-y-1">
                        <span class="text-xs font-extrabold text-amber-500 flex items-center gap-1">
                            <i class="fa-solid fa-align-left"></i>
                            あらすじ
                        </span>
                        @if(!empty($overview))
                            <p class="text-xs text-gray-300 leading-relaxed line-clamp-3">
                                {{ $overview }}
                            </p>
                        @else
                            <p class="text-xs text-gray-400 leading-relaxed italic">
                                ※日本語あらすじ情報は準備中です。
                            </p>
                        @endif
                    </div>
                </div>

                <!-- 🖼️ 下部：ポスター画像が「チラ見え（見切り）」するビジュアルエリア -->
                <div
                    class="relative w-full h-40 sm:h-48 overflow-hidden border-t border-gray-800/80 bg-black/60 shrink-0">
                    @if($posterUrl)
                        <img src="{{ $posterUrl }}" alt="Movie Visual"
                            class="w-full h-full object-cover object-top opacity-60 hover:opacity-80 transition duration-500">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-gray-900/80 pointer-events-none">
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-600 text-xs font-bold">Movie
                            Mood Visual</div>
                    @endif
                </div>

            </div>

            <!-- 【右側】レビュー投稿フォームカード -->
            <div
                class="bg-gray-900/90 border border-gray-800 rounded-2xl p-4 sm:p-5 shadow-2xl flex flex-col justify-between">
                <form action="{{ route('reviews.store', $movie['id'] ?? $movie->id ?? 0) }}" method="POST"
                    class="space-y-3.5 flex flex-col justify-between h-full">
                    @csrf
                    <input type="hidden" name="movie_title" value="{{ $movie['title'] ?? $movie->title ?? '' }}">
                    <input type="hidden" name="poster_path"
                        value="{{ $movie['poster_path'] ?? $movie->poster_path ?? '' }}">

                    <div class="space-y-3.5">
                        <div class="flex items-center gap-2 border-b border-gray-800 pb-2.5">
                            <i class="fa-solid fa-pen-to-square text-amber-500 text-base"></i>
                            <h2 class="text-base font-extrabold text-white">あなたのレビュー・気分を投稿</h2>
                        </div>

                        <!-- ★ 評価スライダー -->
                        <div class="space-y-1.5 bg-black/40 border border-gray-800 p-3 rounded-xl"
                            x-data="{ rating: 7.0 }">
                            <div class="flex items-center justify-between">
                                <label for="rating_number_input"
                                    class="text-xs font-bold text-gray-200 flex items-center gap-1.5 cursor-pointer">
                                    <i class="fa-solid fa-star text-amber-500"></i>
                                    <span>評価 (0.1刻み)</span>
                                </label>

                                <span class="text-xs font-bold text-amber-400" x-text="
                                        rating >= 9.5 ? '🏆 最高傑作' :
                                        rating >= 9.0 ? '🌟 超大作' :
                                        rating >= 8.0 ? '👏 素晴らしい' :
                                        rating >= 7.0 ? '👍 かなりおすすめ' :
                                        rating >= 6.0 ? '🙂 面白い' :
                                        rating >= 5.0 ? '😐 普通' :
                                        rating >= 3.0 ? '🤔 イマイチ' : '👎 時間の無駄'
                                      ">
                                </span>
                            </div>

                            <div class="flex items-center space-x-3 pt-0.5">
                                <input type="range" id="rating_range_input" aria-label="評価スライダー" min="1.0" max="10.0"
                                    step="0.1" x-model="rating"
                                    class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer accent-amber-500 focus:outline-none">

                                <div class="relative flex items-center shrink-0 w-20">
                                    <span class="absolute left-2 text-amber-400 font-black text-xs">★</span>
                                    <input type="number" id="rating_number_input" name="rating" min="1.0" max="10.0"
                                        step="0.1" x-model="rating"
                                        class="w-full pl-5 pr-1 py-1 bg-black/80 text-amber-400 font-black text-xs border border-amber-500/40 rounded-lg focus:outline-none focus:border-amber-400 text-center shadow-inner">
                                </div>
                            </div>
                        </div>

                        <!-- 🎭 4つの気分タグ -->
                        <div class="space-y-1.5">
                            <span id="moods_label"
                                class="block text-xs font-bold text-gray-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-face-smile text-amber-500"></i>
                                鑑賞後の「気分」は？ <span class="text-gray-400 font-normal text-[11px]">（複数選択可）</span>
                            </span>

                            <div class="grid grid-cols-2 gap-2" role="group" aria-labelledby="moods_label">
                                @foreach([
                                        '#号泣' => '😭',
                                        '#スカッと' => '😆',
                                        '#ハラハラ' => '😱',
                                        '#キュン' => '💖'
                                    ] as $moodVal => $emoji)
                                    <label
                                        class="relative flex items-center justify-center gap-2 p-2 rounded-xl border border-gray-700 bg-black/40 cursor-pointer select-none transition-all hover:border-gray-500 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-500/20 has-[:checked]:text-amber-400 shadow-sm">
                                        <input type="checkbox" name="moods[]" value="{{ $moodVal }}" class="sr-only">
                                        <span class="text-sm">{{ $emoji }}</span>
                                        <span class="text-xs font-bold text-gray-200">{{ $moodVal }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- 💬 感想入力エリア -->
                        <div class="space-y-1">
                            <label for="comment_input"
                                class="block text-xs font-bold text-gray-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-comment-dots text-amber-500"></i>
                                感想・レビュー本文
                            </label>
                            <textarea id="comment_input" name="comment" rows="2.5"
                                placeholder="この作品の見どころや感じたことを自由に書いてみよう..."
                                class="w-full bg-black/60 border border-gray-700/80 text-white text-xs rounded-xl p-2.5 focus:border-amber-500 focus:outline-none transition leading-relaxed shadow-inner resize-none"
                                required></textarea>
                        </div>
                    </div>

                    <!-- 🔘 フッターボタン -->
                    <div class="pt-2.5 border-t border-gray-800/80 flex items-center justify-end gap-3 mt-2">
                        <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('movies.show', $movie['id'] ?? $movie->id ?? 0) }}"
                            class="text-xs font-bold text-gray-400 hover:text-white transition px-3 py-1.5">
                            キャンセル
                        </a>

                        <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-black font-black text-xs rounded-xl transition-all shadow-lg active:scale-95 flex items-center gap-1.5">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>レビューを投稿する</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>