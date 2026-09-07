<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    // 映画一覧画面
    public function index()
    {
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));
        $response = Http::get("https://api.themoviedb.org/3/movie/popular", [
            'api_key' => $apiKey,
            'language' => 'ja-JP',
        ]);

        // ビューで参照している変数名 $movies に合わせます
        $movies = $response->successful() ? $response->json()['results'] : [];

        return view('movies.index', compact('movies'));
    }

    // 映画詳細画面
    public function show($id)
    {
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));
        $response = Http::get("https://api.themoviedb.org/3/movie/{$id}", [
            'api_key' => $apiKey,
            'language' => 'ja-JP',
        ]);

        if ($response->successful()) {
            $movie = $response->json();
            if (empty($movie['overview'])) {
                $movie['overview'] = '※この作品の日本語概要は現在登録されていません。';
            }
            return view('movies.show', compact('movie'));
        }

        return redirect()->route('movies.index');
    }
}