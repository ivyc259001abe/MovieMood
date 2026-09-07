<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - レビュー投稿</title>
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
            padding: 15px 20px;
            background-color: #050505;
            border-bottom: 1px solid #222;
        }

        .back-link {
            color: #ffffff;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .header-logo {
            color: #d4af37;
            font-size: 1.3rem;
            font-weight: bold;
            text-decoration: none;
        }

        main {
            flex: 1;
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }

        .page-title {
            color: #d4af37;
            font-size: 1.3rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .target-movie-card {
            background-color: #111111;
            border: 1px solid #333333;
            border-radius: 16px;
            padding: 15px;
            text-align: center;
            margin-bottom: 25px;
        }

        .movie-label {
            font-size: 0.75rem;
            color: #888;
            margin-bottom: 4px;
        }

        .target-movie-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #ffffff;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 10px;
        }

        /* 評価（セレクトボックス） */
        .rating-select {
            width: 100%;
            padding: 12px 15px;
            border-radius: 12px;
            border: none;
            background-color: #ffffff;
            color: #000000;
            font-size: 1rem;
            font-weight: bold;
            box-sizing: border-box;
        }

        /* 感情タグ（チェックボックス群） */
        .tags-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .tag-checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            background-color: #111111;
            border: 1px solid #333333;
            padding: 10px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: bold;
            transition: border-color 0.2s;
        }

        .tag-checkbox-label:hover {
            border-color: #d4af37;
        }

        .tag-checkbox-label input[type="checkbox"] {
            accent-color: #d4af37;
            width: 16px;
            height: 16px;
        }

        /* レビュー本文 */
        .review-textarea {
            width: 100%;
            height: 120px;
            padding: 12px 15px;
            border-radius: 12px;
            border: none;
            background-color: #ffffff;
            color: #000000;
            font-size: 0.9rem;
            box-sizing: border-box;
            resize: vertical;
            font-family: inherit;
        }

        .submit-btn {
            width: 100%;
            background-color: #d4af37;
            color: #000000;
            border: none;
            padding: 14px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 25px;
            transition: background-color 0.2s;
        }

        .submit-btn:hover {
            background-color: #c5a028;
        }

        .bottom-nav {
            display: flex;
            gap: 15px;
        }

        .nav-btn-home {
            flex: 1;
            background-color: #222222;
            color: #ffffff;
            border: 1px solid #444444;
            padding: 12px;
            border-radius: 20px;
            text-decoration: none;
            text-align: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .nav-btn-logout {
            flex: 1;
            background-color: #ff4d4d;
            color: #ffffff;
            padding: 12px;
            border-radius: 20px;
            text-decoration: none;
            text-align: center;
            font-weight: bold;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>

    <header>
        <a href="/movie/1" class="back-link">← 戻る</a>
        <a href="/" class="header-logo">MovieMood</a>
        <div style="width: 50px;"></div>
    </header>

    <main>
        <div class="page-title">レビュー投稿</div>

        <!-- 対象映画情報 -->
        <div class="target-movie-card">
            <div class="movie-label">レビュー対象作品</div>
            <div class="target-movie-title">アベンジャーズ エンドゲーム</div>
        </div>

        <form action="/review" method="POST">
            @csrf
            <!-- 映画ID隠しフィールド -->
            <input type="hidden" name="movie_id" value="1">

            <!-- 星評価選択 -->
            <div class="form-group">
                <label class="form-label">▼ 評価（★数）</label>
                <select name="rating" class="rating-select" required>
                    <option value="5.0">★★★★★ (5.0)</option>
                    <option value="4.0" selected>★★★★☆ (4.0)</option>
                    <option value="3.0">★★★☆☆ (3.0)</option>
                    <option value="2.0">★★☆☆☆ (2.0)</option>
                    <option value="1.0">★☆☆☆☆ (1.0)</option>
                </select>
            </div>

            <!-- 感情タグ選択 -->
            <div class="form-group">
                <label class="form-label">▼ どんな気分になった？ (複数選択可)</label>
                <div class="tags-grid">
                    <label class="tag-checkbox-label">
                        <input type="checkbox" name="tags[]" value="号泣"> 😭 #号泣
                    </label>
                    <label class="tag-checkbox-label">
                        <input type="checkbox" name="tags[]" value="スカッと"> 😆 #スカッと
                    </label>
                    <label class="tag-checkbox-label">
                        <input type="checkbox" name="tags[]" value="ハラハラ"> 😱 #ハラハラ
                    </label>
                    <label class="tag-checkbox-label">
                        <input type="checkbox" name="tags[]" value="キュン"> ❤️ #キュン
                    </label>
                </div>
            </div>

            <!-- コメント入力 -->
            <div class="form-group">
                <label class="form-label">▼ コメント (レビュー本文)</label>
                <textarea name="comment" class="review-textarea" placeholder="感想を入力してください（例：最高の映画でした！）"
                    required></textarea>
            </div>

            <button type="submit" class="submit-btn">投稿する</button>
        </form>

        <div class="bottom-nav">
            <a href="/" class="nav-btn-home">ホームに戻る</a>
            <a href="/logout" class="nav-btn-logout">ログアウト</a>
        </div>
    </main>

</body>

</html>