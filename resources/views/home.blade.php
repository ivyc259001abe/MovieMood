<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - ホーム</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black text-white min-h-screen flex flex-col items-center justify-center p-4">

    <!-- メインカード -->
    <div class="w-full max-w-md bg-gray-900 border-2 border-white rounded-3xl p-6 shadow-2xl text-center space-y-6">

        <!-- タイトル -->
        <h1 class="text-3xl font-bold text-amber-500 tracking-wider">MovieMood</h1>

        <!-- 歓迎メッセージ -->
        <p class="text-lg font-semibold text-amber-400">
            Welcome {{ session('user_name', 'ゲスト') }} さん！
        </p>

        <!-- ユーザーアイコン & ニックネーム -->
        <div
            class="flex items-center justify-center space-x-3 bg-gray-800/80 rounded-full py-2 px-4 border border-gray-700">
            @if(session('user_icon'))
                <img src="{{ session('user_icon') }}" class="w-10 h-10 rounded-full object-cover border border-amber-400">
            @else
                <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center text-xl">👤</div>
            @endif
            <span class="font-medium text-gray-200">{{ session('user_name', 'ゲスト') }}</span>
        </div>

        <!-- 検索バー -->
        <form action="/result" method="GET" class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">🔍</span>
            <input type="text" name="keyword" placeholder="キーワード検索"
                class="w-full py-2.5 pl-10 pr-4 bg-white text-gray-900 rounded-full focus:outline-none focus:ring-2 focus:ring-amber-400 font-medium placeholder-gray-400">
        </form>

        <!-- 気分選択エリア -->
        <div class="space-y-3">
            <p class="text-sm font-bold text-gray-300 flex items-center justify-center gap-1">
                <span>▼</span> 今のあなたの「気分」は？
            </p>

            <div class="grid grid-cols-2 gap-3">
                <a href="/result?mood=cry"
                    class="bg-white text-gray-900 font-bold py-2.5 px-4 rounded-full hover:bg-amber-400 transition text-sm">
                    😭 #号泣
                </a>
                <a href="/result?mood=action"
                    class="bg-white text-gray-900 font-bold py-2.5 px-4 rounded-full hover:bg-amber-400 transition text-sm">
                    😆 #スカッと
                </a>
                <a href="/result?mood=thrill"
                    class="bg-white text-gray-900 font-bold py-2.5 px-4 rounded-full hover:bg-amber-400 transition text-sm">
                    😱 #ハラハラ
                </a>
                <a href="/result?mood=love"
                    class="bg-white text-gray-900 font-bold py-2.5 px-4 rounded-full hover:bg-amber-400 transition text-sm">
                    ❤️ #キュン
                </a>
            </div>
        </div>

        <!-- 本日のピックアップ -->
        <div class="bg-pink-100 text-gray-800 p-3 rounded-2xl text-xs font-bold space-y-1">
            <p class="text-gray-500">本日のピックアップムード</p>
            <p class="text-amber-600 font-extrabold text-sm">「月曜から夜ふかし…」おすすめが出る</p>
        </div>

        <!-- 下部ボタン（マイページ & ログアウト） -->
        <div class="flex items-center justify-between pt-2">
            <a href="/profile"
                class="flex items-center gap-2 text-sm font-bold text-gray-300 hover:text-white transition">
                <span class="bg-gray-700 p-2 rounded-full">👤</span> マイページへ
            </a>
            <a href="/logout"
                class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2 px-5 rounded-full transition shadow-md">
                ログアウト
            </a>
        </div>

    </div>

</body>

</html>