<x-guest-layout>
    <!-- 全体コンテナ（画面高さにフィット） -->
    <div
        style="position: relative; width: 100%; min-height: calc(100vh - 60px); background-color: #000000; color: #ffffff; display: flex; flex-direction: column; justify-content: space-between; align-items: center; padding: 12px 0 0 0; box-sizing: border-box; overflow: hidden;">

        <!-- 1. 中央：パスワード再設定カードエリア -->
        <div
            style="width: 100%; max-width: 360px; margin: auto; display: flex; flex-direction: column; align-items: center; z-index: 10;">

            <!-- カード本文 -->
            <div
                style="width: 100%; background-color: #0d1117; border: 1px solid #1f2937; border-radius: 12px; padding: 20px; box-sizing: border-box; box-shadow: 0 10px 25px rgba(0,0,0,0.8);">

                <div style="text-align: center; margin-bottom: 14px;">
                    <h1 style="font-size: 16px; font-weight: 800; color: #f59e0b; margin: 0 0 6px 0;">パスワード再設定</h1>
                    <p style="font-size: 10px; color: #9ca3af; margin: 0; line-height: 1.4;">
                        登録メールアドレスと<br>新しいパスワードを入力してください。
                    </p>
                </div>

                <!-- ステータスメッセージ（送信完了時） -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}"
                    style="display: flex; flex-direction: column; gap: 10px; margin: 0;">
                    @csrf

                    <!-- メールアドレス -->
                    <div>
                        <label for="email"
                            style="display: block; font-size: 10px; font-weight: bold; color: #e5e7eb; margin-bottom: 3px;">
                            メールアドレス
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="メールアドレス" autocomplete="username"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 7px 12px; font-size: 11px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                        <x-input-error :messages="$errors->get('email')" class="mt-0" />
                    </div>

                    <!-- パスワード（必要に応じて入力用フィールド） -->
                    <div>
                        <label for="password"
                            style="display: block; font-size: 10px; font-weight: bold; color: #e5e7eb; margin-bottom: 3px;">
                            新しいパスワード
                        </label>
                        <input id="password" type="password" name="password" placeholder="新しいパスワード"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 7px 12px; font-size: 11px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                    </div>

                    <!-- パスワード（確認） -->
                    <div>
                        <label for="password_confirmation"
                            style="display: block; font-size: 10px; font-weight: bold; color: #e5e7eb; margin-bottom: 3px;">
                            新しいパスワード (確認)
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            placeholder="もう一度入力"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 7px 12px; font-size: 11px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                    </div>

                    <!-- 送信/変更ボタン -->
                    <button type="submit"
                        style="width: 100%; background-color: #f59e0b; color: #000000; font-weight: 800; padding: 9px; border-radius: 9999px; font-size: 12px; border: none; cursor: pointer; transition: background-color 0.2s; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.25); margin-top: 6px;"
                        onmouseover="this.style.backgroundColor='#fbbf24'"
                        onmouseout="this.style.backgroundColor='#f59e0b'">
                        パスワードを変更する
                    </button>
                </form>

                <!-- ログイン画面に戻るリンク -->
                <div style="margin-top: 12px; padding-top: 10px; border-top: 1px solid #1f2937; text-align: center;">
                    <a href="{{ route('login') }}"
                        style="display: inline-block; color: #f59e0b; font-weight: bold; font-size: 11px; text-decoration: none;"
                        onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1.0'">
                        ログイン画面に戻る
                    </a>
                </div>

            </div>
        </div>

        <!-- 2. 下部：POPULAR MOVIES -->
        @if(!empty($popularMovies) && count($popularMovies) > 0)
            <div
                style="width: 100vw; padding-top: 10px; padding-bottom: 6px; text-align: center; border-top: 1px solid #111827; background-color: #000000; flex-shrink: 0; margin-top: 10px;">
                <div style="margin-bottom: 6px;">
                    <span
                        style="font-size: 10px; font-weight: 800; color: #9ca3af; letter-spacing: 0.1em; text-transform: uppercase;">
                        POPULAR MOVIES
                    </span>
                </div>
                <div
                    style="overflow: hidden; width: 100vw; white-space: nowrap; display: flex; pointer-events: none; user-select: none;">
                    <div class="infinite-scroll-track"
                        style="display: flex; gap: 8px; animation: loop-scroll 120s linear infinite; will-change: transform;">
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

            <!-- コピーライト表示 -->
            <footer
                style="width: 100%; text-align: center; padding: 6px 0 2px 0; background-color: #000000; flex-shrink: 0;">
                <p style="font-size: 10px; color: #6b7280; margin: 0; font-family: sans-serif;">
                    &copy; {{ date('Y') }} MovieMood. All rights reserved.
                </p>
            </footer>

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

    </div>
</x-guest-layout>