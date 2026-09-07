<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - 映画一覧</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black text-white min-h-screen flex flex-col items-center py-6 px-4">

    <!-- ヘッダーエリア（ロゴ & ユーザー表示） -->
    <header class="w-full max-w-5xl flex items-center justify-between mb-8 pb-4 border-b border-gray-800">
        <!-- ロゴ（トップページへ戻る） -->
        <a href="/" class="text-2xl sm:text-3xl font-extrabold text-amber-500 tracking-wide hover:text-amber-400 transition-colors">
            MovieMood
        </a>

        <!-- ユーザー情報表示 -->
        <div class="flex items-center gap-3 bg-[#121824] px-4 py-2 rounded-full border border-gray-700/60 shadow-md">
            @if(session('user_icon'))
                <img src="{{ session('user_icon') }}" alt="User Icon" class="w-8 h-8 rounded-full object-cover border border-amber-500">
            @else
                <div class="w-8 h-8 rounded-full bg-purple-600/50 flex items-center justify-center text-xs text-white">
                    👤
                </div>
            @endif

            <span class="font-bold text-xs sm:text-sm text-gray-200">
                {{ session('user_name', 'ゲスト') }} さん
            </span>

            <a href="/" class="ml-2 text-xs text-amber-400 hover:underline">トップへ</a>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main class="w-full max-w-5xl flex-1">
        
        <!-- セクションタイトル -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-amber-500 border-l-4 border-amber-500 pl-3">
                検索・気別映画一覧
            </h1>
            <a href="/" class="text-xs text-gray-400 hover:text-white transition-colors">← トップへ戻る</a>
        </div>

        <!-- 映画グリッド一覧 -->
        @if(count($movies) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
                @foreach($movies as $movie)
                    <div class="bg-[#121824] border border-gray-800 rounded-xl overflow-hidden shadow-lg transition-transform duration-200 hover:-translate-y-1 hover:border-amber-500/50 flex flex-col justify-between">
                        
                        <div>
                            <!-- ポスター画像 -->
                            <a href="{{ route('movies.show', $movie['id']) }}" class="block overflow-hidden bg-gray-900">
                                @if(!empty($movie['poster_path']))
                                    <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}" 
                                         alt="{{ $movie['title'] }}" 
                                         class="w-full h-56 sm:h-64 object-cover hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-56 sm:h-64 bg-gray-800 flex items-center justify-center text-gray-500 text-xs">
                                        No Image
                                    </div>
                                @endif
                            </a>

                            <!-- 映画情報 -->
                            <div class="p-3">
                                <h2 class="text-xs sm:text-sm font-bold text-white line-clamp-2 leading-tight mb-2">
                                    <a href="{{ route('movies.show', $movie['id']) }}" class="hover:text-amber-400 transition-colors">
                                        {{ $movie['title'] }}
                                    </a>
                                </h2>
                            </div>
                        </div>

                        <!-- メタ情報 (公開日・評価) -->
                        <div class="p-3 pt-0 text-[11px] text-gray-400 flex items-center justify-between">
                            <span>{{ !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) . '年' : '未定' }}</span>
                            <span class="text-amber-400 font-semibold flex items-center gap-1">
                                ★ {{ number_format($movie['vote_average'] ?? 0, 1) }}
                            </span>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <!-- 検索結果が空の場合 -->
            <div class="text-center py-20 bg-[#121824] rounded-2xl border border-gray-800">
                <p class="text-gray-400 text-sm">該当する映画が見つかりませんでした。</p>
                <a href="/" class="inline-block mt-4 px-6 py-2 bg-amber-500 text-black text-xs font-bold rounded-full hover:bg-amber-600 transition-colors">
                    トップに戻って検索し直す
                </a>
            </div>
        @endif

    </main>

    <!-- フッター -->
    <footer class="w-full max-w-5xl text-center text-xs text-gray-600 mt-12 pt-6 border-t border-gray-900">
        &copy; MovieMood All Rights Reserved.
    </footer>

</body>

</html>