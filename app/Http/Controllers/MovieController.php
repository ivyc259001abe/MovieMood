<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Review;

class MovieController extends Controller
{
    /**
     * TMDB APIの共通リクエスト処理（SSLエラー対策・自動リトライ・タイムアウト設定）
     */
    private function fetchFromTmdb(string $url, array $params = [])
    {
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));
        $params['api_key'] = $apiKey;
        $params['language'] = $params['language'] ?? 'ja-JP';

        try {
            // 💡 withoutVerifying() でcURL 35エラー回避、retry(3, 100) で一時的通信失敗を自動リカバリー
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
     * 映画詳細画面を表示
     */
    public function show($id)
    {
        // 映画情報を取得
        $movieData = $this->fetchFromTmdb("/movie/{$id}");

        if (!$movieData) {
            abort(404, '映画情報が見つかりませんでした。');
        }

        // 監督情報を追加で取得（credits endpoint）
        $director = '不明';
        $credits = $this->fetchFromTmdb("/movie/{$id}/credits");
        if ($credits && isset($credits['crew'])) {
            $directorObj = collect($credits['crew'])->firstWhere('job', 'Director');
            if ($directorObj && !empty($directorObj['name'])) {
                $director = $directorObj['name'];
            }
        }

        // 💡 overview が空または空白のみの場合はメッセージを自動補填
        $overview = (!empty($movieData['overview']) && trim($movieData['overview']) !== '')
            ? $movieData['overview']
            : '※日本語あらすじ情報は準備中です。';

        // Viewへ渡す映画情報配列を作成
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

        // カルバナー等で使用する人気映画リスト
        $popularMovies = $this->getPopularMovies();

        // レビュー一覧の取得
        $reviews = Review::with('user', 'likes', 'comments.user')
            ->where('movie_id', $id)
            ->latest()
            ->get();

        return view('movies.show', compact('movie', 'reviews', 'popularMovies'));
    }
}