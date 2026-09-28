<x-app-layout>
    <div style="background-color: #000000; color: #ffffff; min-height: 100vh; padding: 24px 16px; box-sizing: border-box;">
        <div style="max-width: 1100px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">

            <!-- 1. ヘッダーバナー -->
            <div style="background-color: #0d1117; border: 1px solid #1f2937; border-radius: 12px; padding: 20px 24px; display: flex; align-items: center; gap: 16px;">
                <div style="background-color: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 12px; padding: 12px; font-size: 24px;">
                    💬
                </div>
                <div>
                    <h1 style="font-size: 20px; font-weight: 800; color: #ffffff; margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px;">
                        みんなの <span style="color: #f59e0b;">感情タイムライン</span>
                    </h1>
                    <p style="font-size: 12px; color: #9ca3af; margin: 0;">
                        映画を観たあとの「生の感情」が集まる場所。今の気分にぴったりの映画を見つけよう！
                    </p>
                </div>
            </div>

            <!-- メインコンテンツ（左右2カラム） -->
            <div style="display: grid; grid-template-columns: 1fr 320px; gap: 24px; align-items: start;">

                <!-- 左側：投稿フォーム ＆ レビュー一覧 -->
                <div style="display: flex; flex-direction: column; gap: 24px;">

                    <!-- 投稿カード -->
                    <div style="background-color: #0d1117; border: 1px solid #1f2937; border-radius: 12px; padding: 20px; box-sizing: border-box;">
                        <h2 style="font-size: 14px; font-weight: bold; color: #f59e0b; margin: 0 0 16px 0; display: flex; align-items: center; gap: 6px;">
                            📝 観た映画の「今の感情」を投稿する
                        </h2>

                        @if (session('success'))
                            <div style="background-color: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; font-size: 12px; padding: 10px; border-radius: 8px; margin-bottom: 16px;">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('community.store') }}" style="display: flex; flex-direction: column; gap: 16px; margin: 0;">
                            @csrf

                            <!-- 映画タイトル -->
                            <div style="position: relative;">
                                <label for="movie_title" style="display: block; font-size: 11px; font-weight: bold; color: #e5e7eb; margin-bottom: 6px;">
                                    映画タイトル <span style="color: #f59e0b; font-size: 10px; font-weight: normal;">(入力すると候補が自動取得されます)</span>
                                </label>
                                <input id="movie_title" type="text" name="movie_title" value="{{ old('movie_title') }}" required placeholder="例: アベンジャーズ / エンドゲーム" autocomplete="off"
                                    style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 8px; padding: 10px 12px; font-size: 12px; outline: none; box-sizing: border-box;"
                                    onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">
                                
                                <div id="movie_suggestions" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background-color: #111827; border: 1px solid #374151; border-radius: 8px; margin-top: 4px; max-height: 200px; overflow-y: auto; z-index: 100; box-shadow: 0 10px 25px rgba(0,0,0,0.8);"></div>
                            </div>

                            <!-- 2枚目の画像に合わせたスライダー評価UI -->
                            <div style="background-color: #000000; border: 1px solid #1f2937; border-radius: 8px; padding: 14px 16px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <label style="font-size: 11px; font-weight: bold; color: #e5e7eb; display: flex; align-items: center; gap: 4px;">
                                        ⭐ 評価 <span style="font-size: 10px; color: #9ca3af; font-weight: normal;">(1〜10段階)</span>
                                    </label>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span id="rating_label" style="font-size: 10px; color: #f59e0b; font-weight: bold;">かなりおすすめ</span>
                                        <div style="background-color: #111827; border: 1px solid #374151; border-radius: 6px; padding: 2px 8px; font-size: 12px; font-weight: 800; color: #fbbf24; display: flex; align-items: center; gap: 4px;">
                                            ★ <span id="rating_value">7</span>
                                        </div>
                                    </div>
                                </div>
                                <input type="range" id="rating_range" min="1" max="10" step="1" value="{{ old('rating', 7) }}"
                                    style="width: 100%; accent-color: #f59e0b; cursor: pointer; height: 6px; background-color: #374151; border-radius: 9999px; outline: none;">
                                <input type="hidden" name="rating" id="rating_input" value="{{ old('rating', 7) }}">
                            </div>

                            <!-- 今のあなたの気分は？ (複数選択可能ボタン) -->
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: bold; color: #e5e7eb; margin-bottom: 8px;">
                                    現在の「気分」は？ <span style="font-size: 10px; color: #9ca3af; font-weight: normal;">(複数選択可)</span>
                                </label>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                    @php
                                        $moodOptions = [
                                            ['label' => '#号泣', 'emoji' => '😭'],
                                            ['label' => '#スカッと', 'emoji' => '😆'],
                                            ['label' => '#ハラハラ', 'emoji' => '😱'],
                                            ['label' => '#キュン', 'emoji' => '💖'],
                                        ];
                                    @endphp
                                    @foreach($moodOptions as$opt)
                                        <label class="mood-checkbox-label" style="display: flex; align-items: center; justify-content: center; gap: 6px; background-color: #000000; border: 1px solid #374151; border-radius: 8px; padding: 8px 12px; font-size: 11px; color: #e5e7eb; cursor: pointer; transition: all 0.2s; user-select: none;">
                                            <input type="checkbox" name="moods[]" value="{{ $opt['label'] }}" style="display: none;" onchange="toggleMoodStyle(this)">
                                            <span>{{ $opt['emoji'] }}</span>
                                            <span>{{ $opt['label'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- 感想・レビュー本文 -->
                            <div>
                                <label for="comment" style="display: block; font-size: 11px; font-weight: bold; color: #e5e7eb; margin-bottom: 6px;">
                                    💬 感想・レビュー本文
                                </label>
                                <textarea id="comment" name="comment" rows="4" required placeholder="この作品の見どころや感じたことを自由に書いてみよう..."
                                    style="width: 100%; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 8px; padding: 10px 12px; font-size: 12px; outline: none; box-sizing: border-box; resize: vertical;"
                                    onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#374151'">{{ old('comment') }}</textarea>
                            </div>

                            <button type="submit" style="width: 100%; background-color: #f59e0b; color: #000000; font-weight: 800; padding: 10px; border-radius: 9999px; font-size: 12px; border: none; cursor: pointer; transition: background-color 0.2s; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.25); display: flex; align-items: center; justify-content: center; gap: 6px;"
                                onmouseover="this.style.backgroundColor='#fbbf24'" onmouseout="this.style.backgroundColor='#f59e0b'">
                                ✈️ 投稿する
                            </button>
                        </form>
                    </div>

                    <!-- 絞り込みフィルター＆レビュータイムライン -->
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                            <span style="font-size: 12px; font-weight: bold; color: #9ca3af;">気分で絞り込み:</span>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <a href="{{ route('community.index') }}" style="font-size: 10px; font-weight: bold; padding: 4px 10px; border-radius: 9999px; text-decoration: none; background-color: #f59e0b; color: #000000;">すべて</a>
                                @foreach(['#号泣' => '😭', '#スカッと' => '😆', '#ハラハラ' => '😱', '#キュン' => '💖'] as $mood =>$emoji)
                                    <a href="{{ route('community.index', ['mood' => $mood]) }}" style="font-size: 10px; font-weight: bold; padding: 4px 10px; border-radius: 9999px; text-decoration: none; background-color: #111827; border: 1px solid #374151; color: #d1d5db;">
                                        {{ $emoji }} {{$mood }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- タイムライン一覧 -->
                        @if(!empty($reviews) && count($reviews) > 0)
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                @foreach($reviews as$review)
                                    <div style="background-color: #0d1117; border: 1px solid #1f2937; border-radius: 12px; padding: 16px; box-sizing: border-box;">
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                            <div>
                                                <span style="font-size: 13px; font-weight: 800; color: #ffffff;">{{ $review->movie_title }}</span>
                                                <div style="font-size: 10px; color: #6b7280; margin-top: 2px;">
                                                    {{ $review->user->name ?? '匿名ユーザー' }} • {{ $review->created_at ? $review->created_at->diffForHumans() : '' }}
                                                </div>
                                            </div>
                                            <div style="background-color: #111827; border: 1px solid rgba(245, 158, 11, 0.5); border-radius: 6px; padding: 2px 8px; font-size: 11px; font-weight: 800; color: #fbbf24;">
                                                ★ {{ number_format((float)$review->rating, 1) }}
                                            </div>
                                        </div>

                                        @if(!empty($review->mood))
                                            <div style="margin-bottom: 8px; display: flex; gap: 4px; flex-wrap: wrap;">
                                                @foreach(explode(',', $review->mood) as$m)
                                                    <span style="font-size: 9px; background-color: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); color: #f59e0b; padding: 1px 6px; border-radius: 4px;">
                                                        {{ trim($m) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif

                                        <p style="font-size: 11px; color: #e5e7eb; margin: 0; line-height: 1.5; white-space: pre-wrap;">{{ $review->comment }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="background-color: #0d1117; border: 1px dashed #374151; border-radius: 12px; padding: 30px; text-align: center; color: #9ca3af;">
                                <div style="font-size: 24px; margin-bottom: 6px;">💬</div>
                                <p style="font-size: 12px; margin: 0;">最初のレビューを投稿してみましょう！</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 右側：サイドバーエリア -->
                <div style="display: flex; flex-direction: column; gap: 16px;">

                    <!-- みんなが感じている感情 -->
                    <div style="background-color: #0d1117; border: 1px solid #1f2937; border-radius: 12px; padding: 16px;">
                        <h3 style="font-size: 12px; font-weight: bold; color: #f59e0b; margin: 0 0 12px 0; display: flex; align-items: center; gap: 6px;">
                            🔥 今みんなが感じている感情
                        </h3>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                            <span style="font-size: 10px; background-color: #000000; border: 1px solid #374151; color: #ffffff; padding: 4px 10px; border-radius: 9999px;">😭 #号泣</span>
                            <span style="font-size: 10px; background-color: #000000; border: 1px solid #374151; color: #ffffff; padding: 4px 10px; border-radius: 9999px;">😆 #スカッと</span>
                            <span style="font-size: 10px; background-color: #000000; border: 1px solid #374151; color: #ffffff; padding: 4px 10px; border-radius: 9999px;">😱 #ハラハラ</span>
                            <span style="font-size: 10px; background-color: #000000; border: 1px solid #374151; color: #ffffff; padding: 4px 10px; border-radius: 9999px;">💖 #キュン</span>
                        </div>
                    </div>

                    <!-- MovieMood コミュニティの楽しみ方 -->
                    <div style="background-color: #0d1117; border: 1px solid #1f2937; border-radius: 12px; padding: 16px;">
                        <h3 style="font-size: 12px; font-weight: bold; color: #f59e0b; margin: 0 0 8px 0; display: flex; align-items: center; gap: 6px;">
                            💡 MovieMood コミュニティの楽しみ方
                        </h3>
                        <p style="font-size: 10px; color: #9ca3af; line-height: 1.5; margin: 0;">
                            「キュンキュンしたい」「泣いてすっきりしたい」など、その時の気分に合った映画の感想を探せます。感情タグを添えて感想を共有しましょう！
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- JavaScript (スライダーと気分ボタンのインタラクション) -->
    <script>
        // スライダー連動の文字＆数値表示
        const ratingRange = document.getElementById('rating_range');
        const ratingValue = document.getElementById('rating_value');
        const ratingLabel = document.getElementById('rating_label');
        const ratingInput = document.getElementById('rating_input');

        const labels = {
            1: '全然おすすめしない', 2: 'イマイチ', 3: '普通以下', 4: 'まあまあ', 5: '普通',
            6: '良い', 7: 'かなりおすすめ', 8: 'とても面白い', 9: '最高！', 10: '人生の神作'
        };

        if (ratingRange) {
            ratingRange.addEventListener('input', function() {
                const val = this.value;
                ratingValue.textContent = val;
                ratingInput.value = val;
                ratingLabel.textContent = labels[val] || '';
            });
        }

        // 気分ボタン切り替えスタイル
        function toggleMoodStyle(checkbox) {
            const label = checkbox.parentElement;
            if (checkbox.checked) {
                label.style.backgroundColor = 'rgba(245, 158, 11, 0.15)';
                label.style.borderColor = '#f59e0b';
                label.style.color = '#f59e0b';
            } else {
                label.style.backgroundColor = '#000000';
                label.style.borderColor = '#374151';
                label.style.color = '#e5e7eb';
            }
        }

        // 映画タイトル補完処理
        const movieInput = document.getElementById('movie_title');
        const suggestionsBox = document.getElementById('movie_suggestions');

        if (movieInput) {
            movieInput.addEventListener('input', async function() {
                const query = this.value.trim();
                if (query.length < 2) {
                    suggestionsBox.style.display = 'none';
                    return;
                }

                try {
                    const response = await fetch(`/api/movies/search?query=${encodeURIComponent(query)}`);
                    const movies = await response.json();

                    if (movies.length > 0) {
                        suggestionsBox.innerHTML = '';
                        movies.slice(0, 5).forEach(movie => {
                            const div = document.createElement('div');
                            div.style.cssText = 'padding: 8px 12px; font-size: 11px; color: #ffffff; cursor: pointer; border-bottom: 1px solid #1f2937;';
                            div.textContent = movie.title;
                            div.onmouseover = () => div.style.backgroundColor = '#1f2937';
                            div.onmouseout = () => div.style.backgroundColor = 'transparent';
                            div.onclick = () => {
                                movieInput.value = movie.title;
                                suggestionsBox.style.display = 'none';
                            };
                            suggestionsBox.appendChild(div);
                        });
                        suggestionsBox.style.display = 'block';
                    } else {
                        suggestionsBox.style.display = 'none';
                    }
                } catch (e) {
                    suggestionsBox.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>