<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Review;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    /**
     * TMDB APIの共通リクエスト処理
     */
    private function fetchFromTmdb(string $url, array $params = [])
    {
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));
        $params['api_key'] = $apiKey;
        $params['language'] = $params['language'] ?? 'ja-JP';

        try {
            $response = Http::withoutVerifying()
                ->retry(3, 100)
                ->timeout(10)
                ->get("https://api.themoviedb.org/3{$url}", $params);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            \Log::error("TMDB API Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * 人気の映画を取得
     */
    private function getPopularMovies()
    {
        $data = $this->fetchFromTmdb('/movie/popular', ['page' => 1]);
        return $data['results'] ?? [];
    }

    public function home()
    {
        // =========================================================
        // 🌙 TODAY'S PICKUP
        // 「今日のMood」と「今日の映画」を連動させる
        // =========================================================

        $moodThemes = [
            [
                'tag' => '#ハラハラ',
                'mood' => 'ハラハラ',
                'emoji' => '😱',
                'genre_id' => 53, // Thriller
                'bg' => 'bg-red-500/10 border-red-500/30 text-red-400 hover:bg-red-500/20',
                'messages' => [
                    'スリルを味わおう',
                    '極上の緊張感を体験したい日に',
                    '最後まで目が離せない展開！',
                ]
            ],
            [
                'tag' => '#スカッと',
                'mood' => 'スカッと',
                'emoji' => '😆',
                'genre_id' => 28, // Action
                'bg' => 'bg-blue-500/10 border-blue-500/30 text-blue-400 hover:bg-blue-500/20',
                'messages' => [
                    'モヤモヤを吹き飛ばそう！',
                    '気分爽快！ストレス解消に',
                    '思わず叫びたくなる最高の展開',
                ]
            ],
            [
                'tag' => '#号泣',
                'mood' => '号泣',
                'emoji' => '😭',
                'genre_id' => 18, // Drama
                'bg' => 'bg-cyan-500/10 border-cyan-500/30 text-cyan-400 hover:bg-cyan-500/20',
                'messages' => [
                    '涙で心をデトックスしたい夜へ',
                    '心揺さぶられる感動のストーリー',
                    'タオル必須！深すぎる愛の物語',
                ]
            ],
            [
                'tag' => '#キュン',
                'mood' => 'キュン',
                'emoji' => '💖',
                'genre_id' => 10749, // Romance
                'bg' => 'bg-pink-500/10 border-pink-500/30 text-pink-400 hover:bg-pink-500/20',
                'messages' => [
                    'ときめきと癒やしをチャージ',
                    '甘酸っぱい気持ちに浸りたい日に',
                    '胸がキュンとする最高のロマンス',
                ]
            ],
        ];


        // =========================================================
        // 📅 今日の日付から固定値を作成
        //
        // 同じ日なら同じ値になるため、
        // ページを何度リロードしても同じMoodになる
        // =========================================================

        $todayStr = now()->format('Y-m-d');

        $dailyHash = hexdec(
            substr(md5($todayStr), 0, 8)
        );


        // =========================================================
        // 🎭 今日のMoodを決定
        // =========================================================

        $themeIndex = $dailyHash % count($moodThemes);

        $selectedTheme = $moodThemes[$themeIndex];


        // =========================================================
        // 💬 今日のキャッチコピーを決定
        // =========================================================

        $msgCount = count($selectedTheme['messages']);

        $messageIndex = $dailyHash % $msgCount;

        $selectedMessage = $selectedTheme['messages'][$messageIndex];


        // =========================================================
        // 🎬 今日のMoodに合った映画をTMDBから取得
        //
        // 例：
        // #キュン → Romance
        // #ハラハラ → Thriller
        // #スカッと → Action
        // #号泣 → Drama
        //
        // TMDBのDiscover APIでジャンルを指定する
        // =========================================================

        $pickupData = $this->fetchFromTmdb('/discover/movie', [
            'with_genres' => $selectedTheme['genre_id'],
            'sort_by' => 'popularity.desc',
            'page' => 1,
            'include_adult' => false,
            'include_video' => false,
        ]);


        $pickupMovies = $pickupData['results'] ?? [];


        // =========================================================
        // 🎯 今日のおすすめ映画を1作品固定
        //
        // 同じ日なら同じ映画になるように、
        // 日付から作ったdailyHashを利用する
        // =========================================================

        if (!empty($pickupMovies)) {

            // 映画ID順に並べることで、
            // TMDBの人気順の変化による影響をできるだけ抑える
            usort($pickupMovies, function ($a, $b) {
                return ($a['id'] ?? 0) <=> ($b['id'] ?? 0);
            });


            // 今日の日付から1作品を選択
            $movieCount = count($pickupMovies);

            $movieIndex = $dailyHash % $movieCount;

            $pickup = $pickupMovies[$movieIndex];

        } else {

            $pickup = null;
        }


        // =========================================================
        // ⭐ HOME下部のPOPULAR MOVIES用
        //
        // こちらは今まで通り人気映画を取得
        // =========================================================

        $popularMovies = $this->getPopularMovies();


        // =========================================================
        // HOME画面へ渡す
        // =========================================================

        return view('home', compact(
            'popularMovies',
            'selectedTheme',
            'selectedMessage',
            'pickup'
        ));
    }
    /**
     * オートコンプリートAPI（検索窓入力時にリアルタイムで候補5件を返す）
     */
    public function autocomplete(Request $request)
    {
        $query = $request->input('query');
        if (!$query) {
            return response()->json([]);
        }

        $data = $this->fetchFromTmdb('/search/movie', ['query' => $query, 'page' => 1]);
        $results = array_slice($data['results'] ?? [], 0, 5);

        $suggestions = array_map(function ($movie) {
            return [
                'id' => $movie['id'],
                'title' => $movie['title'] ?? 'タイトル不明',
                'release_year' => isset($movie['release_date']) && !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : '',
                'poster_path' => $movie['poster_path'] ?? null
            ];
        }, $results);

        return response()->json($suggestions);
    }

    /**
     * 検索＆Mood絞り込み処理
     *
     * ・キーワード検索 → 最大6作品
     * ・Mood検索 → 選択した感情に合う映画を6作品
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $mood = $request->input('mood');

        $movies = [];

        // =========================================================
        // 🔍 1. キーワード検索
        // =========================================================

        if ($query) {

            $data = $this->fetchFromTmdb('/search/movie', [
                'query' => $query,
                'page' => 1,
            ]);

            // 検索結果から最大6作品
            $movies = array_slice(
                $data['results'] ?? [],
                0,
                6
            );
        }

        // =========================================================
        // 🎭 2. Mood検索
        //
        // HOMEの4つの感情
        //
        // 😭 号泣     → Drama
        // 😆 スカッと → Action
        // 😱 ハラハラ → Thriller
        // 💖 キュン   → Romance
        // =========================================================
        elseif ($mood) {

            $genreMap = [
                '号泣' => 18,
                'スカッと' => 28,
                'ハラハラ' => 53,
                'キュン' => 10749,
            ];

            $genreId = $genreMap[$mood] ?? null;

            // =====================================================
            // Moodが正しい場合
            // =====================================================

            if ($genreId) {

                /*
                 * TMDBの1～10ページからランダムに1ページ取得。
                 * 同じMoodでもアクセスするたびに
                 * 少し違う作品が表示されるようにする。
                 */
                $randomPage = rand(1, 10);

                $data = $this->fetchFromTmdb('/discover/movie', [
                    'with_genres' => $genreId,
                    'sort_by' => 'popularity.desc',
                    'page' => $randomPage,
                    'include_adult' => false,
                    'include_video' => false,
                ]);

                $allFetched = $data['results'] ?? [];

                /*
                 * 取得した作品をシャッフル。
                 */
                shuffle($allFetched);

                /*
                 * その中から最大6作品を表示。
                 */
                $movies = array_slice(
                    $allFetched,
                    0,
                    6
                );
            }

            // =====================================================
            // 不正なMoodの場合
            // =====================================================
            else {

                $movies = array_slice(
                    $this->getPopularMovies(),
                    0,
                    6
                );
            }
        }

        // =========================================================
        // ⭐ 3. 検索条件がない場合
        // =========================================================
        else {

            $movies = array_slice(
                $this->getPopularMovies(),
                0,
                6
            );
        }

        // =========================================================
        // ⭐ 共通POPULAR MOVIES
        //
        // app.blade.phpの下部で使用
        // =========================================================

        $popularMovies = $this->getPopularMovies();

        // =========================================================
        // 📺 検索結果画面へ
        // =========================================================

        return view('result', compact(
            'movies',
            'query',
            'mood',
            'popularMovies'
        ));
    }
    /**
     * 映画詳細画面を表示
     */
    public function show($id)
    {
        $movieData = $this->fetchFromTmdb("/movie/{$id}");

        if (!$movieData) {
            abort(404, '映画情報が見つかりませんでした。');
        }

        $director = '不明';
        $credits = $this->fetchFromTmdb("/movie/{$id}/credits");
        if ($credits && isset($credits['crew'])) {
            $directorObj = collect($credits['crew'])->firstWhere('job', 'Director');
            if ($directorObj && !empty($directorObj['name'])) {
                $director = $directorObj['name'];
            }
        }

        $overview = (!empty($movieData['overview']) && trim($movieData['overview']) !== '')
            ? $movieData['overview']
            : '※日本語あらすじ情報は準備中です。';

        $movie = [
            'id' => $movieData['id'],
            'title' => $movieData['title'] ?? 'タイトル不明',
            'poster_path' => $movieData['poster_path'] ?? null,
            'release_date' => isset($movieData['release_date']) ? date('Y年n月', strtotime($movieData['release_date'])) : '不明',
            'runtime' => $movieData['runtime'] ?? 0,
            'director' => $director,
            'overview' => $overview,
            'genres' => $movieData['genres'] ?? [],
            'vote_average' => $movieData['vote_average'] ?? 0,
        ];

        $popularMovies = $this->getPopularMovies();

        $reviews = Review::with('user', 'likes', 'comments.user')
            ->where('movie_id', $id)
            ->latest()
            ->get();

        // ログイン中ユーザーのWatchlistに入っているmovie_id一覧を取得
        $watchlistMovieIds = [];
        if (Auth::check()) {
            $watchlistMovieIds = Watchlist::where('user_id', Auth::id())
                ->pluck('movie_id')
                ->toArray();
        }

        return view('movies.show', compact('movie', 'reviews', 'popularMovies', 'watchlistMovieIds'));
    }
}