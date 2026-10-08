<!DOCTYPE html>
<html lang="ja" class="dark">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'MovieMood' }}</title>


    <!-- =========================================================
         Tailwind CSS 警告抑制スクリプト
    ========================================================== -->

    <script>

        const originalWarn = console.warn;

        console.warn = (...args) => {

            if (
                args[0] &&
                typeof args[0] === 'string' &&
                args[0].includes('cdn.tailwindcss.com')
            ) {
                return;
            }

            originalWarn(...args);

        };

    </script>


    <!-- Tailwind CSS -->

    <script src="https://cdn.tailwindcss.com"></script>


    <!-- FontAwesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <!-- Alpine.js -->

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js">
    </script>


    <style>
        /* =========================================================
           Alpine
        ========================================================== */

        [x-cloak] {
            display: none !important;
        }


        /* =========================================================
           POPULAR MOVIES 横スクロール
        ========================================================== */

        @keyframes loop-scroll {

            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }

        }


        .animate-loop-scroll {

            display: flex;

            width: max-content;

            animation:
                loop-scroll 120s linear infinite;

        }


        .animate-loop-scroll:hover {

            animation-play-state: paused;

        }


        /* =========================================================
           スクロールバー
        ========================================================== */

        .custom-scrollbar::-webkit-scrollbar {

            width: 6px;

        }


        .custom-scrollbar::-webkit-scrollbar-track {

            background: #0b0e14;

            border-radius: 8px;

        }


        .custom-scrollbar::-webkit-scrollbar-thumb {

            background: #2d3748;

            border-radius: 8px;

        }


        .custom-scrollbar::-webkit-scrollbar-thumb:hover {

            background: #f59e0b;

        }


        /* =========================================================
           共通フッター
        ========================================================== */

        .movie-footer {

            width: 100%;

            max-width: 100%;

            overflow: hidden;

            background: #000000;

            flex-shrink: 0;

        }


        /* =========================================================
           POPULAR MOVIES 表示部分
        ========================================================== */

        .popular-movies-area {

            width: 100%;

            overflow: hidden;

        }


        .popular-movies-track-wrapper {

            width: 100%;

            overflow: hidden;

            position: relative;

        }


        /* =========================================================
           PC
        ========================================================== */

        @media (min-width: 1024px) {

            .popular-movies-area {

                padding-top: 2px;

                padding-bottom: 4px;

            }

        }


        /* =========================================================
           スマートフォン
        ========================================================== */

        @media (max-width: 1023px) {

            .popular-movies-area {

                padding-top: 2px;

                padding-bottom: 4px;

            }

        }
    </style>

</head>


