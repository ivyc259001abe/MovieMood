<x-guest-layout>
    <!-- 全体コンテナ（画面の高さに合わせて上下に適切に分散） -->
    <div
        style="position: relative; width: 100%; min-height: calc(100vh - 80px); background-color: #000000; color: #ffffff; display: flex; flex-direction: column; justify-content: space-between; align-items: center; padding: 4px 0 0 0; box-sizing: border-box;">

        <!-- 1. 中央：WELCOME ＆ 新規アカウント登録カードエリア -->
        <div
            style="width: 100%; max-width: 350px; margin: 0 auto; display: flex; flex-direction: column; align-items: center; z-index: 10;">

            <!-- WELCOME タイトル -->
            <h1
                style="font-size: 16px; font-weight: 900; color: #f59e0b; letter-spacing: 0.15em; text-transform: uppercase; margin: 0 0 6px 0; text-align: center;">
                WELCOME
            </h1>

            <!-- 新規登録カード（縦幅をコンパクトに最適化） -->
            <div
                style="width: 100%; background-color: #0d1117; border: 1px solid #1f2937; border-radius: 12px; padding: 12px 18px; box-sizing: border-box; box-shadow: 0 10px 25px rgba(0,0,0,0.8);">

                <div style="text-align: center; margin-bottom: 8px;">
                    <h2 style="font-size: 14px; font-weight: 800; color: #ffffff; margin: 0;">新規アカウント登録</h2>
                </div>

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data"
                    style="display: flex; flex-direction: column; gap: 6px; margin: 0;">
                    @csrf

                    <!-- アイコン画像選択 (人型シルエット ＆ 2MBメッセージ対応) -->
                    <div
                        style="display: flex; flex-direction: column; align-items: center; gap: 1px; margin-bottom: 2px;">
                        <label for="profile_photo"
                            style="cursor: pointer; display: flex; flex-direction: column; align-items: center;">
                            <!-- アイコンプレビュー枠 -->
                            <div
                                style="width: 48px; height: 48px; border-radius: 9999px; background-color: #1f2937; border: 2px solid #f59e0b; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; box-shadow: 0 2px 8px rgba(0,0,0,0.5);">

                                <!-- 人物のシルエットアイコン（SVG埋め込み） -->
                                <div id="icon-placeholder"
                                    style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; background-color: #1f2937;">
                                    <svg style="width: 24px; height: 24px; fill: #9ca3af;" viewBox="0 0 24 24">
                                        <path
                                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                    </svg>
                                </div>

                                <!-- 選択された画像プレビュー用 -->
                                <img id="icon-preview" src="" alt="プレビュー"
                                    style="width: 100%; height: 100%; object-fit: cover; display: none;">
                            </div>

                            <span style="font-size: 9.5px; color: #f59e0b; font-weight: bold; margin-top: 3px;">
                                アイコン画像を選択 (任意)
                            </span>
                            <span style="font-size: 8px; color: #9ca3af; margin-top: 0px;">
                                ※2MB以下の画像ファイル
                            </span>
                        </label>

                        <input id="profile_photo" type="file" name="profile_photo" accept="image/*"
                            style="display: none;" onchange="previewImage(event)">

                        <!-- JS用リアルタイムエラー -->
                        <p id="icon-js-error"
                            style="display: none; font-size: 9px; color: #ef4444; font-weight: bold; margin: 2px 0 0 0; text-align: center; line-height: 1.2;">
                        </p>

                        <!-- サーバー側エラー -->
                        @if ($errors->has('profile_photo'))
                            <p
                                style="font-size: 9px; color: #ef4444; font-weight: bold; margin: 2px 0 0 0; text-align: center; line-height: 1.2;">
                                2MB以下の画像を選択してください。
                            </p>
                        @endif
                    </div>

                    <!-- ニックネーム (Name) -->
                    <div>
                        <label for="name"
                            style="display: block; font-size: 9.5px; font-weight: bold; color: #e5e7eb; margin-bottom: 1px;">
                            ニックネーム
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            placeholder="例: たろう" autocomplete="name"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 4px 10px; font-size: 10.5px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                        @if ($errors->has('name'))
                            <p style="font-size: 9px; color: #ef4444; font-weight: bold; margin: 1px 0 0 4px;">
                                {{ $errors->first('name') }}
                            </p>
                        @endif
                    </div>

                    <!-- メールアドレス -->
                    <div>
                        <label for="email"
                            style="display: block; font-size: 9.5px; font-weight: bold; color: #e5e7eb; margin-bottom: 1px;">
                            メールアドレス
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            placeholder="example@mail.com" autocomplete="username"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 4px 10px; font-size: 10.5px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                        @if ($errors->has('email'))
                            <p style="font-size: 9px; color: #ef4444; font-weight: bold; margin: 1px 0 0 4px;">
                                {{ $errors->first('email') }}
                            </p>
                        @endif
                    </div>

                    <!-- パスワード -->
                    <div>
                        <label for="password"
                            style="display: block; font-size: 9.5px; font-weight: bold; color: #e5e7eb; margin-bottom: 1px;">
                            パスワード
                        </label>
                        <input id="password" type="password" name="password" required placeholder="8文字以上"
                            autocomplete="new-password"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 4px 10px; font-size: 10.5px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                        @if ($errors->has('password'))
                            <p style="font-size: 9px; color: #ef4444; font-weight: bold; margin: 1px 0 0 4px;">
                                {{ $errors->first('password') }}
                            </p>
                        @endif
                    </div>

                    <!-- パスワード (確認用) -->
                    <div>
                        <label for="password_confirmation"
                            style="display: block; font-size: 9.5px; font-weight: bold; color: #e5e7eb; margin-bottom: 1px;">
                            パスワード (確認用)
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            placeholder="パスワード (確認用)" autocomplete="new-password"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 4px 10px; font-size: 10.5px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                        @if ($errors->has('password_confirmation'))
                            <p style="font-size: 9px; color: #ef4444; font-weight: bold; margin: 1px 0 0 4px;">
                                {{ $errors->first('password_confirmation') }}
                            </p>
                        @endif
                    </div>

                    <!-- アカウント作成ボタン -->
                    <button type="submit"
                        style="width: 100%; background-color: #f59e0b; color: #000000; font-weight: 800; padding: 7px; border-radius: 9999px; font-size: 11.5px; border: none; cursor: pointer; transition: background-color 0.2s; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.25); margin-top: 4px;"
                        onmouseover="this.style.backgroundColor='#fbbf24'"
                        onmouseout="this.style.backgroundColor='#f59e0b'">
                        アカウントを作成
                    </button>
                </form>

                <!-- 既存ログイン案内 -->
                <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #1f2937; text-align: center;">
                    <a href="{{ route('login') }}"
                        style="display: inline-block; color: #f59e0b; font-weight: bold; font-size: 9.5px; text-decoration: none;"
                        onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1.0'">
                        すでにアカウントをお持ちの方はこちら (ログイン)
                    </a>
                </div>

            </div>
        </div>

        <!-- 2. 下部：POPULAR MOVIES （100%表示でチラ見せされる領域） -->
        @if(!empty($popularMovies) && count($popularMovies) > 0)
            <div
                style="width: 100vw; padding-top: 6px; padding-bottom: 4px; text-align: center; border-top: 1px solid #111827; background-color: #000000; flex-shrink: 0; margin-top: 6px;">
                <div style="margin-bottom: 4px;">
                    <span
                        style="font-size: 9.5px; font-weight: 800; color: #9ca3af; letter-spacing: 0.1em; text-transform: uppercase;">
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
                                style="flex: 0 0 95px; width: 95px; height: 130px; background-color: #111827; border-radius: 6px; overflow: hidden; border: 1px solid #1f2937; position: relative; box-sizing: border-box;">
                                @if(!empty($movie['poster_path']))
                                    <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}"
                                        alt="{{ $movie['title'] ?? 'Movie' }}"
                                        style="width: 95px; height: 130px; object-fit: cover; display: block;">
                                @endif
                                @if(isset($movie['vote_average']) && $movie['vote_average'] > 0)
                                    <div
                                        style="position: absolute; top: 4px; right: 4px; z-index: 50; background-color: rgba(0,0,0,0.85); border: 1px solid rgba(245,158,11,0.8); color: #fbbf24; font-size: 8.5px; font-weight: 800; padding: 2px 4px; border-radius: 9999px; line-height: 1; display: flex; align-items: center; gap: 2px;">
                                        ★ {{ number_format((float) $movie['vote_average'], 1) }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- コピーライト表示（最下部） -->
        <footer
            style="width: 100%; text-align: center; padding: 4px 0 2px 0; background-color: #000000; flex-shrink: 0;">
            <p style="font-size: 9.5px; color: #6b7280; margin: 0; font-family: sans-serif;">
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

    </div>

    <!-- プレビュー ＆ ファイルサイズ判定用JavaScript -->
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('icon-preview');
            const placeholder = document.getElementById('icon-placeholder');
            const jsError = document.getElementById('icon-js-error');

            // エラー表示のリセット
            jsError.style.display = 'none';
            jsError.textContent = '';

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB

                // 2MBを超えている場合
                if (file.size > maxSize) {
                    jsError.textContent = '2MB以下の画像を選択してください。';
                    jsError.style.display = 'block';

                    // 選択されたファイルをリセット＆プレビュー解除
                    input.value = '';
                    preview.style.display = 'none';
                    if (placeholder) {
                        placeholder.style.display = 'flex';
                    }
                    return;
                }

                // 2MB以下の場合はプレビュー表示
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-guest-layout>