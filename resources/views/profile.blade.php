<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - プロフィール編集</title>
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
            max-width: 450px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }

        .page-title {
            color: #d4af37;
            font-size: 1.3rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px;
        }

        .avatar-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 25px;
        }

        .current-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #d4af37;
            margin-bottom: 12px;
        }

        .file-label {
            background-color: #222222;
            color: #ffffff;
            border: 1px solid #444444;
            padding: 6px 15px;
            border-radius: 15px;
            font-size: 0.8rem;
            cursor: pointer;
            font-weight: bold;
        }

        .file-input {
            display: none;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            margin-bottom: 8px;
            font-weight: bold;
            color: #ffffff;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border-radius: 20px;
            border: none;
            background-color: #ffffff;
            color: #000000;
            font-size: 0.95rem;
            box-sizing: border-box;
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
            margin-top: 10px;
            margin-bottom: 30px;
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
        <a href="/mypage/watchlist" class="back-link">← マイページ</a>
        <a href="/" class="header-logo">MovieMood</a>
        <div style="width: 50px;"></div>
    </header>

    <main>
        <div class="page-title">⚙️ アカウント設定</div>

        <form action="/profile" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- アイコン画像変更 -->
            <div class="avatar-section">
                <img src="https://via.placeholder.com/80?text=User" class="current-avatar" alt="アバター画像">
                <label class="file-label">
                    画像を変更
                    <input type="file" name="icon_image" class="file-input" accept="image/*">
                </label>
            </div>

            <div class="form-group">
                <label>ニックネーム</label>
                <input type="text" name="name" class="form-control" value="映画太郎" required>
            </div>

            <div class="form-group">
                <label>メールアドレス (ID)</label>
                <input type="email" name="email" class="form-control" value="taro@example.com" required>
            </div>

            <div class="form-group">
                <label>新しいパスワード（変更する場合のみ入力）</label>
                <input type="password" name="password" class="form-control" placeholder="新しいパスワード">
            </div>

            <button type="submit" class="submit-btn">設定を保存する</button>
        </form>

        <div class="bottom-nav">
            <a href="/" class="nav-btn-home">ホームに戻る</a>
            <a href="/logout" class="nav-btn-logout">ログアウト</a>
        </div>
    </main>

</body>

</html>