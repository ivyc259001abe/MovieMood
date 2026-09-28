<x-guest-layout>
    <!-- 全体コンテナ -->
    <div
        style="position: relative; width: 100vw; min-height: calc(100vh - 60px); background-color: #000000; color: #ffffff; display: flex; flex-direction: column; justify-content: space-between; align-items: center; padding: 12px 0 0 0; box-sizing: border-box; overflow-x: hidden;">

        <!-- 1. 中央：WELCOME ＆ ログインカードエリア -->
        <div
            style="width: 100%; max-width: 360px; margin: auto; display: flex; flex-direction: column; align-items: center; z-index: 10;">

            <!-- WELCOME タイトル -->
            <h1
                style="font-size: 18px; font-weight: 900; color: #f59e0b; letter-spacing: 0.15em; text-transform: uppercase; margin: 0 0 10px 0; text-align: center;">
                WELCOME
            </h1>

            <!-- ログインカード -->
            <div
                style="width: 100%; background-color: #0d1117; border: 1px solid #1f2937; border-radius: 12px; padding: 20px; box-sizing: border-box; box-shadow: 0 10px 25px rgba(0,0,0,0.8);">

                <div style="text-align: center; margin-bottom: 16px;">
                    <h2 style="font-size: 15px; font-weight: 800; color: #ffffff; margin: 0;">ログイン</h2>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}"
                    style="display: flex; flex-direction: column; gap: 12px; margin: 0;">
                    @csrf

                    <!-- メールアドレス -->
                    <div>
                        <label for="email"
                            style="display: block; font-size: 11px; font-weight: bold; color: #e5e7eb; margin-bottom: 4px;">
                            メールアドレス(ID)
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="username"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 8px 14px; font-size: 12px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- パスワード -->
                    <div>
                        <label for="password"
                            style="display: block; font-size: 11px; font-weight: bold; color: #e5e7eb; margin-bottom: 4px;">
                            パスワード(PW)
                        </label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 8px 14px; font-size: 12px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- ログイン状態を保存 -->
                    <div style="display: flex; align-items: center; margin-top: 2px;">
                        <label for="remember_me" style="display: inline-flex; align-items: center; cursor: pointer;">
                            <input id="remember_me" type="checkbox" name="remember"
                                style="border-radius: 4px; border: 1px solid #374151; background-color: #000000; color: #f59e0b;">
                            <span style="margin-left: 8px; font-size: 11px; color: #9ca3af;">ログイン状態を保存する</span>
                        </label>
                    </div>

                    <!-- ログインボタン -->
                    <button type="submit"
                        style="width: 100%; background-color: #f59e0b; color: #000000; font-weight: 800; padding: 9px; border-radius: 9999px; font-size: 13px; border: none; cursor: pointer; transition: background-color 0.2s; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.25); margin-top: 4px;"
                        onmouseover="this.style.backgroundColor='#fbbf24'"
                        onmouseout="this.style.backgroundColor='#f59e0b'">
                        ログイン
                    </button>

                    <!-- リンクエリア -->
                    <div
                        style="display: flex; justify-between; align-items: center; margin-top: 8px; padding-top: 10px; border-top: 1px solid #1f2937; font-size: 10px;">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="color: #9ca3af; text-decoration: underline;"
                                onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#9ca3af'">
                                パスワードを忘れた場合
                            </a>
                        @endif

                        <a href="{{ route('register') }}"
                            style="color: #f59e0b; font-weight: bold; text-decoration: none;"
                            onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1.0'">
                            新規登録はこちら
                        </a>
                    </div>
                </form>

            </div>
        </div>

        <!-- 2. 下部エリア（ポスター ＋ 統一コピーライト） -->
        <div style="width: 100vw; background-color: #000000; flex-shrink: 0; margin-top: 8px;">

            @if(!empty($popularMovies) && count($popularMovies) > 0)
                <div style="padding-top: 8px; text-align: center; border-top: 1px solid #111827;">
                    <div style="margin-bottom: 6px;">
                        <span
                            style="font-size: 10px; font-weight: 800; color: #9ca3af; letter-spacing: 0.1em; text-transform: uppercase;">
                            POPULAR MOVIES
                        </span>
                    </div>

                    <!-- CSSキーフレームアニメーションでスムーズに流れるエリア -->
                    <div
                        style="overflow: hidden; width: 100vw; white-space: nowrap; display: flex; pointer-events: none; user-select: none;">
                        <div class="infinite-scroll-track"
                            style="display: flex; gap: 8px; animation: loop-scroll 100s linear infinite; will-change: transform;">
                            @php
                                $loopMovies = array_merge($popularMovies, $popularMovies);
                            @endphp
                            @foreach($loopMovies as $movie)
                                <div
                                    style="flex: 0 0 110px; width: 110px; height: 150px; background-color: #111827; border-radius: 6px; overflow: hidden; border: 1px solid #1f2937; position: relative; box-sizing: border-box;">
                                    @if(!empty($movie['poster_path']))
                                        <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}"
                                            alt="{{ $movie['title'] ?? 'Movie' }}"
                                            style="width: 110px; height: 150px; object-fit: cover; display: block;">
                                    @endif
                                    @if(isset($movie['vote_average']) && $movie['vote_average'] > 0)
                                        <div
                                            style="position: absolute; top: 4px; right: 4px; z-index: 50; background-color: rgba(0,0,0,0.85); border: 1px solid rgba(245,158,11,0.8); color: #fbbf24; font-size: 9px; font-weight: 800; padding: 2px 5px; border-radius: 9999px; line-height: 1; display: flex; align-items: center; gap: 2px;">
                                            ★ {{ number_format((float) $movie['vote_average'], 1) }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <style>
                    @keyframes loop-scroll {
                        0% {
                            transform: translateX(0);
                        }

                        100% {
                            transform: translateX(-50%);
                        }
                    }
                </style>
            @endif

            <!-- 3. 最下部：統一されたコピーライト（常時表示） -->
            <footer
                style="width: 100%; text-align: center; padding: 12px 0 8px 0; background-color: #000000; border-top: 1px solid #111827;">
                <p style="font-size: 10px; color: #6b7280; margin: 0; font-family: sans-serif; font-weight: 500;">
                    &copy; 2026 MovieMood. All rights reserved.
                </p>
            </footer>
        </div>

    </div>
</x-guest-layout>