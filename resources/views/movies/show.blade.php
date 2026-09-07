<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie['title'] }} - MovieMood</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black text-white min-h-screen font-sans">

    <!-- ヘッダー（ロゴを金文字に統一） -->
    <header class="p-4 border-b border-gray-800 flex justify-between items-center">
        <a href="/" class="text-2xl font-extrabold text-amber-400 tracking-widest">MovieMood</a>
        <a href="javascript:history.back()" class="text-xs text-gray-400 hover:text-amber-400">← 戻る</a>
    </header>

    <!-- メインコンテンツ -->
    <main class="max-w-4xl mx-auto p-4 md:p-8">
        <div class="flex flex-col md:flex-row gap-6 items-start">
            <!-- ポスター -->
            @if(!empty($movie['poster_path']))
                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}"
                    class="w-full md:w-72 rounded-2xl shadow-2xl object-cover">
            @endif

            <!-- 映画情報 -->
            <div class="flex-1 space-y-4">
                <h1 class="text-2xl md:text-4xl font-bold text-amber-400">{{ $movie['title'] }}</h1>

                <div class="flex items-center gap-4 text-xs text-gray-400">
                    <span>公開日: {{ $movie['release_date'] ?? '不明' }}</span>
                    <span>評価: ★ {{ number_format($movie['vote_average'] ?? 0, 1) }}</span>
                </div>

                <div class="pt-2">
                    <h2 class="text-sm font-bold text-gray-200 mb-2">概要</h2>
                    <p class="text-xs md:text-sm text-gray-300 leading-relaxed">
                        {{ $movie['overview'] }}
                    </p>
                </div>
            </div>
        </div>
    </main>

</body>

</html>