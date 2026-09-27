<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - パスワード再設定</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black text-white min-h-screen flex flex-col justify-center items-center p-4 space-y-4">

    <!-- 🎬 ヘッダータイトル（MovieMoodロゴ：クリックでログイン画面/ホームへ遷移） -->
    <div class="text-center">
        <a href="{{ route('login') }}" class="inline-block group">
            <h1
                class="text-2xl sm:text-3xl font-extrabold text-amber-500 group-hover:text-amber-400 transition tracking-wide">
                MovieMood
            </h1>
        </a>
    </div>

    <!-- メインカード -->
    <div
        class="w-full max-w-xs sm:max-w-sm bg-[#121824] border border-gray-700/80 rounded-2xl p-6 shadow-2xl space-y-4">

        <h2 class="text-lg font-bold text-center text-amber-400">パスワード再設定</h2>
        <p class="text-xs text-gray-300 text-center leading-relaxed">
            登録メールアドレスと<br>新しいパスワードを入力してください。
        </p>

        @if ($errors->any())
            <div class="p-3 bg-red-900/50 border border-red-500 rounded-xl text-center text-xs text-red-200">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('password.reset.update') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">メールアドレス</label>
                <input type="email" name="email" required placeholder="メールアドレス"
                    class="w-full py-2.5 px-4 bg-gray-100 text-black rounded-full text-xs focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">新しいパスワード</label>
                <input type="password" name="password" required placeholder="新しいパスワード"
                    class="w-full py-2.5 px-4 bg-gray-100 text-black rounded-full text-xs focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-300 mb-1">新しいパスワード（確認）</label>
                <input type="password" name="password_confirmation" required placeholder="もう一度入力"
                    class="w-full py-2.5 px-4 bg-gray-100 text-black rounded-full text-xs focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <button type="submit"
                class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-black font-bold rounded-full transition text-xs mt-2 shadow">
                パスワードを変更する
            </button>
        </form>

        <div class="text-center pt-2 border-t border-gray-800">
            <a href="{{ route('login') }}" class="text-xs text-gray-400 hover:text-amber-400 transition underline">
                ログイン画面に戻る
            </a>
        </div>
    </div>

</body>

</html>