<body
    class="bg-black text-white min-h-screen flex flex-col font-sans antialiased w-full selection:bg-amber-500 selection:text-black overflow-x-hidden relative">


    <!-- =========================================================
         ヘッダーナビゲーション
    ========================================================== -->

    <header x-data="{ mobileMenuOpen: false }"
        class="bg-[#0b0e14] border-b border-gray-800/80 sticky top-0 z-50 w-full py-2.5">


        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between w-full">


            <!-- =================================================
                 左：MovieMoodロゴ
            ================================================== -->

            <div class="flex flex-col items-start justify-center min-w-0">


                <a href="{{ \Illuminate\Support\Facades\Route::has('home') ? route('home') : url('/') }}"
                    title="HOME画面へ戻る"
                    class="group text-lg sm:text-2xl font-extrabold text-amber-500 hover:text-amber-400 transition-all duration-200 tracking-wide shrink-0 no-underline inline-flex items-center gap-1.5">

                    <span class="group-hover:scale-105 transition-transform duration-200">

                        MovieMood

                    </span>

                </a>


                <!-- キャッチコピー -->

                <p
                    class="hidden md:block text-[10px] sm:text-xs text-gray-300 font-bold m-0 mt-0.5 pl-0.5 pointer-events-none">

                    〜 観る前の「気分」も、観た後の「感想」も、映画と一緒に。 〜

                </p>

            </div>



            <!-- =================================================
                 PC表示
            ================================================== -->

            <div class="hidden md:flex items-center gap-4 text-xs font-bold">


                @auth


                    <!-- =================================================
                             通知
                        ================================================== -->

                    <div x-data="{ open: false, unreadCount: {{ Auth::user()->unreadNotifications->count() }} }"
                        class="relative">


                        <button @click="
                                    open = !open;

                                    if (open && unreadCount > 0) {

                                        fetch(
                                            '{{ \Illuminate\Support\Facades\Route::has('notifications.readAll') ? route('notifications.readAll') : '#' }}',
                                            {
                                                method: 'POST',

                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Content-Type': 'application/json'
                                                }

                                            }
                                        )
                                        .then(() => unreadCount = 0);

                                    }
                                "
                            class="relative text-gray-300 hover:text-amber-400 p-2 focus:outline-none transition cursor-pointer flex items-center justify-center rounded-full hover:bg-gray-800/60"
                            title="お知らせ">

                            <i class="fa-solid fa-bell text-base text-amber-500"></i>


                            <span x-show="unreadCount > 0" x-cloak
                                class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-[#0b0e14]">
                            </span>

                        </button>



                        <!-- 通知ドロップダウン -->

                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute right-0 mt-2 w-80 sm:w-96 bg-[#121824] border border-gray-800 rounded-2xl shadow-2xl overflow-hidden py-1 z-50 text-left">


                            <!-- 通知ヘッダー -->

                            <div
                                class="px-4 py-2.5 bg-[#1a2332] border-b border-gray-800 flex justify-between items-center">

                                <span class="font-bold text-gray-200 text-xs flex items-center gap-1.5">

                                    <i class="fa-solid fa-bell text-amber-500"></i>

                                    お知らせ

                                </span>


                                <span
                                    class="text-[10px] bg-amber-500/20 text-amber-400 border border-amber-500/30 px-2 py-0.5 rounded-full font-bold"
                                    x-text="unreadCount + '件の未読'">
                                </span>

                            </div>



                            <!-- 通知一覧 -->

                            <div
                                class="max-h-80 overflow-y-auto divide-y divide-gray-800/60 p-1.5 space-y-1 custom-scrollbar">


                                @forelse(Auth::user()->notifications->take(10) as $notification)


                                                    @php

                                                        $data = $notification->data ?? [];

                                                        $userName =
                                                            $data['user_name']
                                                            ?? $data['user_nickname']
                                                            ?? $data['sender_name']
                                                            ?? $data['sender_nickname']
                                                            ?? ($data['user']['name'] ?? null)
                                                            ?? ($data['user']['nickname'] ?? null)
                                                            ?? null;

                                                        $displayUserName =
                                                            !empty($userName)
                                                            && $userName !== 'ユーザー'
                                                            && $userName !== '匿名ユーザー'
                                                            ? $userName
                                                            : '他の映画ファン';

                                                        $movieTitle =
                                                            $data['movie_title']
                                                            ?? $data['title']
                                                            ?? $data['movie_name']
                                                            ?? null;

                                                        $movieId =
                                                            $data['movie_id']
                                                            ?? $data['tmdb_id']
                                                            ?? null;

                                                        $reviewId =
                                                            $data['review_id']
                                                            ?? null;

                                                        $notificationType =
                                                            strtolower(
                                                                $data['type']
                                                                ?? $notification->type
                                                                ?? ''
                                                            );

                                                        $rawMsg =
                                                            $data['message']
                                                            ?? '';

                                                        $isComment =
                                                            str_contains(
                                                                $notificationType,
                                                                'comment'
                                                            )
                                                            ||
                                                            str_contains(
                                                                $rawMsg,
                                                                'コメント'
                                                            );

                                                        $actionText =
                                                            $isComment
                                                            ? 'にコメントしました。'
                                                            : 'に「いいね！」しました。';

                                                        $targetUrl = '#';


                                                        if (
                                                            !empty($data['url'])
                                                            && $data['url'] !== '#'
                                                        ) {

                                                            $targetUrl = $data['url'];

                                                        } elseif ($movieId) {

                                                            if (
                                                                \Illuminate\Support\Facades\Route::has('movies.show')
                                                            ) {

                                                                $targetUrl =
                                                                    route(
                                                                        'movies.show',
                                                                        $movieId
                                                                    );

                                                            } else {

                                                                $targetUrl =
                                                                    url(
                                                                        '/movies/' . $movieId
                                                                    );

                                                            }


                                                            if ($reviewId) {

                                                                $targetUrl .=
                                                                    '#review-' . $reviewId;

                                                            }

                                                        }


                                                        $isUnread =
                                                            is_null(
                                                                $notification->read_at
                                                            );

                                                    @endphp



                                                    <!-- 通知1件 -->

                                                    <a href="{{ $targetUrl }}" class="group block relative rounded-xl transition duration-150 overflow-hidden border border-transparent
                                                                {{
                                    $isUnread
                                    ? 'bg-[#1a2332]/90'
                                    : 'bg-transparent hover:bg-gray-800/60'
                                                                }}
                                                                p-3 no-underline">


                                                        <div class="flex items-start gap-3">


                                                            <!-- アイコン -->

                                                            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 mt-0.5 border
                                                                        {{
                                    $isComment
                                    ? 'bg-amber-500/20 border-amber-500/50 text-amber-400'
                                    : 'bg-red-500/20 border-red-500/50 text-red-500'
                                                                        }}">

                                                                @if($isComment)

                                                                    <i class="fa-solid fa-comment text-amber-400 text-xs">
                                                                    </i>

                                                                @else

                                                                    <i class="fa-solid fa-heart text-red-500 text-xs">
                                                                    </i>

                                                                @endif

                                                            </div>


                                                            <!-- 本文 -->

                                                            <div class="flex-1 min-w-0 text-xs text-gray-300 leading-relaxed space-y-1">


                                                                <div>

                                                                    <span class="font-bold text-white">

                                                                        {{ $displayUserName }}

                                                                    </span>

                                                                    さんが

                                                                    @if(
                                                                            !empty($movieTitle)
                                                                            && $movieTitle !== '映画'
                                                                        )

                                                                        <span class="font-bold text-amber-400 mx-0.5">

                                                                            『{{ $movieTitle }}』

                                                                        </span>

                                                                    @endif

                                                                    {{ $actionText }}

                                                                </div>


                                                                <div class="flex items-center justify-between text-[10px] text-gray-500 pt-1">

                                                                    <span>

                                                                        {{ $notification->created_at->diffForHumans() }}

                                                                    </span>


                                                                    @if($isUnread)

                                                                        <span
                                                                            class="w-2 h-2 rounded-full bg-amber-500 inline-block shadow-[0_0_6px_rgba(245,158,11,0.8)]">
                                                                        </span>

                                                                    @endif

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </a>


                                @empty

                                    <p class="text-center text-xs text-gray-500 py-6 m-0">

                                        新着のお知らせはありません

                                    </p>

                                @endforelse

                            </div>

                        </div>

                    </div>



                    <!-- =================================================
                             マイページ
                        ================================================== -->

                    @if(\Illuminate\Support\Facades\Route::has('mypage'))

                        <a href="{{ route('mypage') }}"
                            class="inline-flex items-center gap-2 bg-[#1a2332] border border-amber-500/40 hover:border-amber-400 px-3 py-1 rounded-full transition group shrink-0 no-underline shadow-md">


                            <div
                                class="w-7 h-7 rounded-full overflow-hidden bg-gray-800 flex items-center justify-center shrink-0 border-2 border-amber-500 shadow-sm">


                                @php

                                    $avatar =
                                        Auth::user()->avatar
                                        ?? Auth::user()->icon
                                        ?? Auth::user()->icon_path
                                        ?? null;

                                @endphp


                                @if($avatar)

                                    <img src="{{ Str::startsWith($avatar, 'http') ? $avatar : asset($avatar) }}"
                                        alt="{{ Auth::user()->nickname ?? Auth::user()->name }}" class="w-full h-full object-cover">

                                @else

                                            <span class="text-amber-400 font-bold text-xs flex items-center justify-center w-full h-full">

                                                {{
                                    mb_substr(
                                        Auth::user()->nickname
                                        ?? Auth::user()->name
                                        ?? 'U',
                                        0,
                                        1
                                    )
                                                            }}

                                            </span>

                                @endif

                            </div>


                            <span
                                class="text-xs font-bold text-gray-200 group-hover:text-amber-400 max-w-[120px] truncate leading-none">

                                {{ Auth::user()->nickname ?? Auth::user()->name }}

                            </span>

                        </a>

                    @endif



                    <!-- =================================================
                             コミュニティ
                        ================================================== -->

                    @if(\Illuminate\Support\Facades\Route::has('community.index'))

                        <a href="{{ route('community.index') }}"
                            class="text-amber-500 hover:text-amber-400 transition flex items-center gap-1.5 px-2 py-1 shrink-0 no-underline">

                            <i class="fa-solid fa-users text-amber-500 text-xs"></i>

                            <span>
                                コミュニティ
                            </span>

                        </a>

                    @endif



                    <!-- =================================================
                             ログアウト
                        ================================================== -->

                    @if(\Illuminate\Support\Facades\Route::has('logout'))

                        <form method="POST" action="{{ route('logout') }}" class="inline shrink-0 m-0">

                            @csrf

                            <button type="submit"
                                class="text-red-500 hover:text-red-400 transition flex items-center gap-1.5 px-2 py-1 font-bold cursor-pointer bg-transparent border-0">

                                <i class="fa-solid fa-right-from-bracket text-red-500 text-xs">
                                </i>

                                <span>
                                    ログアウト
                                </span>

                            </button>

                        </form>

                    @endif


                @else


                    <!-- 未ログイン -->

                    @if(\Illuminate\Support\Facades\Route::has('login'))

                        <a href="{{ route('login') }}"
                            class="text-gray-300 hover:text-amber-400 transition no-underline px-2 py-1">

                            ログイン

                        </a>

                    @endif


                    @if(\Illuminate\Support\Facades\Route::has('register'))

                        <a href="{{ route('register') }}"
                            class="bg-amber-500 hover:bg-amber-400 text-gray-950 font-bold px-3 py-1.5 rounded-full transition no-underline">

                            新規登録

                        </a>

                    @endif

                @endauth

            </div>



            <!-- =================================================
                 スマホ表示
            ================================================== -->

            <div class="flex items-center gap-2 md:hidden">


                @auth


                    <!-- スマホ通知 -->

                    <div x-data="{ open: false, unreadCount: {{ Auth::user()->unreadNotifications->count() }} }"
                        class="relative">


                        <button @click="
                                    open = !open;

                                    if (open && unreadCount > 0) {

                                        fetch(
                                            '{{ \Illuminate\Support\Facades\Route::has('notifications.readAll') ? route('notifications.readAll') : '#' }}',
                                            {
                                                method: 'POST',

                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Content-Type': 'application/json'
                                                }
                                            }
                                        )
                                        .then(() => unreadCount = 0);

                                    }
                                " class="relative text-gray-300 hover:text-amber-400 p-2 focus:outline-none">


                            <i class="fa-solid fa-bell text-lg text-amber-500">
                            </i>


                            <span x-show="unreadCount > 0" x-cloak
                                class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full">
                            </span>

                        </button>



                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute right-0 mt-2 w-72 bg-[#121824] border border-gray-800 rounded-xl shadow-2xl p-2 z-50">


                            <div
                                class="px-2 py-1 text-xs font-bold text-amber-400 border-b border-gray-800 mb-1 flex justify-between items-center">

                                <span>
                                    お知らせ
                                </span>

                                <span class="text-[10px] text-gray-400" x-text="unreadCount + '件の未読'">
                                </span>

                            </div>


                            <div class="max-h-60 overflow-y-auto space-y-1">


                                @forelse(Auth::user()->notifications->take(5) as $notification)


                                                    @php

                                                        $data =
                                                            $notification->data
                                                            ?? [];

                                                        $userName =
                                                            $data['user_name']
                                                            ?? $data['user_nickname']
                                                            ?? $data['sender_name']
                                                            ?? $data['sender_nickname']
                                                            ?? null;

                                                        $displayUserName =
                                                            !empty($userName)
                                                            && $userName !== 'ユーザー'
                                                            && $userName !== '匿名ユーザー'
                                                            ? $userName
                                                            : '他の映画ファン';

                                                        $movieTitle =
                                                            $data['movie_title']
                                                            ?? $data['title']
                                                            ?? $data['movie_name']
                                                            ?? null;

                                                        $notificationType =
                                                            strtolower(
                                                                $data['type']
                                                                ?? $notification->type
                                                                ?? ''
                                                            );

                                                        $rawMsg =
                                                            $data['message']
                                                            ?? '';

                                                        $isComment =
                                                            str_contains(
                                                                $notificationType,
                                                                'comment'
                                                            )
                                                            ||
                                                            str_contains(
                                                                $rawMsg,
                                                                'コメント'
                                                            );

                                                    @endphp


                                                    <div class="text-[11px] text-gray-300 p-2 rounded bg-[#1a2332]/50">

                                                        <div class="flex items-start gap-2">


                                                            <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 border
                                                                        {{
                                    $isComment
                                    ? 'bg-amber-500/20 border-amber-500/50'
                                    : 'bg-red-500/20 border-red-500/50'
                                                                        }}">

                                                                @if($isComment)

                                                                    <i class="fa-solid fa-comment text-amber-400 text-[10px]">
                                                                    </i>

                                                                @else

                                                                    <i class="fa-solid fa-heart text-red-500 text-[10px]">
                                                                    </i>

                                                                @endif

                                                            </div>


                                                            <div class="leading-relaxed">

                                                                <span class="font-bold text-white">

                                                                    {{ $displayUserName }}

                                                                </span>

                                                                さんが

                                                                @if(
                                                                        !empty($movieTitle)
                                                                        && $movieTitle !== '映画'
                                                                    )

                                                                    <span class="font-bold text-amber-400">

                                                                        『{{ $movieTitle }}』

                                                                    </span>

                                                                @endif


                                                                @if($isComment)

                                                                    にコメントしました。

                                                                @else

                                                                    に「いいね！」しました。

                                                                @endif

                                                            </div>

                                                        </div>

                                                    </div>


                                @empty

                                    <div class="text-[11px] text-gray-500 p-2 text-center">

                                        お知らせはありません

                                    </div>

                                @endforelse

                            </div>

                        </div>

                    </div>

                @endauth



                <!-- 三本線 -->

                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" aria-label="メニュー"
                    class="p-2 rounded-lg text-amber-500 hover:text-amber-400 hover:bg-gray-800/60 focus:outline-none transition">

                    <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark text-xl' : 'fa-bars text-xl'">
                    </i>

                </button>

            </div>

        </div>



        <!-- =================================================
             スマホ用展開メニュー
        ================================================== -->

        <div x-show="mobileMenuOpen" x-cloak @click.away="mobileMenuOpen = false"
            class="md:hidden bg-[#0e131f] border-t border-gray-800/80 px-4 pt-3 pb-4 space-y-3 mt-2">


            @auth


                <!-- ユーザー情報 -->

                <div class="flex items-center gap-3 pb-3 border-b border-gray-800">


                    <div
                        class="w-8 h-8 rounded-full bg-gray-900 border border-amber-400 overflow-hidden shrink-0 flex items-center justify-center">

                        @if(Auth::user()->avatar)

                            <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                                class="w-full h-full object-cover">

                        @else

                            <i class="fa-solid fa-user text-gray-400 text-xs">
                            </i>

                        @endif

                    </div>


                    <div class="min-w-0 flex-1">

                        <div class="font-bold text-xs text-amber-400 truncate">

                            {{ Auth::user()->nickname ?? Auth::user()->name ?? 'ユーザー' }}

                        </div>

                    </div>

                </div>



                <!-- メニュー -->

                <div class="space-y-1">


                    @if(\Illuminate\Support\Facades\Route::has('mypage'))

                        <a href="{{ route('mypage') }}"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-gray-200 hover:bg-gray-800 hover:text-amber-400 transition no-underline">

                            <i class="fa-solid fa-user text-amber-500 w-4 text-center">
                            </i>

                            <span>
                                マイページ
                            </span>

                        </a>

                    @endif


                    @if(\Illuminate\Support\Facades\Route::has('community.index'))

                        <a href="{{ route('community.index') }}"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-gray-200 hover:bg-gray-800 hover:text-amber-400 transition no-underline">

                            <i class="fa-solid fa-users text-amber-500 w-4 text-center">
                            </i>

                            <span>
                                コミュニティ
                            </span>

                        </a>

                    @endif

                </div>



                <!-- ログアウト -->

                @if(\Illuminate\Support\Facades\Route::has('logout'))

                    <div class="pt-2 border-t border-gray-800">

                        <form method="POST" action="{{ route('logout') }}" class="m-0">

                            @csrf

                            <button type="submit"
                                class="w-full text-left px-3 py-2 rounded-lg text-xs font-bold text-red-500 hover:bg-red-500/10 transition flex items-center gap-2.5 bg-transparent border-0 cursor-pointer">

                                <i class="fa-solid fa-right-from-bracket w-4 text-center">
                                </i>

                                <span>
                                    ログアウト
                                </span>

                            </button>

                        </form>

                    </div>

                @endif


            @else


                <!-- 未ログイン -->

                <div class="space-y-2 pt-1">


                    @if(\Illuminate\Support\Facades\Route::has('login'))

                        <a href="{{ route('login') }}"
                            class="block w-full text-center py-2 text-xs font-bold text-gray-300 hover:text-white bg-gray-800 rounded-lg no-underline">

                            ログイン

                        </a>

                    @endif


                    @if(\Illuminate\Support\Facades\Route::has('register'))

                        <a href="{{ route('register') }}"
                            class="block w-full text-center py-2 text-xs font-bold text-gray-950 bg-amber-500 hover:bg-amber-400 rounded-lg no-underline">

                            新規登録

                        </a>

                    @endif

                </div>

            @endauth

        </div>

    </header>



    <!-- =========================================================
         メインコンテンツ

         ★ ここが今回の重要変更箇所

         flex-grow を外しています。

         これにより、HOMEの内容が必要以上に
         画面の高さを占有しません。
    ========================================================== -->

    <main class="w-full max-w-full overflow-x-hidden">

        {{ $slot }}

    </main>



    <!-- =========================================================
         POPULAR MOVIES 用データ
    ========================================================== -->

    @php

        if (empty($popularMovies)) {

            try {

                $apiKey =
                    config(
                        'services.tmdb.api_key',
                        env('TMDB_API_KEY')
                    );


                if ($apiKey) {

                    $response =
                        \Illuminate\Support\Facades\Http::get(
                            "https://api.themoviedb.org/3/movie/popular",
                            [
                                'api_key' => $apiKey,
                                'language' => 'ja-JP',
                                'page' => 1,
                            ]
                        );


                    $popularMovies =
                        $response->successful()
                        ? ($response->json()['results'] ?? [])
                        : [];

                }

            } catch (\Exception $e) {

                $popularMovies = [];

            }

        }


        $loopMovies =
            array_merge(
                $popularMovies ?? [],
                $popularMovies ?? []
            );

    @endphp



    <!-- =========================================================
         共通フッター

         ★ mt-6 を削除
         ★ POPULAR MOVIESをメイン直下に配置
    ========================================================== -->

    <footer class="movie-footer bg-black border-t border-gray-900 text-gray-400 text-xs">


        @if(!empty($popularMovies))


            <!-- =================================================
                     POPULAR MOVIES
                ================================================== -->

            <div class="popular-movies-area border-b border-gray-900 bg-black">


                <!-- タイトル -->

                <div class="w-full text-center mb-1.5 px-4">

                    <span class="text-[10px] font-extrabold text-gray-400 tracking-wider uppercase">

                        POPULAR MOVIES

                        <span class="text-gray-500 font-normal">

                            （タップで詳細へ）

                        </span>

                    </span>

                </div>



                <!-- =================================================
                         ポスター横スクロール
                    ================================================== -->

                <div class="popular-movies-track-wrapper">


                    <div class="animate-loop-scroll gap-2 will-change-transform">


                        @foreach($loopMovies as $movie)


                                    @php

                                        $movieShowUrl =
                                            \Illuminate\Support\Facades\Route::has(
                                                'movies.show'
                                            )
                                            ? route(
                                                'movies.show',
                                                $movie['id'] ?? 0
                                            )
                                            : url(
                                                '/movies/' .
                                                ($movie['id'] ?? 0)
                                            );

                                    @endphp



                                    <!-- ポスター -->

                                    <a href="{{ $movieShowUrl }}"
                                        class="flex-none w-[110px] h-[150px] bg-gray-900 rounded-md overflow-hidden border border-gray-800 relative shadow-md group">


                                        @if(!empty($movie['poster_path']))


                                            <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}"
                                                alt="{{ $movie['title'] ?? '' }}"
                                                class="w-[110px] h-[150px] object-cover group-hover:scale-105 transition duration-300">


                                        @else


                                            <div class="w-full h-full flex items-center justify-center text-gray-600 text-[10px]">

                                                No Image

                                            </div>


                                        @endif



                                        <!-- 評価 -->

                                        <div
                                            class="absolute top-1 right-1 bg-black/85 border border-amber-500/80 text-amber-400 text-[9px] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-sm">

                                            ★

                                            {{
                            (
                                isset($movie['vote_average'])
                                && $movie['vote_average'] > 0
                            )
                            ? number_format(
                                $movie['vote_average'],
                                1
                            )
                            : '-'
                                                    }}

                                        </div>


                                    </a>


                        @endforeach


                    </div>

                </div>

            </div>


        @endif



        <!-- =================================================
             コピーライト
        ================================================== -->

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 text-center text-[10px] text-gray-500 font-medium">


            <p class="m-0">

                &copy; 2026 MovieMood. All rights reserved.

            </p>

        </div>


    </footer>


</body>

</html>