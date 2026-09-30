<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    private function getPopularMovies()
    {
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));
        $response = Http::get("https://api.themoviedb.org/3/movie/popular", [
            'api_key' => $apiKey,
            'language' => 'ja-JP',
            'page' => 1,
        ]);
        return $response->successful() ? $response->json()['results'] : [];
    }

    private function getMoviesByMood($mood)
    {
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));

        $genreMap = [
            '号泣' => 18,     // ドラマ
            'スカッと' => 28,  // アクション
            'ハラハラ' => 53,  // スリラー
            'キュン' => 10749, // 恋愛
        ];

        $genreId = $genreMap[$mood] ?? 18;

        $response = Http::get("https://api.themoviedb.org/3/discover/movie", [
            'api_key' => $apiKey,
            'language' => 'ja-JP',
            'region' => 'JP',
            'with_genres' => $genreId,
            'sort_by' => 'vote_count.desc',
            'vote_count.gte' => 100,
            'page' => 1,
        ]);

        if ($response->successful()) {
            $results = $response->json()['results'] ?? [];
            $sliced = array_slice($results, 0, 4);

            return array_map(function ($item) {
                $overview = !empty($item['overview']) ? $item['overview'] : '※日本語あらすじ情報は準備中です。';

                return [
                    'id' => $item['id'],
                    'title' => $item['title'] ?? 'タイトル不明',
                    'poster_path' => $item['poster_path'] ?? null,
                    'date' => isset($item['release_date']) ? substr($item['release_date'], 0, 7) : '公開年不明',
                    'overview' => $overview,
                    'vote_average' => $item['vote_average'] ?? 0,
                ];
            }, $sliced);
        }

        return [];
    }

    // 🔍 キーワード検索処理ヘルパー
    private function searchMoviesByQuery($query)
    {
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));

        $response = Http::get("https://api.themoviedb.org/3/search/movie", [
            'api_key' => $apiKey,
            'language' => 'ja-JP',
            'query' => $query,
            'page' => 1,
        ]);

        if ($response->successful()) {
            $results = $response->json()['results'] ?? [];

            return array_map(function ($item) {
                $overview = !empty($item['overview']) ? $item['overview'] : '※日本語あらすじ情報は準備中です。';

                return [
                    'id' => $item['id'],
                    'title' => $item['title'] ?? 'タイトル不明',
                    'poster_path' => $item['poster_path'] ?? null,
                    'date' => isset($item['release_date']) ? substr($item['release_date'], 0, 7) : '公開年不明',
                    'overview' => $overview,
                    'vote_average' => $item['vote_average'] ?? 0,
                ];
            }, $results);
        }

        return [];
    }

    public function home()
    {
        $popularMovies = $this->getPopularMovies();
        $movie = null;

        if (!empty($popularMovies)) {
            $dayOfYear = (int) date('z');
            $pickupIndex = $dayOfYear % count($popularMovies);
            $selected = $popularMovies[$pickupIndex];

            $genreIds = $selected['genre_ids'] ?? [];

            $mood = '#ハラハラ';
            $phrase = 'ドキドキしたい？';

            if (in_array(18, $genreIds)) {
                $mood = '#号泣';
                $phrase = '思いっきり泣きたい？';
            } elseif (in_array(28, $genreIds)) {
                $mood = '#スカッと';
                $phrase = 'スカッとしたい？';
            } elseif (in_array(53, $genreIds) || in_array(27, $genreIds)) {
                $mood = '#ハラハラ';
                $phrase = 'ドキドキしたい？';
            } elseif (in_array(10749, $genreIds)) {
                $mood = '#キュン';
                $phrase = 'キュンキュンしたい？';
            }

            $movie = [
                'id' => $selected['id'] ?? null,
                'title' => $selected['title'] ?? 'タイトル不明',
                'poster_path' => $selected['poster_path'] ?? null,
                'vote_average' => $selected['vote_average'] ?? null,
                'mood' => $mood,
                'phrase' => $phrase,
            ];
        }

        return view('home', [
            'popularMovies' => $popularMovies,
            'movie' => $movie,
        ]);
    }

    // 🔍 検索・診断結果画面用メソッド
    public function search(Request $request)
    {
        $popularMovies = $this->getPopularMovies();
        $query = $request->query('query');

        if ($query) {
            $movies = $this->searchMoviesByQuery($query);

            return view('result', [
                'moodName' => '検索結果: ' . $query,
                'query' => $query,
                'movies' => $movies,
                'popularMovies' => $popularMovies,
            ]);
        }

        $mood = $request->query('mood', '号泣');

        $map = [
            'cry' => '号泣',
            'refresh' => 'スカッと',
            'thrill' => 'ハラハラ',
            'heartbeat' => 'キュン'
        ];

        $targetMood = $map[$mood] ?? $mood;
        $movies = $this->getMoviesByMood($targetMood);

        return view('result', [
            'moodName' => $targetMood,
            'query' => null,
            'movies' => $movies,
            'popularMovies' => $popularMovies,
        ]);
    }

    public function index()
    {
        return $this->search(request());
    }

    public function show($id)
    {
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));
        $popularMovies = $this->getPopularMovies();

        $response = Http::get("https://api.themoviedb.org/3/movie/{$id}", [
            'api_key' => $apiKey,
            'language' => 'ja-JP',
            'append_to_response' => 'credits',
        ]);

        if ($response->failed()) {
            abort(404);
        }

        $movieData = $response->json();

        $director = '不明';
        if (!empty($movieData['credits']['crew'])) {
            foreach ($movieData['credits']['crew'] as $crew) {
                if ($crew['job'] === 'Director') {
                    $director = $crew['name'];
                    break;
                }
            }
        }

        $overview = !empty($movieData['overview']) ? $movieData['overview'] : '※日本語あらすじ情報は準備中です。';

        $movie = [
            'id' => $movieData['id'],
            'title' => $movieData['title'] ?? 'タイトル不明',
            'poster_path' => $movieData['poster_path'] ?? null,
            'release_date' => isset($movieData['release_date']) ? date('Y年n月', strtotime($movieData['release_date'])) : '不明',
            'runtime' => $movieData['runtime'] ?? 0,
            'director' => $director,
            'overview' => $overview,
            'vote_average' => $movieData['vote_average'] ?? 0,
        ];

        // データベースから実際に投稿された対象映画のレビューを取得
        $reviews = Review::with('user')
            ->where('movie_id', $id)
            ->latest()
            ->get();

        return view('movies.show', [
            'movie' => $movie,
            'reviews' => $reviews,
            'popularMovies' => $popularMovies,
        ]);
    }

    // 🤖 JavaScriptの自動補完・オートコンプリート用API
    public function searchApi(Request $request)
    {
        $query = $request->query('query');

        if (!$query) {
            return response()->json([]);
        }

        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));

        $response = Http::get("https://api.themoviedb.org/3/search/movie", [
            'api_key' => $apiKey,
            'language' => 'ja-JP',
            'query' => $query,
            'page' => 1,
        ]);

        if ($response->successful()) {
            $results = $response->json()['results'] ?? [];

            $formatted = array_map(function ($item) {
                return [
                    'id' => $item['id'],
                    'title' => $item['title'] ?? 'タイトル不明',
                    'poster_path' => $item['poster_path'] ?? null,
                    'release_date' => $item['release_date'] ?? '',
                ];
            }, array_slice($results, 0, 5));

            return response()->json($formatted);
        }

        return response()->json([]);
    }
}