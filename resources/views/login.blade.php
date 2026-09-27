<x-guest-layout>
    <!-- 全体コンテナ -->
    <div
        style="position: relative; width: 100vw; height: calc(100vh - 65px); min-height: 600px; overflow: hidden; background-color: #000000; color: #ffffff; margin-left: calc(-50vw + 50%); display: flex; flex-direction: column; justify-content: flex-start; align-items: center;">

        <!-- 1. 中央：WELCOME ＆ ログインカードエリア -->
        <div
            style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin-top: 20px; z-index: 10;">

            <!-- WELCOME タイトル -->
            <h1
                style="font-size: 22px; font-weight: 900; color: #f59e0b; letter-spacing: 0.15em; text-transform: uppercase; margin: 0 0 12px 0; text-align: center;">
                WELCOME
            </h1>

            <!-- ログインカード -->
            <div
                style="width: 380px; background-color: #0d1117; border: 1px solid #1f2937; border-radius: 16px; padding: 24px 24px; box-sizing: border-box; box-shadow: 0 10px 25px rgba(0,0,0,0.8);">

                <div style="text-align: center; margin-bottom: 16px;">
                    <h2 style="font-size: 16px; font-weight: 800; color: #ffffff; margin: 0;">ログイン</h2>
                </div>

                <!-- セッションステータス -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="/" style="display: flex; flex-direction: column; gap: 12px; margin: 0;">
                    @csrf

                    <!-- メールアドレス -->
                    <div>
                        <label for="email"
                            style="display: block; font-size: 11px; font-weight: bold; color: #e5e7eb; margin-bottom: 4px;">
                            メールアドレス (ID)
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="メールアドレス (ID)"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 9px 14px; font-size: 12px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- パスワード -->
                    <div>
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <label for="password" style="font-size: 11px; font-weight: bold; color: #e5e7eb;">
                                パスワード
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    style="font-size: 10px; color: #9ca3af; text-decoration: none;"
                                    onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#9ca3af'">
                                    パスワードをお忘れですか？
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required placeholder="パスワード"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 9px 14px; font-size: 12px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- ログイン状態を保持 -->
                    <div style="display: flex; align-items: center;">
                        <label for="remember_me" style="display: inline-flex; align-items: center; cursor: pointer;">
                            <input id="remember_me" type="checkbox" name="remember"
                                style="width: 14px; height: 14px; border-radius: 4px; background-color: #000; border: 1px solid #374151; color: #f59e0b;">
                            <span style="margin-left: 6px; font-size: 11px; color: #9ca3af;">ログイン状態を保持する</span>
                        </label>
                    </div>

                    <!-- ログインボタン -->
                    <button type="submit"
                        style="width: 100%; background-color: #f59e0b; color: #000000; font-weight: 800; padding: 10px; border-radius: 9999px; font-size: 13px; border: none; cursor: pointer; transition: background-color 0.2s; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.25);"
                        onmouseover="this.style.backgroundColor='#fbbf24'"
                        onmouseout="this.style.backgroundColor='#f59e0b'">
                        ログイン
                    </button>
                </form>

                <!-- 新規登録案内 -->
                <div style="margin-top: 12px; padding-top: 10px; border-top: 1px solid #1f2937; text-align: center;">
                    <a href="{{ route('register') }}"
                        style="display: inline-block; color: #f59e0b; font-weight: bold; font-size: 11px; text-decoration: none;"
                        onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1.0'">
                        新規登録はこちら &gt;
                    </a>
                </div>

            </div>
        </div>

        <!-- 2. 下部：POPULAR MOVIES （スピードをゆっくりに調整） -->
        @if(!empty($popularMovies) && count($popularMovies) > 0)
            <div
                style="position: absolute; bottom: -110px; left: 0; width: 100vw; background-color: #000000; z-index: 5; text-align: center;">

                <!-- 見出しテキスト -->
                <div style="margin-bottom: 8px;">
                    <span
                        style="font-size: 11px; font-weight: 700; color: #9ca3af; letter-spacing: 0.1em; text-transform: uppercase;">
                        POPULAR MOVIES 
                    </span>
                </div>

                <!-- 無限スライドトラック -->
                <div style="overflow: hidden; width: 100vw; white-space: nowrap; display: flex;">

                    <!-- animation時間を 35s から 70s に変更して、流れるスピードを約半分（ゆっくり）に修正 -->
                    <div class="infinite-scroll-track"
                        style="display: flex; gap: 12px; animation: loop-scroll 70s linear infinite; will-change: transform;">

                        <!-- 1周目 -->
                        @foreach($popularMovies as $movie)
                            <div
                                style="flex: 0 0 140px; width: 140px; background-color: #111827; border-radius: 10px; overflow: hidden; border: 1px solid #1f2937; display: inline-block; vertical-align: top;">
                                <div
                                    style="width: 100%; height: 190px; background-color: #1f2937; position: relative; overflow: hidden;">
                                    @if(!empty($movie['poster_path']))
                                        <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}"
                                            alt="{{ $movie['title'] ?? 'Movie' }}"
                                            style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                    @endif

                                    @if(!empty($movie['vote_average']))
                                        <div
                                            style="position: absolute; top: 6px; right: 6px; background-color: rgba(0,0,0,0.85); border: 1px solid rgba(245,158,11,0.6); color: #fbbf24; font-size: 10px; font-weight: 800; padding: 2px 6px; border-radius: 9999px;">
                                            ★ {{ number_format($movie['vote_average'], 1) }}
                                        </div>
                                    @endif
                                </div>

                                <div style="padding: 6px 8px; text-align: left; background-color: #111827;">
                                    <h3 style="font-size: 11px; font-weight: 700; color: #ffffff; margin: 0 0 2px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                        title="{{ $movie['title'] ?? '' }}">
                                        {{ $movie['title'] ?? 'タイトル不明' }}
                                    </h3>
                                    <p style="font-size: 9px; color: #9ca3af; margin: 0;">
                                        {{ isset($movie['release_date']) ? substr($movie['release_date'], 0, 4) : '' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach

                        <!-- 2周目（繋ぎ目用） -->
                        @foreach($popularMovies as $movie)
                            <div
                                style="flex: 0 0 140px; width: 140px; background-color: #111827; border-radius: 10px; overflow: hidden; border: 1px solid #1f2937; display: inline-block; vertical-align: top;">
                                <div
                                    style="width: 100%; height: 190px; background-color: #1f2937; position: relative; overflow: hidden;">
                                    @if(!empty($movie['poster_path']))
                                        <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}"
                                            alt="{{ $movie['title'] ?? 'Movie' }}"
                                            style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                    @endif

                                    @if(!empty($movie['vote_average']))
                                        <div
                                            style="position: absolute; top: 6px; right: 6px; background-color: rgba(0,0,0,0.85); border: 1px solid rgba(245,158,11,0.6); color: #fbbf24; font-size: 10px; font-weight: 800; padding: 2px 6px; border-radius: 9999px;">
                                            ★ {{ number_format($movie['vote_average'], 1) }}
                                        </div>
                                    @endif
                                </div>

                                <div style="padding: 6px 8px; text-align: left; background-color: #111827;">
                                    <h3 style="font-size: 11px; font-weight: 700; color: #ffffff; margin: 0 0 2px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                        title="{{ $movie['title'] ?? '' }}">
                                        {{ $movie['title'] ?? 'タイトル不明' }}
                                    </h3>
                                    <p style="font-size: 9px; color: #9ca3af; margin: 0;">
                                        {{ isset($movie['release_date']) ? substr($movie['release_date'], 0, 4) : '' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

            <!-- アニメーション定義 -->
            <style>
                @keyframes loop-scroll {
                    0% {
                        transform: translateX(0);
                    }

                    100% {
                        transform: translateX(-50%);
                    }
                }

                .infinite-scroll-track:hover {
                    animation-play-state: paused;
                }
            </style>
        @endif

    </div>
</x-guest-layout>