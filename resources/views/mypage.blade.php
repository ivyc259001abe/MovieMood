<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - マイページ</title>
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

        /* ユーザープロフィールヘッダー */
        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #111111;
            border: 1px solid #333333;
            border-radius: 20px;
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        .user-flex {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar-large {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #d4af37;
        }

        .user-name-display {
            font-size: 1.1rem;
            font-weight: bold;
            color: #ffffff;
        }

        .edit-profile-btn {
            border: 1px solid #d4af37;
            color: #d4af37;
            padding: 6px 14px;
            border-radius: 15px;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: bold;
            transition: all 0.2s;
        }

        .edit-profile-btn:hover {
            background-color: #d4af37;
            color: #000000;
        }

        /* セクションタイトル */
        .section-header {
            color: #d4af37;
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #333;
        }

        /* ウォッチリスト（横スクロール風リスト） */
        .watchlist-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }

        .watch-card {
            background-color: #111111;
            border: 1px solid #333333;
            border-radius: 12px;
            overflow: hidden;
            text-decoration: none;
            color: #ffffff;
        }

        .watch-poster-dummy {
            width: 100%;
            height: 120px;
            background-color: #222222;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            font-size: 0.8rem;
        }

        .watch-title {
            padding: 8px 10px;
            font-size: 0.85rem;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* 投稿したレビューリスト */
        .my-review-card {
            background-color: #111111;
            border: 1px solid #333333;
            border-radius: 16px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .my-review-movie {
            font-size: 1rem;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 5px;
        }

        .my-review-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .my-review-stars {
            color: #d4af37;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .my-review-tag {
            background-color: #222222;
            color: #d4af37;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
        }

        .my-review-text {
            font-size: 0.85rem;
            color: #cccccc;
            line-height: 1.4;
            margin-bottom: 10px;
        }

        .my-review-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
        }

        .likes-count {
            color: #ff4d4d;
        }

        .action-links a {
            color: #aaa;
            text-decoration: none;
            margin-left: 10px;
        }

        .action-links a:hover {
            color: #d4af37;
        }

        .bottom-nav {
            display: flex;
            gap: 15px;
            margin-top: 20px;
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
        <a href="/" class="back-link">← ホーム</a>
        <a href="/" class="header-logo">MovieMood</a>
        <div style="width: 50px;"></div>
    </header>

    <main>
        <!-- ユーザープロフィール表示 -->
        <div class="profile-header">
            <div class="user-flex">
                <img src="https://via.placeholder.com/45?text=User" class="user-avatar-large" alt="アバター">
                <div class="user-name-display">{{ session('user_name', '映画太郎') }}</div>
            </div>
            <a href="/profile" class="edit-profile-btn">⚙️ 編集</a>
        </div>

        <!-- WATCHLIST セクション -->
        <div class="section-header">📌 WATCHLIST (みたい！作品)</div>
        <div class="watchlist-grid">
            <a href="/movie/1" class="watch-card">
                <div class="watch-poster-dummy">ポスター</div>
                <div class="watch-title">アベンジャーズ</div>
            </a>
            <a href="/movie/2" class="watch-card">
                <div class="watch-poster-dummy">ポスター</div>
                <div class="watch-title">サンプル映画タイトル</div>
            </a>
        </div>

        <!-- REVIEWS セクション -->
        <div class="section-header">📝 自分のレビュー投稿履歴</div>

        <div class="my-review-card">
            <div class="my-review-movie">アベンジャーズ エンドゲーム</div>
            <div class="my-review-meta">
                <span class="my-review-stars">★★★★☆ (4.0)</span>
                <span class="my-review-tag">#号泣</span>
            </div>
            <div class="my-review-text">何度見てもクライマックスで泣いてしまう最高の作品！</div>
            <div class="my-review-actions">
                <span class="likes-count">❤️ 12 いいね！</span>
                <div class="action-links">
                    <a href="#">編集</a>
                    <a href="#" style="color: #ff4d4d;">削除</a>
                </div>
            </div>
        </div>

        <div class="bottom-nav">
            <a href="/" class="nav-btn-home">ホームに戻る</a>
            <a href="/logout" class="nav-btn-logout">ログアウト</a>
        </div>
    </main>

</body>

</html>