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
</head>

<body
    style="margin: 0; padding: 0; background-color: #000000; color: #ffffff; font-family: sans-serif; min-height: 100vh;">

    <!-- ヘッダーエリア -->
    <header
        style="width: 100%; border-bottom: 1px solid #1f2937; background-color: #000000; padding: 20px 32px; box-sizing: border-box;">
        <div
            style="max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; align-items: flex-start; gap: 4px;">
            <a href="/"
                style="display: flex; align-items: center; gap: 8px; text-decoration: none; font-size: 28px; font-weight: 900; color: #f59e0b; letter-spacing: 0.05em;">
                <svg style="width: 32px; height: 32px; fill: #f59e0b;" viewBox="0 0 24 24">
                    <path
                        d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4h-2l2 4H9L7 4H5L3 8V4H1v16h22V4h-5zM8 17H6v-2h2v2zm0-4H6v-2h2v2zm0-4H6V7h2v2zm10 8h-8v-2h8v2zm0-4h-8v-2h8v2zm0-4h-8V7h8v2z" />
                </svg>
                MovieMood
            </a>
            <p style="color: #ffffff; font-size: 15px; font-weight: 700; margin: 4px 0 0 0; letter-spacing: 0.03em;">
                〜 あなたの「今の気分」が、次に観る映画を決める。 〜
            </p>
        </div>
    </header>

    <!-- メインコンテンツ（固定やFlexによる強制限界を排除） -->
    <main style="width: 100%;">
        {{ $slot }}
    </main>

</body>

</html>