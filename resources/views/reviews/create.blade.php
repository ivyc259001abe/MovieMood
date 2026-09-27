<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- 1. ページヘッダー（コミュニティ画面・他ページと共通のトーン） -->
        <div class="bg-gray-900/90 rounded-2xl border border-gray-800 shadow-xl p-6 mb-8">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-500 text-2xl shadow-inner shrink-0">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <span class="text-[10px] text-amber-500 font-extrabold tracking-widest uppercase block mb-0.5">
                        MOVIE REVIEW & MOOD
                    </span>
                    <h1 class="text-2xl font-black text-white flex items-center gap-2">
                        作品レビューを<span class="text-amber-500">投稿する</span>
                    </h1>
                    <p class="text-xs text-gray-400 mt-1">あなたの観賞後の生の感情や評価を共有して、コミュニティを盛り上げよう！</p>
                </div>
            </div>
        </div>

        <!-- 2. レビュー投稿メインカード（HOME・コミュニティフォームと完全共通化） -->
        <div class="bg-gray-900/90 rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-2xl space-y-6">

            <!-- 🎬 対象映画タイトルヘッダー -->
            <div class="bg-black/50 border border-gray-800/80 rounded-xl p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-500 text-sm shrink-0">
                        <i class="fa-solid fa-film"></i>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-400 block font-bold">対象作品</span>
                        <h2 class="text-base font-extrabold text-white">
                            {{ $movie['title'] ?? $movie->title ?? '映画タイトル' }}</h2>
                    </div>
                </div>
            </div>

            <form action="{{ route('reviews.store', $movie['id'] ?? $movie->id ?? 0) }}" method="POST"
                class="space-y-6">
                @csrf
                <input type="hidden" name="movie_title" value="{{ $movie['title'] ?? $movie->title ?? '' }}">
                <input type="hidden" name="poster_path"
                    value="{{ $movie['poster_path'] ?? $movie->poster_path ?? '' }}">

                <!-- ★ 評価 (10点満点・TMDB API互換) ＆ 🎭 4つの気分タグ -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-6">

                    <!-- 評価選択（10点満点） -->
                    <div class="sm:col-span-5 space-y-2">
                        <label for="rating_select"
                            class="block text-xs font-bold text-gray-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-star text-amber-500"></i>
                            評価 (10点満点)
                        </label>
                        <select id="rating_select" name="rating"
                            class="w-full bg-black/60 border border-gray-700/80 text-amber-400 font-bold text-sm rounded-xl px-4 py-3 focus:border-amber-500 focus:outline-none transition shadow-inner">
                            <option value="10.0">★ 10.0 (最高のおすすめ)</option>
                            <option value="9.5">★ 9.5</option>
                            <option value="9.0">★ 9.0 (超大作・大傑作)</option>
                            <option value="8.5">★ 8.5</option>
                            <option value="8.0">★ 8.0 (とても素晴らしい)</option>
                            <option value="7.5">★ 7.5</option>
                            <option value="7.0" selected>★ 7.0 (かなりおすすめ)</option>
                            <option value="6.5">★ 6.5</option>
                            <option value="6.0">★ 6.0 (面白い)</option>
                            <option value="5.5">★ 5.5</option>
                            <option value="5.0">★ 5.0 (普通)</option>
                            <option value="4.0">★ 4.0 (少し物足りない)</option>
                            <option value="3.0">★ 3.0 (イマイチ)</option>
                            <option value="2.0">★ 2.0 (おすすめしない)</option>
                            <option value="1.0">★ 1.0 (時間の無駄)</option>
                        </select>
                    </div>

                    <!-- 🎭 4つの気分タグ（複数タップ選択可能・統一ボタンデザイン） -->
                    <div class="sm:col-span-7 space-y-2">
                        <span class="block text-xs font-bold text-gray-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-face-smile text-amber-500"></i>
                            鑑賞後の「気分」は？ <span class="text-gray-400 font-normal text-[11px]">（複数選択可）</span>
                        </span>

                        <div class="grid grid-cols-2 gap-2.5">
                            @foreach([
                                    '#号泣' => '😭',
                                    '#スカッと' => '😆',
                                    '#ハラハラ' => '😱',
                                    '#キュン' => '💖'
                                ] as $moodVal => $emoji)
                                <label
                                    class="relative flex items-center justify-center gap-2 p-3 rounded-xl border border-gray-700 bg-black/40 cursor-pointer select-none transition-all hover:border-gray-500 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-500/20 has-[:checked]:text-amber-400 shadow-sm">
                                    <input type="checkbox" name="moods[]" value="{{ $moodVal }}" class="sr-only">
                                    <span class="text-base">{{ $emoji }}</span>
                                    <span class="text-xs font-bold text-gray-200">{{ $moodVal }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- 💬 感想入力エリア -->
                <div class="space-y-2">
                    <label for="comment_input" class="block text-xs font-bold text-gray-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-comment-dots text-amber-500"></i>
                        感想・レビュー本文
                    </label>
                    <textarea id="comment_input" name="comment" rows="4" placeholder="この作品の見どころや感じたことを自由に書いてみよう..."
                        class="w-full bg-black/60 border border-gray-700/80 text-white text-xs rounded-xl p-4 focus:border-amber-500 focus:outline-none transition leading-relaxed shadow-inner"
                        required></textarea>
                </div>

                <!-- 🔘 フッターアクション（統一感のあるコンパクト右寄せボタン） -->
                <div class="pt-4 border-t border-gray-800/80 flex items-center justify-between">
                    <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('movies.show', $movie['id'] ?? $movie->id ?? 0) }}"
                        class="text-xs font-bold text-gray-400 hover:text-white transition flex items-center gap-1.5 px-3 py-2">
                        <i class="fa-solid fa-arrow-left"></i>
                        キャンセル
                    </a>

                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-black font-black text-xs rounded-xl transition-all shadow-lg active:scale-95 flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>レビューを投稿する</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>