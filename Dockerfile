FROM php:8.3-cli

# 必要なシステムパッケージとPHP拡張機能をインストール
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    curl \
    && docker-php-ext-install pdo_mysql zip bcmath

# Node.js と npm をインストール（ここを修正しました！）
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Composerをステージからコピー
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 開発用サーバーの起動
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]