<x-app-layout>

    @php
        /*
        |--------------------------------------------------------------------------
        | 検索条件の判定
        |--------------------------------------------------------------------------
        */

        $currentMood = request('mood');
        $currentQuery = request('query');

        $moodInfo = [
            '号泣' => [
                'emoji' => '😭',
                'tag' => '#号泣',
                'title' => '号泣',
                'message' => '思いっきり泣きたいあなたへ',
            ],
            'スカッと' => [
                'emoji' => '😆',
                'tag' => '#スカッと',
                'title' => 'スカッと',
                'message' => '爽快な気分になりたいあなたへ',
            ],
            'ハラハラ' => [
                'emoji' => '😱',
                'tag' => '#ハラハラ',
                'title' => 'ハラハラ',
                'message' => 'ドキドキする映画を楽しみたいあなたへ',
            ],
            'キュン' => [
                'emoji' => '💖',
                'tag' => '#キュン',
                'title' => 'キュン',
                'message' => '心がときめく映画を探しているあなたへ',
            ],
        ];

        $isMoodSearch = !empty($currentMood) && isset($moodInfo[$currentMood]);
        $selectedMood = $isMoodSearch ? $moodInfo[$currentMood] : null;

        /*
        |--------------------------------------------------------------------------
        | 表示する映画数
        |--------------------------------------------------------------------------
        */

        $movieList = $movies ?? [];
        $movieCount = count($movieList);
    @endphp


    <style>
        /* =========================================================
           MovieMood 検索結果画面
        ========================================================== */

        .search-page {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px 16px 28px;
        }


        /* =========================================================
           上部ナビゲーション
        ========================================================== */

        .search-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #9ca3af;
            text-decoration: none;
            font-size: 13px;
            font-weight: 800;
            transition: color 0.2s ease;
            white-space: nowrap;
        }

        .back-home:hover {
            color: #f59e0b;
        }


        /* =========================================================
           再検索
        ========================================================== */

        .search-form {
            flex: 1;
            max-width: 480px;
            display: flex;
            gap: 8px;
            margin: 0;
        }

        .search-input {
            flex: 1;
            min-width: 0;
            background: #000000;
            border: 1px solid #374151;
            color: #ffffff;
            border-radius: 9999px;
            padding: 9px 16px;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .search-input::placeholder {
            color: #6b7280;
        }

        .search-input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.08);
        }

        .search-submit {
            background: #f59e0b;
            color: #000000;
            font-weight: 900;
            padding: 9px 18px;
            border-radius: 9999px;
            font-size: 13px;
            border: none;
            cursor: pointer;
            flex-shrink: 0;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .search-submit:hover {
            background: #fbbf24;
            transform: translateY(-1px);
        }


        /* =========================================================
           タイトルヘッダーカード
        ========================================================== */

        .result-header {
            background: linear-gradient(135deg,
                    rgba(18, 25, 39, 0.98),
                    rgba(15, 20, 29, 0.98));
            border: 1px solid rgba(55, 65, 81, 0.8);
            border-radius: 20px;
            padding: 20px 22px;
            margin-bottom: 22px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
        }

        .result-label {
            font-size: 10px;
            color: #fbbf24;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .result-title {
            margin: 5px 0 0;
            color: #ffffff;
            font-size: 20px;
            line-height: 1.4;
            font-weight: 900;
        }

        .result-message {
            margin-top: 6px;
            color: #9ca3af;
            font-size: 12px;
            line-height: 1.6;
        }


        /* =========================================================
           感情タグ
        ========================================================== */

        .mood-result-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 12px;
            padding: 5px 10px;
            border-radius: 9999px;
            background: rgba(245, 158, 11, 0.08);
            border: 1px solid rgba(245, 158, 11, 0.25);
            color: #fbbf24;
            font-size: 11px;
            font-weight: 800;
        }


        /* =========================================================
           映画一覧
        ========================================================== */

        .movie-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 16px;
        }


        /* =========================================================
           映画カード
        ========================================================== */

        .movie-card {
            display: flex;
            flex-direction: column;
            min-width: 0;
            padding: 10px;
            background: #121927;
            border: 1px solid rgba(55, 65, 81, 0.8);
            border-radius: 18px;
            text-decoration: none;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.22);
            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;
        }

        .movie-card:hover {
            transform: translateY(-4px);
            border-color: rgba(245, 158, 11, 0.65);
            box-shadow: 0 16px 30px rgba(0, 0, 0, 0.35);
        }


        /* =========================================================
           ポスター
        ========================================================== */

        .movie-poster {
            position: relative;
            width: 100%;
            aspect-ratio: 2 / 3;
            overflow: hidden;
            border-radius: 12px;
            background: #05080e;
            border: 1px solid rgba(55, 65, 81, 0.8);
            margin-bottom: 10px;
        }

        .movie-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .movie-card:hover .movie-poster img {
            transform: scale(1.05);
        }


        /* =========================================================
           映画情報
        ========================================================== */

        .movie-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
            min-width: 0;
        }

        .movie-title {
            color: #ffffff;
            font-size: 13px;
            line-height: 1.4;
            font-weight: 900;
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .movie-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            font-size: 10px;
        }

        .movie-year {
            color: #9ca3af;
        }

        .movie-score {
            color: #fbbf24;
            font-weight: 900;
            white-space: nowrap;
        }


        /* =========================================================
           件数表示
        ========================================================== */

        .result-count {
            margin-bottom: 12px;
            padding-left: 2px;
            color: #6b7280;
            font-size: 11px;
            font-weight: 700;
        }


        /* =========================================================
           結果なし
        ========================================================== */

        .empty-result {
            grid-column: 1 / -1;
            background: #121927;
            border: 1px solid #374151;
            border-radius: 18px;
            padding: 48px 20px;
            text-align: center;
            color: #9ca3af;
        }

        .empty-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .empty-result p {
            margin: 0;
            font-size: 14px;
        }


        /* =========================================================
           タブレット
        ========================================================== */

        @media (max-width: 1023px) {

            .movie-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }

        }


        /* =========================================================
           スマートフォン
        ========================================================== */

        @media (max-width: 640px) {

            .search-page {
                padding: 18px 12px 24px;
            }

            .search-top {
                align-items: stretch;
                margin-bottom: 18px;
            }

            .back-home {
                font-size: 12px;
            }

            .search-form {
                width: 100%;
                max-width: none;
            }

            .result-header {
                padding: 17px 16px;
                border-radius: 16px;
                margin-bottom: 18px;
            }

            .result-title {
                font-size: 17px;
            }

            .result-message {
                font-size: 11px;
            }

            .movie-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }

            .movie-card {
                padding: 8px;
                border-radius: 15px;
            }

            .movie-poster {
                border-radius: 10px;
                margin-bottom: 8px;
            }

            .movie-title {
                font-size: 12px;
            }

            .movie-meta {
                font-size: 9px;
            }

        }
    </style>


    <div class="search-page">

        <!-- =====================================================
             1. ページ上部
        ====================================================== -->

        <div class="search-top">

            <!-- ホームへ戻る -->

            <a href="{{ route('home') }}" class="back-home">
                <span>&larr;</span>
                <span>ホームへ戻る</span>
            </a>


            <!-- 再検索 -->

            <form action="{{ route('movies.search') }}" method="GET" class="search-form">

                <input type="text" name="query" value="{{ $currentQuery }}" placeholder="キーワードで再検索..."
                    class="search-input" required>

                <button type="submit" class="search-submit">
                    検索
                </button>

            </form>

        </div>


        <!-- =====================================================
             2. 結果タイトル
        ====================================================== -->

        <div class="result-header">

            @if($isMoodSearch)

                <!-- 感情検索の場合 -->

                <span class="result-label">
                    MOOD MOVIE PICKS
                </span>

                <h1 class="result-title">
                    {{ $selectedMood['emoji'] }}
                    「{{ $selectedMood['title'] }}」のおすすめ6選
                </h1>

                <p class="result-message">
                    {{ $selectedMood['message'] }}
                </p>

                <div class="mood-result-tag">
                    <span>{{ $selectedMood['emoji'] }}</span>
                    <span>{{ $selectedMood['tag'] }}</span>
                </div>

            @else

                <!-- キーワード検索の場合 -->

                <span class="result-label">
                    KEYWORD SEARCH RESULT
                </span>

                <h1 class="result-title">
                    🔍 「{{ $currentQuery }}」の検索結果
                </h1>

                <p class="result-message">
                    キーワードに一致する映画を探しました。
                </p>

            @endif

        </div>


        <!-- =====================================================
             3. 映画件数
        ====================================================== -->

        @if($movieCount > 0)

            <div class="result-count">

                @if($isMoodSearch)

                    {{ $movieCount }}作品を表示しています

                @else

                    {{ $movieCount }}作品が見つかりました

                @endif

            </div>

        @endif


        <!-- =====================================================
             4. 映画一覧
        ====================================================== -->

        <div class="movie-grid">

            @forelse($movieList as $movie)

                <a href="{{ route('movies.show', $movie['id']) }}" class="movie-card group">

                    <!-- ポスター -->

                    <div class="movie-poster">

                        @if(!empty($movie['poster_path']))

                            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                                alt="{{ $movie['title'] ?? '映画ポスター' }}" loading="lazy">

                        @else

                            <div class="w-full h-full flex items-center justify-center bg-[#0b0e15] text-gray-600 text-2xl">
                                🎬
                            </div>

                        @endif

                    </div>


                    <!-- 映画情報 -->

                    <div class="movie-info">

                        <h3 class="movie-title">
                            {{ $movie['title'] ?? 'タイトル不明' }}
                        </h3>

                        <div class="movie-meta">

                            <span class="movie-year">

                                @if(!empty($movie['release_date']))

                                    {{ substr($movie['release_date'], 0, 4) }}年

                                @else

                                    ----年

                                @endif

                            </span>


                            @if(isset($movie['vote_average']))

                                <span class="movie-score">
                                    ⭐ {{ number_format((float) $movie['vote_average'], 1) }}
                                </span>

                            @endif

                        </div>

                    </div>

                </a>

            @empty

                <!-- =================================================
                         結果なし
                    ================================================== -->

                <div class="empty-result">

                    <div class="empty-icon">
                        🎬
                    </div>

                    @if($isMoodSearch)

                        <p>
                            現在、「{{ $selectedMood['title'] }}」に
                            ぴったりの映画が見つかりませんでした。
                        </p>

                    @else

                        <p>
                            「{{ $currentQuery }}」に一致する映画が見つかりませんでした。
                        </p>

                    @endif

                </div>

            @endforelse

        </div>

    </div>

</x-app-layout>