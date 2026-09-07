<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie['title'] ?? '映画詳細' }} - MovieMood</title>
    <style>
        body {
            background-color: #141414;
            color: #ffffff;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 40px;
        }

        a {
            color: #e50914;
            text-decoration: none;
        }

        .container {
            display: flex;
            gap: 30px;
            max-width: 900px;
            margin-top: 20px;
        }

        .poster {
            border-radius: 8px;
            max-width: 300px;
        }

        .details h1 {
            margin-top: 0;
        }

        .meta {
            color: #aaa;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <p><a href="{{ route('movies.index') }}">← 一覧に戻る</a></p>

    <div class="container">
        @if(!empty($movie['poster_path']))
            <img class="poster" src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                alt="{{ $movie['title'] }}">
        @endif

        <div class="details">
            <h1>{{ $movie['title'] ?? 'タイトル不明' }}</h1>
            <p class="meta">公開日: {{ $movie['release_date'] ?? '情報なし' }} | 評価: <span style="color:#ffbd03;">★
                    {{ number_format($movie['vote_average'] ?? 0, 1) }}</span></p>
            <h3>概要</h3>
            <p>{{ $movie['overview'] ?? '概要情報はありません。' }}</p>
        </div>
    </div>
</body>

</html>