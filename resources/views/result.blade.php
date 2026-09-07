<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - 映画コミュニティ</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #000000;
            color: #ffffff;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            border-bottom: 1px solid #222;
        }

        .logo {
            color: #d4af37;
            font-size: 1.8rem;
            font-weight: bold;
            text-decoration: none;
        }

        .back-btn {
            color: #d4af37;
            text-decoration: none;
            border: 1px solid #d4af37;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .back-btn:hover {
            background-color: #d4af37;
            color: #000000;
        }

        main {
            flex: 1;
            padding: 40px 20px;
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }

        .result-title {
            color: #d4af37;
            font-size: 1.4rem;
            margin-bottom: 25px;
            text-align: center;
            font-weight: bold;
        }

        .movie-card {
            background-color: #0a0a0a;
            border: 1px solid #d4af37;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.15);
            margin-bottom: 30px;
        }

        .movie-card h2 {
            margin: 0 0 10px 0;
            color: #ffffff;
            font-size: 1.8rem;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }

        .movie-card p {
            color: #cccccc;
            font-size: 1rem;
            line-height: 1.7;
            margin: 0;
        }

        .community-section {
            background-color: #0a0a0a;
            border-radius: 16px;
            padding: 25px;
            border: 1px solid #333;
        }

        .community-title {
            color: #d4af37;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .review-box {
            background-color: #141414;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 12px;
            border-left: 3px solid #d4af37;
        }

        .review-user {
            font-size: 0.85rem;
            color: #d4af37;
            margin-bottom: 6px;
        }

        .review-text {
            font-size: 0.95rem;
            color: #eeeeee;
            margin: 0;
        }

        .comment-form {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .comment-input {
            flex: 1;
            padding: 12px 18px;
            border-radius: 25px;
            border: 1px solid #333;
            background-color: #141414;
            color: #ffffff;
            font-size: 0.95rem;
        }

        .comment-input:focus {
            outline: none;
            border-color: #d4af37;
        }

        .comment-btn {
            background-color: #d4af37;
            color: #000000;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .comment-btn:hover {
            background-color: #f3e5ab;
        }
    </style>
</head>

<body>

    <header>
        <a href="/" class="logo">MovieMood</a>
        <a href="/" class="back-btn">← 気分を選び直す</a>
    </header>

    <main>
        <div class="result-title">
            {{ $movie['mood_name'] }} 気分の方へおすすめの映画作品
        </div>

        <div class="movie-card">
            <h2>{{ $movie['title'] }}</h2>
            <p>{{ $movie['description'] }}</p>
        </div>

        <div class="community-section">
            <div class="community-title">💬 みんなの感想・レビュー</div>

            <div class="review-box">
                <div class="review-user">映画好きユーザーA ★★★★☆</div>
                <p class="review-text">後半の展開にボロ泣きしました...。今の気分にぴったりでした！</p>
            </div>

            <div class="review-box">
                <div class="review-user">映画好きユーザーB ★★★★★</div>
                <p class="review-text">映像美と音楽が最高。何度も見返したくなる名作です。</p>
            </div>

            <form class="comment-form" onsubmit="event.preventDefault();">
                <input type="text" class="comment-input" placeholder="この映画の感想やレビューを書く...">
                <button type="submit" class="comment-btn">投稿</button>
            </form>
        </div>
    </main>

</body>

</html>