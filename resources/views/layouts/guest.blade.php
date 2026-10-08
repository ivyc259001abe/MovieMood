<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MovieMood') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* =========================================================
           MovieMood ロゴ
           ログイン前・ログイン後で共通
        ========================================================== */

        .moviemood-logo {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 1;
            color: #f59e0b;
            text-decoration: none;
            white-space: nowrap;
        }

        .moviemood-logo:hover {
            color: #fbbf24;
        }

        @media (max-width: 639px) {
            .moviemood-logo {
                font-size: 21px;
            }
        }
    </style>
</head>

<body style="
        margin: 0;
        padding: 0;
        background-color: #000000;
        color: #ffffff;
        font-family: sans-serif;
        min-height: 100vh;
    ">

    <!-- =========================================================
         ヘッダー
         MovieMoodのロゴのみ表示
         ※キャッチコピーはログイン画面本体側に任せる
    ========================================================== -->

    <header style="
            width: 100%;
            border-bottom: 1px solid #1f2937;
            background-color: #000000;
            padding: 20px 32px;
            box-sizing: border-box;
        ">

        <div style="
                max-width: 1200px;
                margin: 0 auto;
                display: flex;
                align-items: center;
            ">

            <!-- MovieMood ロゴ -->

            <a href="/" class="moviemood-logo" style="
                    display: flex;
                    align-items: center;
                    gap: 8px;
                " title="HOME画面へ戻る">

                <svg style="
                        width: 30px;
                        height: 30px;
                        fill: #f59e0b;
                        flex-shrink: 0;
                    " viewBox="0 0 24 24" aria-hidden="true">

                    <path
                        d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4h-2l2 4H9L7 4H5L3 8V4H1v16h22V4h-5zM8 17H6v-2h2v2zm0-4H6v-2h2v2zm0-4H6V7h2v2zm10 8h-8v-2h8v2zm0-4h-8v-2h8v2zm0-4h-8V7h8v2z" />

                </svg>

                <span>MovieMood</span>

            </a>

        </div>

    </header>


    <!-- =========================================================
         メインコンテンツ
    ========================================================== -->

    <main style="width: 100%;">

        {{ $slot }}

    </main>

</body>

</html>