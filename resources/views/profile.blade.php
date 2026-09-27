<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - アカウント編集</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-scroll {
            display: flex;
            width: 200%;
            animation: scroll 35s linear infinite;
        }

        .animate-scroll:hover {
            animation-play-state: paused;
        }
    </style>
</head>

<body
    class="bg-black text-white min-h-screen w-screen flex flex-col justify-between items-center py-4 px-4 overflow-x-hidden overflow-y-auto">

    <!-- 【上部】ロゴ & アカウント編集カードエリア -->
    <div class="w-full max-w-xs sm:max-w-sm flex flex-col items-center justify-center flex-1 my-auto space-y-3">

        <!-- ヘッダータイトル -->
        <div class="text-center">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-amber-500 tracking-wide">MovieMood</h1>
            <p class="text-xs sm:text-sm font-medium text-gray-200 leading-snug pt-2">
                あなたの『今の気分』が、次に観る映画を決める。
            </p>
        </div>

        <!-- アカウント編集カード -->
        <div class="w-full bg-[#121824] border border-gray-700/80 rounded-2xl p-5 sm:p-6 shadow-2xl space-y-4">

            <h2 class="text-lg sm:text-xl font-bold text-center text-amber-500">アカウント編集</h2>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-3"
                autocomplete="off">
                @csrf
                @method('PUT')

                <!-- 1. アイコンプレビュー ＆ アップロード (一番上へ移動) -->
                <div class="flex flex-col items-center space-y-2 pb-1">
                    <div
                        class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-amber-500 overflow-hidden bg-gray-800 flex items-center justify-center shadow-md">
                        @if(auth()->check() && auth()->user()->icon_url)
                            <img id="iconPreview" src="{{ auth()->user()->icon_url }}" alt="現在のアイコン"
                                class="w-full h-full object-cover">
                        @else
                            <!-- 未設定時のデフォルト「人型」SVGアイコン -->
                            <svg id="defaultIcon" class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                            <img id="iconPreview" class="w-full h-full object-cover hidden" alt="プレビュー">
                        @endif
                    </div>
                    <label class="cursor-pointer text-xs font-semibold text-amber-500 hover:text-amber-400 underline">
                        画像を選択・変更
                        <input type="file" name="icon" accept="image/*" class="hidden" onchange="previewImage(event)">
                    </label>
                </div>

                <!-- 2. ニックネーム -->
                <div class="space-y-1">
                    <label class="block text-xs font-semibold text-gray-300 pl-1">ニックネーム</label>
                    <input type="text" name="nickname" value="{{ old('nickname', auth()->user()->nickname ?? '') }}"
                        placeholder="例: たろう" required
                        class="w-full py-2 px-4 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs sm:text-sm placeholder-gray-400">
                </div>

                <!-- 3. メールアドレス (自動入力されないよう初期値空・補足対応) -->
                <div class="space-y-1">
                    <label class="block text-xs font-semibold text-gray-300 pl-1">メールアドレス</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="example@mail.com" required
                        autocomplete="off"
                        class="w-full py-2 px-4 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs sm:text-sm placeholder-gray-400">
                </div>

                <!-- 4. 新しいパスワード (変更する場合のみ) -->
                <div class="space-y-1">
                    <label class="block text-xs font-semibold text-gray-300 pl-1">新しいパスワード <span
                            class="text-[10px] text-gray-400 font-normal">(変更する場合のみ)</span></label>
                    <input type="password" name="password" placeholder="8文字以上" autocomplete="new-password"
                        class="w-full py-2 px-4 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs sm:text-sm placeholder-gray-400">
                </div>

                <!-- 5. パスワード（確認） -->
                <div class="space-y-1">
                    <input type="password" name="password_confirmation" placeholder="パスワード(確認用)"
                        autocomplete="new-password"
                        class="w-full py-2 px-4 bg-gray-100 text-black rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs sm:text-sm placeholder-gray-400">
                </div>

                <!-- エラーメッセージ表示エリア -->
                @if ($errors->any())
                    <div class="p-2 bg-red-900/50 border border-red-500 rounded-xl text-center space-y-1">
                        @foreach ($errors->all() as $error)
                            <p class="text-xs text-red-200 font-bold">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- 変更保存ボタン -->
                <button type="submit"
                    class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-black font-bold rounded-full transition duration-200 shadow-md text-sm mt-2">
                    変更を保存する
                </button>
            </form>

            <!-- 下部アクション (キャンセル & ログアウト) -->
            <div class="flex items-center justify-between pt-2 px-1">
                <a href="javascript:history.back()"
                    class="text-xs font-semibold text-gray-400 hover:text-white underline transition-colors">
                    キャンセル(戻る)
                </a>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="py-1 px-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full text-xs transition-colors shadow">
                        ログアウト
                    </button>
                </form>
            </div>

        </div>

    </div>

    <!-- 【下部】POPULAR MOVIES カルーセル (API連携) -->
    <div class="w-full mt-4 space-y-1.5 flex-shrink-0 pb-2">
        <p class="text-center text-xs font-bold text-gray-400 tracking-wider">
            POPULAR MOVIES (タップで詳細へ)
        </p>

        <div class="overflow-hidden w-full">
            <div class="animate-scroll flex gap-3 px-2">
                @php $movies = $popularMovies ?? []; @endphp

                <!-- 1周目 -->
                @foreach($movies as $movie)
                    @if(!empty($movie['poster_path']))
                        <a href="{{ route('movies.show', $movie['id']) }}"
                            class="flex-shrink-0 transition-transform duration-200 hover:scale-105">
                            <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}"
                                title="{{ $movie['title'] }}" loading="lazy"
                                class="w-20 h-30 sm:w-24 sm:h-36 object-cover rounded-xl shadow-lg border border-gray-800">
                        </a>
                    @endif
                @endforeach

                <!-- 2周目 -->
                @foreach($movies as $movie)
                    @if(!empty($movie['poster_path']))
                        <a href="{{ route('movies.show', $movie['id']) }}"
                            class="flex-shrink-0 transition-transform duration-200 hover:scale-105">
                            <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}"
                                title="{{ $movie['title'] }}" loading="lazy"
                                class="w-20 h-30 sm:w-24 sm:h-36 object-cover rounded-xl shadow-lg border border-gray-800">
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- JavaScript: 画像アップロード時の即時プレビュー切替 -->
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const output = document.getElementById('iconPreview');
                    const defaultIcon = document.getElementById('defaultIcon');

                    output.src = e.target.result;
                    output.classList.remove('hidden');

                    if (defaultIcon) {
                        defaultIcon.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    </script>

</body>

</html>