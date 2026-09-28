<x-guest-layout>
    <!-- 全体コンテナ（画面高さにぴったりフィット） -->
    <div
        style="position: relative; width: 100%; min-height: calc(100vh - 60px); background-color: #000000; color: #ffffff; display: flex; flex-direction: column; justify-content: space-between; align-items: center; padding: 12px 0 0 0; box-sizing: border-box; overflow: hidden;">

        <!-- 1. 中央：WELCOME ＆ 新規アカウント登録カードエリア -->
        <div
            style="width: 100%; max-width: 360px; margin: auto; display: flex; flex-direction: column; align-items: center; z-index: 10;">

            <!-- WELCOME タイトル -->
            <h1
                style="font-size: 18px; font-weight: 900; color: #f59e0b; letter-spacing: 0.15em; text-transform: uppercase; margin: 0 0 10px 0; text-align: center;">
                WELCOME
            </h1>

            <!-- 新規登録カード -->
            <div
                style="width: 100%; background-color: #0d1117; border: 1px solid #1f2937; border-radius: 12px; padding: 16px 20px; box-sizing: border-box; box-shadow: 0 10px 25px rgba(0,0,0,0.8);">

                <div style="text-align: center; margin-bottom: 12px;">
                    <h2 style="font-size: 15px; font-weight: 800; color: #ffffff; margin: 0;">新規アカウント登録</h2>
                </div>

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data"
                    style="display: flex; flex-direction: column; gap: 8px; margin: 0;">
                    @csrf

                    <!-- アイコン画像選択 (リアルタイムプレビュー対応) -->
                    <div
                        style="display: flex; flex-direction: column; align-items: center; gap: 2px; margin-bottom: 4px;">
                        <label for="profile_photo"
                            style="cursor: pointer; display: flex; flex-direction: column; align-items: center;">
                            <!-- アイコンプレビュー枠 -->
                            <div
                                style="width: 52px; height: 52px; border-radius: 9999px; background-color: #000000; border: 1.5px solid #f59e0b; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                                <!-- デフォルトの人型アイコン -->
                                <i id="icon-placeholder" class="fa-solid fa-user"
                                    style="font-size: 20px; color: #9ca3af;"></i>
                                <!-- 選択された画像プレビュー用 -->
                                <img id="icon-preview" src="" alt="プレビュー"
                                    style="width: 100%; height: 100%; object-fit: cover; display: none;">
                            </div>
                            <span style="font-size: 10px; color: #f59e0b; font-weight: bold; margin-top: 4px;">
                                アイコン画像を選択 (任意)
                            </span>
                        </label>
                        <input id="profile_photo" type="file" name="profile_photo" accept="image/*"
                            style="display: none;" onchange="previewImage(event)">
                        <x-input-error :messages="$errors->get('profile_photo')" class="mt-0" />
                    </div>

                    <!-- ニックネーム (Name) -->
                    <div>
                        <label for="name"
                            style="display: block; font-size: 10px; font-weight: bold; color: #e5e7eb; margin-bottom: 2px;">
                            ニックネーム
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            placeholder="例: たろう" autocomplete="name"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 6px 12px; font-size: 11px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                        <x-input-error :messages="$errors->get('name')" class="mt-0" />
                    </div>

                    <!-- メールアドレス -->
                    <div>
                        <label for="email"
                            style="display: block; font-size: 10px; font-weight: bold; color: #e5e7eb; margin-bottom: 2px;">
                            メールアドレス
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            placeholder="example@mail.com" autocomplete="username"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 6px 12px; font-size: 11px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                        <x-input-error :messages="$errors->get('email')" class="mt-0" />
                    </div>

                    <!-- パスワード -->
                    <div>
                        <label for="password"
                            style="display: block; font-size: 10px; font-weight: bold; color: #e5e7eb; margin-bottom: 2px;">
                            パスワード
                        </label>
                        <input id="password" type="password" name="password" required placeholder="8文字以上"
                            autocomplete="new-password"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 6px 12px; font-size: 11px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                        <x-input-error :messages="$errors->get('password')" class="mt-0" />
                    </div>

                    <!-- パスワード (確認用) -->
                    <div>
                        <label for="password_confirmation"
                            style="display: block; font-size: 10px; font-weight: bold; color: #e5e7eb; margin-bottom: 2px;">
                            パスワード (確認用)
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            placeholder="パスワード (確認用)" autocomplete="new-password"
                            style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 6px 12px; font-size: 11px; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-0" />
                    </div>

                    <!-- アカウント作成ボタン -->
                    <button type="submit"
                        style="width: 100%; background-color: #f59e0b; color: #000000; font-weight: 800; padding: 8px; border-radius: 9999px; font-size: 12px; border: none; cursor: pointer; transition: background-color 0.2s; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.25); margin-top: 4px;"
                        onmouseover="this.style.backgroundColor='#fbbf24'"
                        onmouseout="this.style.backgroundColor='#f59e0b'">
                        アカウントを作成
                    </button>
                </form>

                <!-- 既存ログイン案内 -->
                <div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid #1f2937; text-align: center;">
                    <a href="{{ route('login') }}"
                        style="display: inline-block; color: #f59e0b; font-weight: bold; font-size: 10px; text-decoration: none;"
                        onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1.0'">
                        すでにアカウントをお持ちの方はこちら (ログイン)
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
                            <!-- ⭕️ 修正箇所: 先頭に <div を追記しました -->
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

    <!-- プレビュー表示用JavaScript -->
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('icon-preview');
            const placeholder = document.getElementById('icon-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-guest-layout>