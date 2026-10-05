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
        // 自身の getPopularMovies() メソッドから人気の映画を取得
        $popularMovies = $this->getPopularMovies();

        // --- 🌙 TODAY'S PICKUP の日替わり計算処理 ---
        $moodThemes = [
            [
                'tag' => '#ハラハラ',
                'mood' => 'ハラハラ',
                'emoji' => '😱',
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
                'bg' => 'bg-pink-500/10 border-pink-500/30 text-pink-400 hover:bg-pink-500/20',
                'messages' => [
                    'ときめきと癒やしをチャージ',
                    '甘酸っぱい気持ちに浸りたい日に',
                    '胸がキュンとする最高のロマンス',
                ]
            ],
        ];

        // 今日の日付を基準にする（例: 20261005）
        $daySeed = (int) date('Ymd');

        // 日替わりで感情テーマを選択
        $themeIndex = $daySeed % count($moodThemes);
        $selectedTheme = $moodThemes[$themeIndex];

        // 日替わりでキャッチコピーを選択
        $msgCount = count($selectedTheme['messages']);
        $selectedMessage = $selectedTheme['messages'][$daySeed % $msgCount];

        // 日替わりで映画をピックアップ（日付シードで人気映画リストから抽出）
        if (!empty($popularMovies)) {
            $movieIndex = $daySeed % count($popularMovies);
            $pickup = $popularMovies[$movieIndex];
        } else {
            $pickup = null;
        }

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
     * 検索＆Mood絞り込み処理（ランダム6作品抽出対応）
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $mood = $request->input('mood');

        $movies = [];

        // 1. キーワード検索
        if ($query) {
            $data = $this->fetchFromTmdb('/search/movie', ['query' => $query, 'page' => 1]);
            $movies = array_slice($data['results'] ?? [], 0, 6);
        }
        // 2. 感情（Mood）タグ検索（ランダム1～10ページから抽出して6件に限定）
        elseif ($mood) {
            $genreMap = [
                '号泣' => 18,    // Drama
                'スカッと' => 28,  // Action
                'ハラハラ' => 53,  // Thriller
                'キュン' => 10749, // Romance
            ];

            $genreId = $genreMap[$mood] ?? null;

            if ($genreId) {
                $randomPage = rand(1, 10);
                $data = $this->fetchFromTmdb('/discover/movie', [
                    'with_genres' => $genreId,
                    'sort_by' => 'popularity.desc',
                    'page' => $randomPage,
                ]);

                $allFetched = $data['results'] ?? [];
                // シャッフルしてランダムに6件を取得
                shuffle($allFetched);
                $movies = array_slice($allFetched, 0, 6);
            } else {
                $movies = array_slice($this->getPopularMovies(), 0, 6);
            }
        } else {
            $movies = array_slice($this->getPopularMovies(), 0, 6);
        }

        $popularMovies = $this->getPopularMovies();

        return view('result', compact('movies', 'query', 'mood', 'popularMovies'));
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