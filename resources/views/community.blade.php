<x-app-layout :popularMovies="$popularMovies ?? []">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeFilter: 'all' }">

        <!-- 1. ページヘッダー（程よい余白でゆったり配置） -->
        <div class="bg-gray-900/90 rounded-2xl border border-gray-800 shadow-xl p-6 mb-8">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-500 text-2xl shadow-inner shrink-0">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-white flex items-center gap-2">
                        みんなの<span class="text-amber-500">感情タイムライン</span>
                    </h1>
                    <p class="text-xs text-gray-400 mt-1">映画を観たあとの「生の感情」が集まる場所。今の気分にぴったりの映画を見つけよう！</p>
                </div>
            </div>
        </div>

        <!-- 2. メインコンテンツ（左8：右4 の2カラム構成） -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- 👈 左カラム：投稿フォーム ＆ タイムライン (8/12) -->
            <div class="lg:col-span-8 space-y-8">

                <!-- ✍️ 投稿ボックス（余白を広げて見やすく調整） -->
                <div class="bg-gray-900/90 rounded-2xl border border-gray-800 p-6 shadow-xl space-y-6" x-data="{ 
                         searchQuery: '', 
                         searchResults: [], 
                         openDropdown: false, 
                         isLoading: false,
                         selectedMoods: [],
                         async searchMovies() {
                             if (!this.searchQuery || this.searchQuery.trim().length < 1) {
                                 this.searchResults = [];
                                 this.openDropdown = false;
                                 return;
                             }
                             this.isLoading = true;
                             try {
                                 const res = await fetch(`/api/movies/search?query=${encodeURIComponent(this.searchQuery.trim())}`);
                                 if (res.ok) {
                                     this.searchResults = await res.json();
                                     this.openDropdown = this.searchResults.length > 0;
                                 }
                             } catch (e) {
                                 console.error('検索エラー:', e);
                             } finally {
                                 this.isLoading = false;
                             }
                         },
                         selectMovie(title) {
                             this.searchQuery = title;
                             this.openDropdown = false;
                         },
                         toggleMood(mood) {
                             if (this.selectedMoods.includes(mood)) {
                                 this.selectedMoods = this.selectedMoods.filter(m => m !== mood);
                             } else {
                                 this.selectedMoods.push(mood);
                             }
                         }
                     }">

                    <h2
                        class="text-sm font-bold text-gray-300 flex items-center gap-2 uppercase tracking-wider pb-2 border-b border-gray-800">
                        <i class="fa-solid fa-pen-to-square text-amber-500"></i>
                        観た映画の「今の感情」を投稿する
                    </h2>

                    <form action="{{ route('community.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- 🎬 映画タイトル -->
                        <div class="relative w-full" @click.away="openDropdown = false">
                            <label for="movie_title_input" class="block text-xs font-bold text-gray-300 mb-2">
                                映画タイトル <span class="text-amber-500 text-[11px] font-normal">（入力すると候補が自動取得されます）</span>
                            </label>

                            <div class="relative w-full">
                                <input type="text" id="movie_title_input" name="movie_title" x-model="searchQuery"
                                    @input.debounce.300ms="searchMovies()" placeholder="例: アベンジャーズ、スパイダーマン..."
                                    class="w-full bg-black/60 border border-gray-700/80 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-500 focus:outline-none transition shadow-inner"
                                    required autocomplete="off">

                                <div x-show="isLoading" x-cloak class="absolute right-4 top-1/2 -translate-y-1/2">
                                    <i class="fa-solid fa-spinner animate-spin text-amber-500 text-sm"></i>
                                </div>
                            </div>

                            <!-- ドロップダウン候補 -->
                            <div x-show="openDropdown && searchResults.length > 0" x-cloak
                                class="absolute z-50 left-0 w-full mt-2 bg-gray-900 border border-gray-700 rounded-xl shadow-2xl max-h-60 overflow-y-auto custom-scrollbar">
                                <div class="p-2">
                                    <template x-for="movie in searchResults" :key="movie.id">
                                        <div @click="selectMovie(movie.title)"
                                            class="px-4 py-2.5 text-xs text-gray-200 hover:bg-amber-500/20 hover:text-amber-400 rounded-lg cursor-pointer flex items-center justify-between transition border-b border-gray-800/50 last:border-0">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-sm text-white" x-text="movie.title"></span>
                                                <span class="text-xs text-gray-400"
                                                    x-text="movie.release_date ? '(' + movie.release_date.substring(0, 4) + ')' : ''"></span>
                                            </div>
                                            <span class="text-xs text-amber-400 shrink-0 font-bold"
                                                x-text="'★ ' + (movie.vote_average ? Number(movie.vote_average).toFixed(1) : 'NEW')"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- 評価 ＆ 気分選択 -->
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-5">
                            <!-- 評価 -->
                            <div class="sm:col-span-5">
                                <label for="rating_select" class="block text-xs font-bold text-gray-300 mb-2">評価</label>
                                <select id="rating_select" name="rating"
                                    class="w-full bg-black/60 border border-gray-700 rounded-xl px-4 py-3 text-sm text-amber-400 font-bold focus:border-amber-500 focus:outline-none">
                                    <option value="5">★★★★★ (5.0)</option>
                                    <option value="4">★★★★☆ (4.0)</option>
                                    <option value="3" selected>★★★☆☆ (3.0)</option>
                                    <option value="2">★★☆☆☆ (2.0)</option>
                                    <option value="1">★☆☆☆☆ (1.0)</option>
                                </select>
                            </div>

                            <!-- 🎭 4つの気分ボタン -->
                            <div class="sm:col-span-7">
                                <span class="block text-xs font-bold text-gray-300 mb-2">今のあなたの「気分」は？（複数選択可）</span>

                                <template x-for="mood in selectedMoods" :key="mood">
                                    <input type="hidden" name="moods[]" :value="mood">
                                </template>

                                <div class="grid grid-cols-2 gap-2.5">
                                    @foreach([
                                            '号泣' => '😭',
                                            'スカッと' => '😆',
                                            'ハラハラ' => '😱',
                                            'キュン' => '💖'
                                        ] as $moodName => $emoji)
                                        <button type="button" @click="toggleMood('{{ $moodName }}')"
                                            :class="selectedMoods.includes('{{ $moodName }}') ? 'bg-amber-500/20 text-amber-400 border-amber-500 shadow-sm shadow-amber-500/30' : 'bg-black/40 text-gray-300 border-gray-700 hover:border-gray-500'"
                                            class="flex items-center justify-center gap-2 text-xs font-bold px-3 py-2.5 rounded-xl border transition-all select-none">
                                            <span>{{ $emoji }}</span>
                                            <span>#{{ $moodName }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- 💬 感想入力 -->
                        <div>
                            <label for="comment_textarea"
                                class="block text-xs font-bold text-gray-300 mb-2">感想・レビュー</label>
                            <textarea id="comment_textarea" name="comment" rows="3"
                                placeholder="この作品のどこが良かったか教えてください..."
                                class="w-full bg-black/60 border border-gray-700 rounded-xl p-4 text-sm text-white focus:border-amber-500 focus:outline-none leading-relaxed"
                                required></textarea>
                        </div>

                        <!-- 🔘 送信ボタン（ゆったり右寄せ） -->
                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-black font-black text-xs rounded-xl transition-all shadow-lg active:scale-95 flex items-center gap-2">
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>投稿する</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 🔍 感情フィルター（クリックでリアルタイムに絞り込み） -->
                <div class="space-y-2">
                    <span class="text-xs font-bold text-gray-400 px-1">絞り込み表示：</span>
                    <div class="flex flex-wrap gap-2.5 text-xs font-bold">
                        <button @click="activeFilter = 'all'"
                            :class="activeFilter === 'all' ? 'bg-amber-500 text-black shadow-md' : 'bg-gray-900 border border-gray-800 text-gray-300 hover:border-amber-500/50'"
                            class="px-4 py-2 rounded-full transition-all">
                            すべて
                        </button>
                        @foreach([
                                '号泣' => '😭',
                                'スカッと' => '😆',
                                'ハラハラ' => '😱',
                                'キュン' => '💖'
                            ] as $mName => $emoji)
                            <button @click="activeFilter = '{{ $mName }}'"
                                :class="activeFilter === '{{ $mName }}' ? 'bg-amber-500 text-black shadow-md' : 'bg-gray-900 border border-gray-800 text-gray-300 hover:border-amber-500/50'"
                                class="px-4 py-2 rounded-full transition-all flex items-center gap-1.5">
                                <span>{{ $emoji }}</span>
                                <span>#{{ $mName }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- 📱 タイムライン投稿一覧（フィルター連動） -->
                <div class="space-y-5">
                    @forelse($reviews ?? $posts ?? [] as $post)
                        @php
                            $moodVal = $post->mood ?? $post->moods ?? '';
                        @endphp
                        <div x-show="activeFilter === 'all' || '{{ $moodVal }}'.includes(activeFilter)" x-transition
                            class="bg-gray-900/90 rounded-2xl border border-gray-800 p-6 shadow-xl hover:border-gray-700 transition space-y-4">

                            <!-- ヘッダー（ユーザー名 ＆ 気分タグ） -->
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-500 to-amber-700 text-black font-black flex items-center justify-center text-xs shadow">
                                        {{ mb_substr($post->user->name ?? '名', 0, 1) }}
                                    </div>
                                    <div>
                                        <span
                                            class="font-bold text-xs text-white block">{{ $post->user->name ?? 'ゲストユーザー' }}</span>
                                        <span
                                            class="text-[10px] text-gray-400 block">{{ $post->created_at ? $post->created_at->diffForHumans() : 'たった今' }}</span>
                                    </div>
                                </div>

                                <!-- 気分タグ表示 -->
                                @if(!empty($moodVal))
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach(explode(',', $moodVal) as $m)
                                            <span
                                                class="bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
                                                #{{ trim($m) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- 映画タイトル ＆ 評価 -->
                            @if(!empty($post->movie_title))
                                <div
                                    class="bg-black/50 border border-gray-800/80 rounded-xl p-3 flex items-center justify-between">
                                    <span class="font-bold text-xs text-amber-400 flex items-center gap-2">
                                        <i class="fa-solid fa-film"></i>
                                        {{ $post->movie_title }}
                                    </span>
                                    <span class="text-amber-400 font-extrabold text-xs">★
                                        {{ number_format((float) ($post->rating ?? 5.0), 1) }}</span>
                                </div>
                            @endif

                            <!-- 感想文 -->
                            <p class="text-xs text-gray-200 leading-relaxed px-1">{{ $post->comment ?? $post->content }}</p>

                            <!-- ❤️ 共感 ＆ 💬 コメントボタン -->
                            <div
                                class="flex items-center justify-end gap-6 pt-3 border-t border-gray-800/60 text-xs text-gray-400">
                                <button class="hover:text-red-400 transition flex items-center gap-1.5 active:scale-95">
                                    <i class="fa-solid fa-heart text-red-500"></i>
                                    <span>共感した！</span>
                                    <span class="font-bold text-amber-400">{{ $post->likes_count ?? 0 }}</span>
                                </button>
                                <button class="hover:text-amber-400 transition flex items-center gap-1.5 active:scale-95">
                                    <i class="fa-solid fa-comment text-amber-500"></i>
                                    <span>コメント</span>
                                    <span class="font-bold text-amber-400">{{ $post->comments_count ?? 0 }}</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="bg-gray-900/60 rounded-2xl border border-gray-800/80 p-10 text-center space-y-3">
                            <i class="fa-solid fa-comments text-amber-500 text-3xl"></i>
                            <p class="text-xs text-gray-400">最初のレビューを投稿してみましょう！</p>
                        </div>
                    @endforelse
                </div>

            </div>

            <!-- 👉 右カラム：サイドバー (4/12) -->
            <div class="lg:col-span-4 space-y-6">

                <!-- 🔥 今みんなが感じている感情（クリックでフィルター連動） -->
                <div class="bg-gray-900/90 rounded-2xl border border-gray-800 p-5 shadow-xl space-y-4">
                    <h3 class="text-xs font-bold text-amber-400 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-fire text-amber-500"></i>
                        今みんなが感じている感情
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach([
                                '号泣' => '😭',
                                'スカッと' => '😆',
                                'ハラハラ' => '😱',
                                'キュン' => '💖'
                            ] as $mName => $emoji)
                            <button @click="activeFilter = '{{ $mName }}'"
                                class="bg-black hover:bg-gray-800 border border-gray-800 hover:border-amber-500/50 text-gray-300 text-xs font-bold px-3.5 py-2 rounded-full transition flex items-center gap-1.5">
                                <span>{{ $emoji }}</span>
                                <span>#{{ $mName }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- 💡 楽しみ方ガイド -->
                <div class="bg-gray-900/90 rounded-2xl border border-gray-800 p-5 shadow-xl space-y-3">
                    <h3 class="text-xs font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb text-amber-500"></i>
                        MovieMood コミュニティの楽しみ方
                    </h3>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        「キュンキュンしたい」「泣いてすっきりしたい」など、その時の気分に合った映画の感想を探せます。「共感した！」やコメントで感想を共有しましょう。
                    </p>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>