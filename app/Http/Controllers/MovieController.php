<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Review;

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

    /**
     * ホーム画面の表示
     */
    public function home()
    {
        $popularMovies = $this->getPopularMovies();

        // 💡 本日のトピック：日付ベースのシード値で毎日ランダムに1作品を選出
        $todayTopic = null;
        if (!empty($popularMovies)) {
            $daySeed = (int) date('Ymd');
            $topicIndex = $daySeed % count($popularMovies);
            $todayTopic = $popularMovies[$topicIndex];

            // トピック映画の監督情報を補填
            if (isset($todayTopic['id'])) {
                $credits = $this->fetchFromTmdb("/movie/{$todayTopic['id']}/credits");
                if ($credits && isset($credits['crew'])) {
                    $directorObj = collect($credits['crew'])->firstWhere('job', 'Director');
                    $todayTopic['director'] = $directorObj['name'] ?? '不明';
                }
            }
        }

        return view('home', compact('popularMovies', 'todayTopic'));
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

        return view('movies.show', compact('movie', 'reviews', 'popularMovies'));
    }
}