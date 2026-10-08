<x-guest-layout>

    <!-- =========================================================
         MovieMood ログイン画面
         PC：2カラム
         スマホ：1カラム
    ========================================================== -->

    <div style="
        position: relative;
        width: 100%;
        min-height: calc(100vh - 90px);
        background-color: #000000;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        box-sizing: border-box;
        overflow-x: hidden;
    ">

        <!-- =====================================================
             メインエリア
             ※ POPULAR MOVIESとは完全に分離
        ====================================================== -->

        <div class="login-main" style="
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 45px 24px 30px 24px;
            box-sizing: border-box;
            flex: 1 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        ">

            <!-- =================================================
                 左側：MovieMood紹介
            ================================================== -->

            <div class="login-intro" style="
                flex: 1 1 auto;
                min-width: 0;
                padding: 20px 55px 20px 25px;
                box-sizing: border-box;
            ">

                <!-- 小さなラベル -->
                <div style="
                    display: inline-block;
                    color: #f59e0b;
                    border: 1px solid rgba(245,158,11,0.45);
                    background-color: rgba(245,158,11,0.08);
                    border-radius: 9999px;
                    padding: 5px 12px;
                    font-size: 10px;
                    font-weight: 800;
                    letter-spacing: 0.12em;
                    margin-bottom: 18px;
                ">
                    MOVIE REVIEW SNS
                </div>


                <!-- MovieMood -->
                <h1 style="
                    margin: 0;
                    color: #f59e0b;
                    font-size: 42px;
                    line-height: 1.1;
                    font-weight: 900;
                    letter-spacing: 0.02em;
                ">
                    🎬 MovieMood
                </h1>


                <!-- キャッチコピー -->
                <p style="
                    margin: 18px 0 0 0;
                    color: #ffffff;
                    font-size: 20px;
                    line-height: 1.8;
                    font-weight: 800;
                ">
                    観る前の「気分」も、<br>
                    観た後の「感想」も、<br>
                    映画と一緒に。
                </p>


                <!-- 説明 -->
                <p style="
                    margin: 20px 0 0 0;
                    max-width: 440px;
                    color: #9ca3af;
                    font-size: 13px;
                    line-height: 2;
                ">
                    観たい映画を記録し、観る前の期待度や気分を残す。
                    そして、観た後には感想や感情をレビューとして記録する。
                    MovieMoodは、映画との思い出を残しながら、
                    他の映画ファンともつながれる場所です。
                </p>


                <!-- =================================================
                     感情タグ
                ================================================== -->

                <div style="
                    margin-top: 22px;
                    max-width: 440px;
                ">

                    <!-- 鑑賞後の感情 -->
                    <div style="
                        margin-bottom: 14px;
                    ">

                        <div style="
                            color: #fbbf24;
                            font-size: 11px;
                            font-weight: 800;
                            margin-bottom: 7px;
                        ">
                            鑑賞後の感情
                        </div>

                        <div style="
                            display: flex;
                            flex-wrap: wrap;
                            gap: 7px;
                        ">

                            <span style="
                                color: #fbbf24;
                                border: 1px solid rgba(245,158,11,0.55);
                                background-color: rgba(245,158,11,0.08);
                                border-radius: 9999px;
                                padding: 5px 10px;
                                font-size: 10px;
                                font-weight: 700;
                            ">
                                #号泣
                            </span>

                            <span style="
                                color: #fbbf24;
                                border: 1px solid rgba(245,158,11,0.55);
                                background-color: rgba(245,158,11,0.08);
                                border-radius: 9999px;
                                padding: 5px 10px;
                                font-size: 10px;
                                font-weight: 700;
                            ">
                                #スカッと
                            </span>

                            <span style="
                                color: #fbbf24;
                                border: 1px solid rgba(245,158,11,0.55);
                                background-color: rgba(245,158,11,0.08);
                                border-radius: 9999px;
                                padding: 5px 10px;
                                font-size: 10px;
                                font-weight: 700;
                            ">
                                #ハラハラ
                            </span>

                            <span style="
                                color: #fbbf24;
                                border: 1px solid rgba(245,158,11,0.55);
                                background-color: rgba(245,158,11,0.08);
                                border-radius: 9999px;
                                padding: 5px 10px;
                                font-size: 10px;
                                font-weight: 700;
                            ">
                                #キュン
                            </span>

                        </div>

                    </div>


                    <!-- 鑑賞前の気分 -->
                    <div>

                        <div style="
                            color: #d8b4fe;
                            font-size: 11px;
                            font-weight: 800;
                            margin-bottom: 7px;
                        ">
                            鑑賞前の気分
                        </div>

                        <div style="
                            display: flex;
                            flex-wrap: wrap;
                            gap: 7px;
                        ">

                            <span style="
                                color: #d8b4fe;
                                border: 1px solid rgba(168,85,247,0.45);
                                background-color: rgba(88,28,135,0.25);
                                border-radius: 9999px;
                                padding: 5px 10px;
                                font-size: 10px;
                                font-weight: 700;
                            ">
                                #号泣しそう
                            </span>

                            <span style="
                                color: #d8b4fe;
                                border: 1px solid rgba(168,85,247,0.45);
                                background-color: rgba(88,28,135,0.25);
                                border-radius: 9999px;
                                padding: 5px 10px;
                                font-size: 10px;
                                font-weight: 700;
                            ">
                                #スカッとしそう
                            </span>

                            <span style="
                                color: #d8b4fe;
                                border: 1px solid rgba(168,85,247,0.45);
                                background-color: rgba(88,28,135,0.25);
                                border-radius: 9999px;
                                padding: 5px 10px;
                                font-size: 10px;
                                font-weight: 700;
                            ">
                                #ハラハラしそう
                            </span>

                            <span style="
                                color: #d8b4fe;
                                border: 1px solid rgba(168,85,247,0.45);
                                background-color: rgba(88,28,135,0.25);
                                border-radius: 9999px;
                                padding: 5px 10px;
                                font-size: 10px;
                                font-weight: 700;
                            ">
                                #キュンとしそう
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 右側：ログインカード
            ================================================== -->

            <div class="login-card-wrapper" style="
                width: 390px;
                flex: 0 0 390px;
                box-sizing: border-box;
            ">

                <!-- WELCOME -->
                <div style="
                    text-align: center;
                    margin-bottom: 12px;
                ">

                    <div style="
                        color: #f59e0b;
                        font-size: 16px;
                        font-weight: 900;
                        letter-spacing: 0.18em;
                    ">
                        WELCOME
                    </div>

                    <div style="
                        margin-top: 4px;
                        color: #6b7280;
                        font-size: 10px;
                        letter-spacing: 0.08em;
                    ">
                        MOVIE LOVER'S SPACE
                    </div>

                </div>


                <!-- ログインカード -->
                <div style="
                    width: 100%;
                    background-color: #0d1117;
                    border: 1px solid #1f2937;
                    border-radius: 16px;
                    padding: 28px;
                    box-sizing: border-box;
                    box-shadow:
                        0 20px 50px rgba(0,0,0,0.65),
                        0 0 35px rgba(245,158,11,0.04);
                ">

                    <!-- タイトル -->
                    <div style="
                        text-align: center;
                        margin-bottom: 22px;
                    ">

                        <h2 style="
                            font-size: 20px;
                            font-weight: 800;
                            color: #ffffff;
                            margin: 0;
                        ">
                            ログイン
                        </h2>

                        <p style="
                            margin: 6px 0 0 0;
                            color: #6b7280;
                            font-size: 10px;
                        ">
                            MovieMoodをはじめましょう
                        </p>

                    </div>


                    <!-- セッションステータス -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />


                    <!-- ログインフォーム -->
                    <form method="POST" action="{{ route('login') }}" style="
                            display: flex;
                            flex-direction: column;
                            gap: 15px;
                            margin: 0;
                        ">

                        @csrf

                        <!-- メールアドレス -->
                        <div>

                            <label for="email" style="
                                display: block;
                                font-size: 11px;
                                font-weight: bold;
                                color: #e5e7eb;
                                margin-bottom: 6px;
                            ">
                                メールアドレス (ID)
                            </label>

                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                autocomplete="username" placeholder="メールアドレス" style="
                                    width: 100%;
                                    height: 44px;
                                    background-color: #000000;
                                    border: 1px solid #374151;
                                    color: #ffffff;
                                    border-radius: 9999px;
                                    padding: 0 15px;
                                    font-size: 12px;
                                    outline: none;
                                    box-sizing: border-box;
                                    transition: border-color 0.2s;
                                " onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">

                            <x-input-error :messages="$errors->get('email')" class="mt-1" style="
                                    font-size: 10px;
                                    color: #ef4444;
                                    line-height: 1.2;
                                " />

                        </div>


                        <!-- パスワード -->
                        <div>

                            <div style="
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                margin-bottom: 6px;
                            ">

                                <label for="password" style="
                                    font-size: 11px;
                                    font-weight: bold;
                                    color: #e5e7eb;
                                ">
                                    パスワード
                                </label>

                                @if (Route::has('password.request'))

                                    <a href="{{ route('password.request') }}" style="
                                                    font-size: 10px;
                                                    color: #9ca3af;
                                                    text-decoration: none;
                                                    transition: color 0.2s;
                                                " onmouseover="this.style.color='#f59e0b'"
                                        onmouseout="this.style.color='#9ca3af'">
                                        パスワードをお忘れですか？
                                    </a>

                                @endif

                            </div>


                            <input id="password" type="password" name="password" required
                                autocomplete="current-password" placeholder="パスワード" style="
                                    width: 100%;
                                    height: 44px;
                                    background-color: #000000;
                                    border: 1px solid #374151;
                                    color: #ffffff;
                                    border-radius: 9999px;
                                    padding: 0 15px;
                                    font-size: 12px;
                                    outline: none;
                                    box-sizing: border-box;
                                    transition: border-color 0.2s;
                                " onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">

                            <x-input-error :messages="$errors->get('password')" class="mt-1" />

                        </div>


                        <!-- ログイン状態を保持 -->
                        <div>

                            <label for="remember_me" style="
                                    display: inline-flex;
                                    align-items: center;
                                    cursor: pointer;
                                ">

                                <input id="remember_me" type="checkbox" name="remember" style="
                                        width: 14px;
                                        height: 14px;
                                        border-radius: 4px;
                                        background-color: #000000;
                                        border: 1px solid #374151;
                                        accent-color: #f59e0b;
                                    ">

                                <span style="
                                    margin-left: 7px;
                                    font-size: 11px;
                                    color: #9ca3af;
                                ">
                                    ログイン状態を保持する
                                </span>

                            </label>

                        </div>


                        <!-- ログインボタン -->
                        <button type="submit" style="
                                width: 100%;
                                height: 46px;
                                background-color: #f59e0b;
                                color: #000000;
                                font-weight: 900;
                                border-radius: 9999px;
                                font-size: 13px;
                                border: none;
                                cursor: pointer;
                                transition: all 0.2s;
                                box-shadow: 0 5px 18px rgba(245,158,11,0.22);
                            " onmouseover="
                                this.style.backgroundColor='#fbbf24';
                                this.style.transform='translateY(-1px)';
                            " onmouseout="
                                this.style.backgroundColor='#f59e0b';
                                this.style.transform='translateY(0)';
                            ">
                            ログイン
                        </button>

                    </form>


                    <!-- 新規登録 -->
                    <div style="
                        margin-top: 18px;
                        padding-top: 16px;
                        border-top: 1px solid #1f2937;
                        text-align: center;
                    ">

                        <span style="
                            color: #6b7280;
                            font-size: 10px;
                        ">
                            はじめてご利用の方
                        </span>

                        <br>

                        <a href="{{ route('register') }}" style="
                                display: inline-block;
                                margin-top: 6px;
                                color: #f59e0b;
                                font-weight: bold;
                                font-size: 11px;
                                text-decoration: none;
                                transition: opacity 0.2s;
                            " onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
                            新規登録はこちら ＞
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- =====================================================
             POPULAR MOVIES
             ※ メインエリアの外側
             ※ width:100%で独立
        ====================================================== -->

        @if(!empty($popularMovies) && count($popularMovies) > 0)

            <div class="popular-movies-section" style="
                            width: 100%;
                            flex: 0 0 auto;
                            background-color: #000000;
                            border-top: 1px solid #111827;
                            box-sizing: border-box;
                            overflow: hidden;
                            clear: both;
                        ">

                <!-- 見出し -->
                <div style="
                            padding-top: 9px;
                            padding-bottom: 7px;
                            text-align: center;
                        ">

                    <span style="
                                font-size: 10px;
                                font-weight: 800;
                                color: #9ca3af;
                                letter-spacing: 0.12em;
                            ">
                        POPULAR MOVIES
                    </span>

                </div>


                <!-- =================================================
                             ポスター横スクロール
                        ================================================== -->

                <div style="
                            width: 100%;
                            overflow: hidden;
                            white-space: nowrap;
                            display: block;
                            pointer-events: none;
                            user-select: none;
                            box-sizing: border-box;
                        ">

                    <div class="infinite-scroll-track" style="
                                    display: flex;
                                    width: max-content;
                                    gap: 8px;
                                    animation: loop-scroll 120s linear infinite;
                                    will-change: transform;
                                ">

                        @php
                            $loopMovies = array_merge(
                                $popularMovies ?? [],
                                $popularMovies ?? []
                            );
                        @endphp


                        @foreach($loopMovies as $movie)

                            <div style="
                                                flex: 0 0 110px;
                                                width: 110px;
                                                height: 150px;
                                                background-color: #111827;
                                                border-radius: 6px;
                                                overflow: hidden;
                                                border: 1px solid #1f2937;
                                                position: relative;
                                                box-sizing: border-box;
                                            ">

                                @if(!empty($movie['poster_path']))

                                    <img src="https://image.tmdb.org/t/p/w300{{ $movie['poster_path'] }}"
                                        alt="{{ $movie['title'] ?? 'Movie' }}" style="
                                                                    width: 110px;
                                                                    height: 150px;
                                                                    object-fit: cover;
                                                                    display: block;
                                                                ">

                                @endif


                                <!-- スコア -->
                                @if(isset($movie['vote_average']) && $movie['vote_average'] > 0)

                                    <div style="
                                                                position: absolute;
                                                                top: 4px;
                                                                right: 4px;
                                                                z-index: 50;
                                                                background-color: rgba(0,0,0,0.85);
                                                                border: 1px solid rgba(245,158,11,0.8);
                                                                color: #fbbf24;
                                                                font-size: 9px;
                                                                font-weight: 800;
                                                                padding: 2px 5px;
                                                                border-radius: 9999px;
                                                                line-height: 1;
                                                                display: flex;
                                                                align-items: center;
                                                                gap: 2px;
                                                            ">
                                        ★ {{ number_format((float) $movie['vote_average'], 1) }}
                                    </div>

                                @endif

                            </div>

                        @endforeach

                    </div>

                </div>


                <!-- アニメーション -->
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


                <!-- コピーライト -->
                <footer style="
                            width: 100%;
                            text-align: center;
                            padding: 8px 0;
                            margin-top: 8px;
                            background-color: #000000;
                            border-top: 1px solid #111827;
                            box-sizing: border-box;
                        ">

                    <p style="
                                font-size: 10px;
                                color: #6b7280;
                                margin: 0;
                                font-family: sans-serif;
                            ">
                        &copy; {{ date('Y') }} MovieMood. All rights reserved.
                    </p>

                </footer>

            </div>

        @else

            <!-- ポスターがない場合でもフッターを横幅100%にする -->
            <footer style="
                        width: 100%;
                        text-align: center;
                        padding: 8px 0;
                        background-color: #000000;
                        border-top: 1px solid #111827;
                        box-sizing: border-box;
                    ">

                <p style="
                            font-size: 10px;
                            color: #6b7280;
                            margin: 0;
                            font-family: sans-serif;
                        ">
                    &copy; {{ date('Y') }} MovieMood. All rights reserved.
                </p>

            </footer>

        @endif

    </div>


    <!-- =========================================================
         レスポンシブ
    ========================================================== -->

    <style>
        /*
         * PC
         * 左：MovieMood
         * 右：ログイン
         */
        @media (min-width: 901px) {

            .login-main {
                flex-direction: row;
            }

        }


        /*
         * タブレット・スマートフォン
         */
        @media (max-width: 900px) {

            .login-main {
                flex-direction: column;
                align-items: center;
                padding-top: 35px;
                padding-bottom: 25px;
            }

            .login-intro {
                display: none !important;
            }

            .login-card-wrapper {
                width: 100% !important;
                max-width: 390px !important;
                flex: 0 0 auto !important;
                margin: 0 auto !important;
            }

        }


        /*
         * スマートフォン
         */
        @media (max-width: 640px) {

            .login-main {
                padding-left: 16px !important;
                padding-right: 16px !important;
            }

            .login-card-wrapper {
                max-width: 360px !important;
            }

        }
    </style>

</x-guest-layout>
