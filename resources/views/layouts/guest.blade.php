<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MovieMood') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* =========================================================
           MovieMood ロゴスタイル（3枚目のスタイリッシュ極太ツートンに固定）
        ========================================================== */
        .moviemood-logo {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            font-size: 28px;
            font-weight: 900;
            letter-spacing: -0.03em;
            line-height: 1;
            text-decoration: none;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
        }

        .moviemood-logo .logo-movie {
            color: #f59e0b;
            transition: color 0.2s;
        }

        .moviemood-logo .logo-mood {
            color: #fbbf24;
            transition: color 0.2s;
        }

        .moviemood-logo:hover .logo-movie {
            color: #d97706;
        }

        .moviemood-logo:hover .logo-mood {
            color: #f59e0b;
        }

        @media (max-width: 639px) {
            .moviemood-logo {
                font-size: 24px;
            }
        }
    </style>
</head>

<body style="
        margin: 0;
        padding: 0;
        background-color: #000000;
        color: #ffffff;
        font-family: ui-sans-serif, system-ui, sans-serif;
        min-height: 100vh;
    ">

    <!-- =========================================================
         ヘッダー（スタイリッシュ超極太ツートンロゴ）
    ========================================================== -->

    <header style="
            width: 100%;
            border-bottom: 1px solid #1f2937;
            background-color: #000000;
            padding: 18px 32px;
            box-sizing: border-box;
        ">

        <div style="
                max-width: 1200px;
                margin: 0 auto;
                display: flex;
                align-items: center;
            ">

            <!-- MovieMood ロゴ -->
            <a href="/" class="moviemood-logo" title="HOME画面へ戻る">
                <span class="logo-movie">Movie</span><span class="logo-mood">Mood</span>
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