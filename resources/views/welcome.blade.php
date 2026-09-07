<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - ホーム</title>
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

        /* ヘッダーエリア */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #050505;
            border-bottom: 1px solid #222;
        }

        .header-logo {
            color: #d4af37;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
        }

        .user-nav {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 1.5px solid #d4af37;
        }

        .user-name {
            color: #ffffff;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .header-btn {
            border: 1px solid #d4af37;
            color: #d4af37;
            padding: 5px 14px;
            border-radius: 15px;
            text-decoration: none;
            font-size: 0.8rem;
            transition: all 0.2s;
        }

        .header-btn:hover {
            background-color: #d4af37;
            color: #000;
        }

        /* メインコンテンツ */
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 20px;
            max-width: 500px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }

        .welcome-title {
            color: #d4af37;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
        }

        /* 検索バー */
        .search-container {
            width: 100%;
            margin-bottom: 30px;
        }

        .search-form {
            display: flex;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 12px 20px 12px 45px;
            border-radius: 25px;
            border: none;
            background-color: #ffffff;
            color: #000000;
            font-size: 0.95rem;
            box-sizing: border-box;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.1rem;
            color: #666;
        }

        /* 気分タグエリア */
        .section-title {
            color: #ffffff;
            font-size: 1.1rem;
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
        }

        .mood-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            width: 100%;
            margin-bottom: 25px;
        }

        .mood-btn {
            background-color: #ffffff;
            color: #000000;
            padding: 12px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.95rem;
            text-align: center;
            transition: transform 0.1s, background-color 0.2s;
        }

        .mood-btn:hover {
            transform: scale(1.02);
            background-color: #f0f0f0;
        }

        /* 本日のピックアップ */
        .pickup-card {
            background-color: #111111;
            border: 1px solid #333333;
            border-radius: 20px;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            margin-bottom: 30px;
        }

        .pickup-label {
            color: #888888;
            font-size: 0.8rem;
            margin-bottom: 5px;
        }

        .pickup-text {
            color: #d4af37;
            font-size: 1rem;
            font-weight: bold;
        }

        /* 下部固定アクションボタン */
        .bottom-actions {
            display: flex;
            gap: 15px;
            width: 100%;
        }

        .action-btn-mypage {
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

        .action-btn-logout {
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

    <!-- ヘッダー -->
    <header>
        <a href="/" class="header-logo">MovieMood</a>
        <div class="user-nav">
            <div class="user-info">
                <img src="https://via.placeholder.com/32?text=Icon" class="user-avatar" alt="アイコン">
                <span class="user-name">Welcome {{ session('user_name', 'ゲスト') }}さん！</span>
            </div>
            <a href="/profile" class="header-btn">⚙️ 設定</a>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main>
        <div class="welcome-title">Welcome {{ session('user_name', 'ゲスト') }}さん！</div>

        <!-- キーワード検索 -->
        <div class="search-container">
            <form action="/search" method="GET" class="search-form">
                <span class="search-icon">🔍</span>
                <input type="text" name="keyword" class="search-input" placeholder="キーワード検索">
            </form>
        </div>

        <!-- 気分選択ボタン -->
        <div class="section-title">▼ 今のあなたの「気分」は？</div>
        <div class="mood-grid">
            <a href="/search?mood=号泣" class="mood-btn">😭 #号泣</a>
            <a href="/search?mood=スカッと" class="mood-btn">😆 #スカッと</a>
            <a href="/search?mood=ハラハラ" class="mood-btn">😱 #ハラハラ</a>
            <a href="/search?mood=キュン" class="mood-btn">❤️ #キュン</a>
        </div>

        <!-- 本日のピックアップ -->
        <div class="pickup-card">
            <div class="pickup-label">本日のピックアップムード</div>
            <div class="pickup-text">「月曜から夜更かし…」 おすすめが出る</div>
        </div>

        <!-- 下部アクション -->
        <div class="bottom-actions">
            <a href="/mypage/watchlist" class="action-btn-mypage">⚙️ マイページへ</a>
            <a href="/logout" class="action-btn-logout">ログアウト</a>
        </div>
    </main>

</body>

</html>