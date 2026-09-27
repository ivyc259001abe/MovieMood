<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ログイン・新規登録・プロフィール編集画面に自動で $popularMovies を渡す
        View::composer([
            'login',
            'register',
            'auth.login',
            'auth.register',
            'profile.edit'
        ], function ($view) {
            // TMDB APIの応答結果を60分間キャッシュしてアプリを高速化
            $popularMovies = Cache::remember('popular_movies_cache', 3600, function () {
                $apiKey = config('services.tmdb.api_key') ?? env('TMDB_API_KEY');
                $response = Http::get("https://api.themoviedb.org/3/movie/popular", [
                    'api_key' => $apiKey,
                    'language' => 'ja-JP',
                ]);
                return $response->json()['results'] ?? [];
            });

            $view->with('popularMovies', $popularMovies);
        });
    }
}