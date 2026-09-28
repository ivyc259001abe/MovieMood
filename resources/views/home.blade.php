<x-app-layout :popularMovies="$popularMovies ?? []">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

            <!-- 左側 (7カラム) -->
            <div class="lg:col-span-7 flex flex-col gap-4">

                <!-- 🌟 WELCOME (おすすめ配色：ニックネームゴールド強調版) -->
                <div
                    class="bg-[#121927] border border-gray-800/80 rounded-2xl p-4 sm:p-5 shadow-xl flex items-center gap-4">
                    <!-- アバター表示部分 -->
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 rounded-full overflow-hidden bg-gray-900 border-2 border-amber-500 flex items-center justify-center shrink-0">
                        @if(Auth::check() && Auth::user()->avatar)
                            <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-user text-gray-400 text-lg"></i>
                        @endif
                    </div>
                    <!-- あいさつテキスト -->
                    <div>
                        <span class="text-xs text-gray-400 font-bold tracking-wide flex items-center gap-1">
                            Welcome!
                        </span>
                        <h1 class="text-base sm:text-xl font-black mt-0.5">
                            <span class="text-amber-400">{{ Str::limit(Auth::user()->name ?? 'ゲスト', 15, '') }}</span>
                            <span class="text-white"> さん、こんにちは！</span>
                        </h1>
                    </div>
                </div>

                <!-- 検索 -->
                <div class="bg-[#121927] border border-gray-800/80 rounded-2xl p-4 shadow-xl space-y-3">
                    <p class="text-xs font-bold text-amber-500 flex items-center gap-1.5">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>キーワード検索</span>
                    </p>

                    <!-- 🔍 キーワード検索フォーム -->
                    <form action="{{ route('movies.search') }}" method="GET" class="flex gap-2 w-full">
                        <div class="relative flex-grow">
                            <input type="text" name="query" value="{{ request('query') }}"
                                placeholder="キーワード検索（映画タイトル、キャストなど）" required
                                class="w-full bg-[#05080e] border border-gray-800 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition">
                        </div>
                        <button type="submit"
                            class="bg-amber-500 hover:bg-amber-400 text-black font-extrabold px-6 py-3 rounded-xl text-sm transition shrink-0 cursor-pointer">
                            検索
                        </button>
                    </form>
                </div>

                <!-- 気分タグ -->
                <div
                    class="bg-[#121927] border border-gray-800/80 rounded-2xl p-4 shadow-xl space-y-3 flex-grow flex flex-col justify-center">
                    <p class="text-xs font-bold text-amber-500 text-center">
                        ▼ 今のあなたの「気分」は？
                    </p>

                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('result', ['mood' => '号泣']) }}"
                            class="flex items-center justify-center gap-2 bg-[#0b0e15] hover:bg-gray-800 border border-gray-800 hover:border-amber-500/60 rounded-xl py-3 px-3 transition shadow group">
                            <span class="text-base">😭</span>
                            <span class="text-xs font-bold text-gray-200 group-hover:text-amber-400">#号泣</span>
                        </a>

                        <a href="{{ route('result', ['mood' => 'スカッと']) }}"
                            class="flex items-center justify-center gap-2 bg-[#0b0e15] hover:bg-gray-800 border border-gray-800 hover:border-amber-500/60 rounded-xl py-3 px-3 transition shadow group">
                            <span class="text-base">😆</span>
                            <span class="text-xs font-bold text-gray-200 group-hover:text-amber-400">#スカッと</span>
                        </a>

                        <a href="{{ route('result', ['mood' => 'ハラハラ']) }}"
                            class="flex items-center justify-center gap-2 bg-[#0b0e15] hover:bg-gray-800 border border-gray-800 hover:border-amber-500/60 rounded-xl py-3 px-3 transition shadow group">
                            <span class="text-base">😱</span>
                            <span class="text-xs font-bold text-gray-200 group-hover:text-amber-400">#ハラハラ</span>
                        </a>

                        <a href="{{ route('result', ['mood' => 'キュン']) }}"
                            class="flex items-center justify-center gap-2 bg-[#0b0e15] hover:bg-gray-800 border border-gray-800 hover:border-amber-500/60 rounded-xl py-3 px-3 transition shadow group">
                            <span class="text-base">💖</span>
                            <span class="text-xs font-bold text-gray-200 group-hover:text-amber-400">#キュン</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- 右側 (5カラム) -->
            <div
                class="lg:col-span-5 bg-[#121927] border border-gray-800/80 rounded-2xl p-5 shadow-xl flex flex-col items-center justify-between text-center space-y-4">

                <div class="space-y-1">
                    <span
                        class="text-[10px] text-amber-500 font-extrabold tracking-widest uppercase bg-amber-500/10 px-3 py-1 rounded-full border border-amber-500/20 inline-block">
                        🌙 TODAY'S PICKUP
                    </span>
                    <h2 class="text-xs sm:text-sm font-bold text-white pt-1">
                        本日のピックアップ<br>
                        <span class="text-xs text-amber-400 font-normal">「スカッとしたい？」</span>
                    </h2>
                </div>

                @if(!empty($popularMovies[0]))
                    <div class="w-full max-w-[140px] sm:max-w-[160px] mx-auto space-y-2">
                        <div
                            class="aspect-[2/3] rounded-xl overflow-hidden bg-black border border-gray-800 shadow-xl relative">
                            @if(!empty($popularMovies[0]['poster_path']))
                                <img src="https://image.tmdb.org/t/p/w500{{ $popularMovies[0]['poster_path'] }}"
                                    alt="{{ $popularMovies[0]['title'] ?? '' }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <h3 class="text-xs font-bold text-white truncate">{{ $popularMovies[0]['title'] ?? '' }}</h3>
                        <p class="text-[11px] text-amber-400 font-extrabold flex items-center justify-center gap-1">
                            <i class="fa-solid fa-star text-[10px]"></i>
                            <span>{{ number_format((float) ($popularMovies[0]['vote_average'] ?? 0), 1) }}</span>
                        </p>
                    </div>

                    <a href="{{ route('movies.show', $popularMovies[0]['id'] ?? 0) }}"
                        class="w-full py-3 bg-amber-500 hover:bg-amber-400 text-black font-black text-xs rounded-xl transition shadow flex items-center justify-center gap-1">
                        <span>詳細をチェックする</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                @endif

            </div>

        </div>

    </div>
</x-app-layout>