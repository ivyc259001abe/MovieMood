<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - ホーム</title>

    <style>
        * {
            box-sizing: border-box;
        }

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

        /* ヘッダー */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 15px 30px;
            background-color: #050505;
            border-bottom: 1px solid #222;
        }

        .header-logo {
            color: #d4af37;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
            flex-shrink: 0;
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
            min-width: 0;
        }

        /* プロフィールアイコン */
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1.5px solid #d4af37;
            background-color: #171717;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .user-avatar-image {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-avatar-initial {
            color: #d4af37;
            font-size: 1rem;
            font-weight: bold;
        }

        .user-name {
            color: #ffffff;
            font-weight: bold;
            font-size: 0.9rem;
            overflow-wrap: anywhere;
        }

        .header-btn {
            border: 1px solid #d4af37;
            color: #d4af37;
            padding: 5px 14px;
            border-radius: 15px;
            text-decoration: none;
            font-size: 0.8rem;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .header-btn:hover {
            background-color: #d4af37;
            color: #000;
        }

        /* メイン */
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 20px;
            max-width: 500px;
            margin: 0 auto;
            width: 100%;
        }

        .welcome-title {
            color: #d4af37;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
            overflow-wrap: anywhere;
        }

        /* 検索 */
        .search-container {
            width: 100%;
            margin-bottom: 30px;
        }

        .search-form {
            display: flex;
            position: relative;
            width: 100%;
        }

        .search-input {
            width: 100%;
            padding: 12px 20px 12px 45px;
            border-radius: 25px;
            border: none;
            background-color: #ffffff;
            color: #000000;
            font-size: 0.95rem;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.1rem;
            color: #666;
            pointer-events: none;
        }

        /* 気分タグ */
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

        /* ピックアップ */
        .pickup-card {
            background-color: #111111;
            border: 1px solid #333333;
            border-radius: 20px;
            padding: 20px;
            width: 100%;
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

        /* 下部ボタン */
        .bottom-actions {
            display: flex;
            gap: 15px;
            width: 100%;
        }

        .action-btn-mypage,
        .action-btn-logout {
            flex: 1;
            padding: 12px;
            border-radius: 20px;
            text-decoration: none;
            text-align: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .action-btn-mypage {
            background-color: #222222;
            color: #ffffff;
            border: 1px solid #444444;
        }

        .action-btn-logout {
            background-color: #ff4d4d;
            color: #ffffff;
        }

        @media (max-width: 600px) {
            header {
                padding: 12px 14px;
                gap: 8px;
            }

            .header-logo {
                font-size: 1.15rem;
            }

            .user-nav {
                gap: 8px;
            }

            .user-name {
                font-size: 0.75rem;
                max-width: 130px;
            }

            .user-avatar {
                width: 32px;
                height: 32px;
            }

            .header-btn {
                padding: 5px 9px;
                font-size: 0.75rem;
            }

            main {
                padding: 25px 16px;
            }

            .welcome-title {
                font-size: 1.25rem;
            }
        }

        @media (max-width: 380px) {
            .user-name {
                display: none;
            }

            .bottom-actions {
                gap: 8px;
            }

            .action-btn-mypage,
            .action-btn-logout {
                padding: 10px 6px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>

    @php
        $user = Auth::user();

        // ニックネームを優先し、なければ名前を使用
        $nickname = trim((string) ($user?->nickname ?? ''));

        if ($nickname === '') {
            $nickname = trim((string) ($user?->name ?? ''));
        }

        if ($nickname === '') {
            $nickname = trim((string) session('user_name', ''));
        }

        if ($nickname === '') {
            $nickname = 'ゲスト';
        }

        // プロフィール画像
        $avatar = $user?->avatar;

        // 表示する先頭1文字
        $initial = mb_substr($nickname, 0, 1);
    @endphp

    <!-- ヘッダー -->
    <header>
        <a href="/" class="header-logo">MovieMood</a>

        <div class="user-nav">

            <div class="user-info">


                <!-- プロフィールアイコン -->
                <div class="user-avatar">

                    @php
                        $avatarUrl = null;

                        if (is_string($avatar) && trim($avatar) !== '') {
                            $avatarPath = trim($avatar);

                            if (
                                \Illuminate\Support\Str::startsWith(
                                    $avatarPath,
                                    ['http://', 'https://']
                                )
                            ) {
                                // 外部URLの場合
                                $avatarUrl = $avatarPath;
                            } else {
                                // 先頭の / を取り除く
                                $avatarPath = ltrim($avatarPath, '/');

                                if (\Illuminate\Support\Str::startsWith($avatarPath, 'uploads/')) {
                                    // public/uploads/ に保存された画像
                                    $avatarUrl = asset($avatarPath);
                                } elseif (\Illuminate\Support\Str::startsWith($avatarPath, 'storage/')) {
                                    // public/storage/ 経由の画像
                                    $avatarUrl = asset($avatarPath);
                                } else {
                                    // storage/app/public/ 内の画像を想定
                                    $avatarUrl = asset('storage/' . $avatarPath);
                                }
                            }
                        }
                    @endphp

                    <!-- 画像がある場合 -->
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="プロフィール画像" class="user-avatar-image"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                        <span class="user-avatar-initial" style="display: none;">
                            {{ $initial }}
                        </span>
                    @else
                        <!-- 画像がない場合はニックネームの先頭1文字 -->
                        <span class="user-avatar-initial">
                            {{ $initial }}
                        </span>
                    @endif

                </div>


                <!-- ユーザー名 -->
                <span class="user-name">
                    Welcome {{ $nickname }}さん！
                </span>

            </div>

            @auth
                <a href="{{ route('profile.edit') }}" class="header-btn">
                    ⚙️ 設定
                </a>
            @else
                <a href="{{ route('login') }}" class="header-btn">
                    ログイン
                </a>
            @endauth

        </div>
    </header>

    <!-- メインコンテンツ -->
    <main>

        <div class="welcome-title">
            Welcome {{ $nickname }}さん！
        </div>

        <!-- キーワード検索 -->
        <div class="search-container">

            <form action="/search" method="GET" class="search-form">

                <span class="search-icon">🔍</span>

                <input type="text" name="keyword" class="search-input" placeholder="キーワード検索">

            </form>

        </div>

        <!-- 気分選択 -->
        <div class="section-title">
            ▼ 今のあなたの「気分」は？
        </div>

        <div class="mood-grid">

            <a href="/search?mood=号泣" class="mood-btn">
                😭 #号泣
            </a>

            <a href="/search?mood=スカッと" class="mood-btn">
                😆 #スカッと
            </a>

            <a href="/search?mood=ハラハラ" class="mood-btn">
                😱 #ハラハラ
            </a>

            <a href="/search?mood=キュン" class="mood-btn">
                ❤️ #キュン
            </a>

        </div>

        <!-- 本日のピックアップ -->
        <div class="pickup-card">

            <div class="pickup-label">
                本日のピックアップムード
            </div>

            <div class="pickup-text">
                「月曜から夜更かし…」 おすすめが出る
            </div>

        </div>

        <!-- 下部アクション -->
        <div class="bottom-actions">

            @auth
                <a href="/mypage/watchlist" class="action-btn-mypage">
                    ⚙️ マイページへ
                </a>

                <form action="{{ route('logout') }}" method="POST" style="flex: 1; display: flex;">
                    @csrf
                    <button type="submit" class="action-btn-logout" style="width: 100%; border: none; cursor: pointer;">
                        ログアウト
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="action-btn-mypage">
                    ログイン
                </a>

                <a href="{{ route('register') }}" class="action-btn-logout">
                    新規登録
                </a>
            @endauth

        </div>

    </main>

</body>

</html>