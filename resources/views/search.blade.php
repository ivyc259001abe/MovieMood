<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - 検索結果</title>
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
            max-width: 600px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }

        .search-title {
            color: #d4af37;
            font-size: 1.4rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .search-box {
            margin-bottom: 25px;
        }

        .search-input {
            width: 100%;
            padding: 12px 20px;
            border-radius: 25px;
            border: none;
            background-color: #ffffff;
            color: #000000;
            font-size: 0.95rem;
            box-sizing: border-box;
        }

        /* 映画グリッド表示 */
        .movie-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .movie-card {
            background-color: #111111;
            border: 1px solid #333333;
            border-radius: 16px;
            overflow: hidden;
            text-decoration: none;
            color: #ffffff;
            transition: transform 0.2s;
            display: flex;
            flex-direction: column;
        }

        .movie-card:hover {
            transform: translateY(-4px);
            border-color: #d4af37;
        }

        .poster-dummy {
            width: 100%;
            height: 180px;
            background-color: #222222;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888888;
            font-weight: bold;
        }

        .movie-info {
            padding: 12px;
        }

        .movie-title {
            font-size: 0.95rem;
            font-weight: bold;
            margin-bottom: 6px;
            color: #ffffff;
        }

        .movie-meta {
            font-size: 0.75rem;
            color: #aaa;
            line-height: 1.4;
        }

        /* 0件ヒット（検索エラー）時のスタイル */
        .no-result {
            text-align: center;
            padding: 30px 10px;
        }

        .error-icon {
            font-size: 3rem;
            color: #ff4d4d;
            margin-bottom: 15px;
        }

        .error-msg {
            font-size: 0.95rem;
            color: #ccc;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .mood-tags-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 15px;
        }

        .tag-btn {
            background-color: #ffffff;
            color: #000000;
            padding: 10px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.85rem;
            text-align: center;
        }

        /* フッター固定導線 */
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
        <a href="/" class="back-link">← 戻る</a>
        <a href="/" class="header-logo">MovieMood</a>
        <div style="width: 50px;"></div> <!-- レイアウト崩れ防止用スペース -->
    </header>

    <main>
        <!-- 検索結果のタイトル（動的に変更可能） -->
        <div class="search-title"># 号泣</div>

        <div class="search-box">
            <input type="text" class="search-input" placeholder="🔍 キーワード検索" value="号泣">
        </div>

        <!-- パターンA：検索結果が存在する場合（カード一覧） -->
        <div class="movie-grid">
            <a href="/movie/1" class="movie-card">
                <div class="poster-dummy">ポスター</div>
                <div class="movie-info">
                    <div class="movie-title">アベンジャーズ エンドゲーム</div>
                    <div class="movie-meta">2019年4月 / 監督: アンソニー・ルッソ</div>
                </div>
            </a>

            <a href="/movie/2" class="movie-card">
                <div class="poster-dummy">ポスター</div>
                <div class="movie-info">
                    <div class="movie-title">サンプル作品タイトル</div>
                    <div class="movie-meta">2023年 / あらすじテキスト...</div>
                </div>
            </a>
        </div>

        <!-- パターンB：検索結果が0件の場合（コメントアウト解除で確認可能） -->
        <!-- 
        <div class="no-result">
            <div class="error-icon">✖</div>
            <div class="error-msg">
                該当する映画は見つかりませんでした<br>
                スペルを確認するか、別のワードで検索してください。
            </div>
            <div style="font-weight: bold; color: #d4af37; margin-bottom: 10px;"># 気分から探す</div>
            <div class="mood-tags-container">
                <a href="/search?mood=号泣" class="tag-btn">😭 #号泣したい</a>
                <a href="/search?mood=スカッと" class="tag-btn">😆 #スカッと</a>
                <a href="/search?mood=ハラハラ" class="tag-btn">😱 #ハラハラ</a>
                <a href="/search?mood=キュン" class="tag-btn">❤️ #キュン</a>
            </div>
        </div>
        -->

        <div class="bottom-nav">
            <a href="/" class="nav-btn-home">ホームに戻る</a>
            <a href="/logout" class="nav-btn-logout">ログアウト</a>
        </div>
    </main>

</body>

</html>