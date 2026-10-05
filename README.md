# MovieMood 🎬

**MovieMood** は、映画の「鑑賞後のレビュー」と「鑑賞前の期待度メモ」をまとめて管理・投稿できるWebアプリケーションです。
TMDB (The Movie Database) APIと連携し、映画の詳細情報参照、評価スライダー、雰囲気タグ機能、いいねやコメントによるユーザー間コミュニケーションを提供します。

---

## ✨ 主な機能

- **🎬 映画情報の参照 (TMDB連携)**
    - ポスター画像、あらすじ、ジャンル、監督、上演時間などの映画情報を表示
    - TMDBの平均評価スコアの表示および本家ページへの外部リンク
- **🍿 鑑賞後レビュー機能**
    - **1.0 〜 10.0** の詳細なスコア評価（インタラクティブなスライダーUI）
    - 感想・見どころのテキスト投稿
    - 観た後の気分に合わせたマルチタグ選択（最大4つ）
- **✨ 鑑賞前（観たい）メモ機能**
    - 今後観たい映画に対する「期待度」の記録
    - 観る前の期待ポイントに合わせたタグ選択（「号泣しそう」「キュンとしそう」など）
- **🔖 「みたい！」（ウォッチリスト）登録**
    - ワンクリックで気になった映画をリストへ保存・解除
- **💬 ソーシャル・コミュニケーション機能**
    - レビューに対する「いいね (Like)」機能
    - レビューへのコメント投稿・スレッド表示（開閉アコーディオンUI）

---

## 🛠️ 技術スタック

| カテゴリ           | 使用技術                                             |
| ------------------ | ---------------------------------------------------- |
| **バックエンド**   | PHP 8.x / Laravel                                    |
| **フロントエンド** | Blade Template / JavaScript (Vanilla) / Tailwind CSS |
| **アイコン・UI**   | FontAwesome 6                                        |
| **外部API**        | TMDB (The Movie Database) API                        |
| **データベース**   | MySQL (または SQLite / PostgreSQL)                   |

---

## 🚀 開発環境の構築手順

### 1. リポジトリのクローン

```bash
git clone https://github.com/your-username/moviemood.git
cd moviemood
2. 依存関係のインストール
Bash
composer install
npm install && npm run dev
3. 環境変数の設定 (.env)
.env.example をコピーして .env を作成します。

Bash
cp .env.example .env
php artisan key:generate
.env 内にデータベース接続情報と TMDB APIキー を設定してください。

コード スニペット
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=moviemood
DB_USERNAME=root
DB_PASSWORD=

# TMDB API Settings
TMDB_API_KEY=your_tmdb_api_key_here
4. マイグレーションとダミーデータの挿入
Bash
php artisan migrate
5. ローカルサーバーの起動
Bash
php artisan serve
ブラウザで http://localhost:8000 にアクセスして動作品質をご確認ください。

📁 画面・コンポーネント構成（Blade）
resources/views/movies/show.blade.php: 映画詳細およびレビュー投稿・一覧表示画面

resources/views/layouts/app.blade.php: アプリケーション共通レイアウト
