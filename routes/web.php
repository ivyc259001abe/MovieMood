<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\MovieController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\ReviewController;

// =========================================================
// TMDb人気映画取得ヘルパー関数
// =========================================================
if (!function_exists('getPopularMovies')) {
    function getPopularMovies()
    {
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));

        try {
            $response = Http::timeout(10)->get(
                'https://api.themoviedb.org/3/movie/popular',
                [
                    'api_key' => $apiKey,
                    'language' => 'ja-JP',
                    'page' => 1,
                ]
            );

            return $response->successful()
                ? ($response->json()['results'] ?? [])
                : [];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return [];
        }
    }
}

// =========================================================
// トップページ
// =========================================================
Route::get('/', function () {

    if (Auth::check()) {
        return redirect()->route('home');
    }

    $popularMovies = getPopularMovies();

    return view('login', compact('popularMovies'));
});

// =========================================================
// ログイン画面
// =========================================================
Route::get('/login', function () {

    if (Auth::check()) {
        return redirect()->route('home');
    }

    $popularMovies = getPopularMovies();

    return view('login', compact('popularMovies'));

})->name('login');

// ログイン処理
Route::post('/', [
    AuthenticatedSessionController::class,
    'store',
]);

Route::post('/login', [
    AuthenticatedSessionController::class,
    'store',
]);

// =========================================================
// 新規登録・ログアウト
// =========================================================
Route::get('/register', [
    RegisteredUserController::class,
    'create',
])->name('register');

Route::post('/register', [
    RegisteredUserController::class,
    'store',
]);

Route::post('/logout', [
    AuthenticatedSessionController::class,
    'destroy',
])->name('logout');

// =========================================================
// パスワード再設定
// =========================================================
Route::get('/password/reset', function () {

    $popularMovies = getPopularMovies();

    return view('password_reset', compact('popularMovies'));

})->name('password.request');

Route::post('/password/reset', [
    PasswordController::class,
    'resetPassword',
])->name('password.reset.update');

// =========================================================
// コミュニティ表示・投稿
// =========================================================
Route::get('/community', [
    ReviewController::class,
    'community',
])->name('community.index');

Route::post('/community', [
    ReviewController::class,
    'store',
])->name('community.store')->middleware('auth');

// =========================================================
// ログインユーザー専用機能
// =========================================================
Route::middleware('auth')->group(function () {

    // -----------------------------------------------------
    // ホーム画面
    // -----------------------------------------------------
    Route::get('/home', [
        MovieController::class,
        'home',
    ])->name('home');

    // -----------------------------------------------------
    // 天気専用ページ
    // -----------------------------------------------------
    Route::get('/weather-page', function () {
        return view('weather');
    })->name('weather.page');


    // -----------------------------------------------------
    // 映画検索・Mood絞り込み
    // -----------------------------------------------------
    Route::get('/movies/search', [
        MovieController::class,
        'search',
    ])->name('movies.search');

    Route::get('/result', [
        MovieController::class,
        'search',
    ])->name('result');

    // =====================================================
    // 天気関連API
    // =====================================================

    // 都市名の予測変換候補を取得
    Route::get('/weather/cities', function (Request $request) {

        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ]);

        $apiKey = config('services.openweathermap.key');

        if (empty($apiKey)) {
            return response()->json([
                'message' => '天気APIの設定がありません。.envとconfig/services.phpを確認してください。',
            ], 500);
        }

        try {

            $response = Http::timeout(10)->get(
                'https://api.openweathermap.org/geo/1.0/direct',
                [
                    'q' => $validated['q'],
                    'limit' => 5,
                    'appid' => $apiKey,
                ]
            );

            if (!$response->successful()) {
                return response()->json([
                    'message' => '都市候補を取得できませんでした。APIキーや利用状況をご確認ください。',
                ], 422);
            }

            $cities = collect($response->json() ?? [])
                ->map(function ($city) {

                    $localNames = $city['local_names'] ?? [];

                    return [
                        'name' => $localNames['ja']
                            ?? $city['name']
                            ?? '',
                        'state' => $city['state'] ?? '',
                        'country' => $city['country'] ?? '',
                        'lat' => $city['lat'] ?? null,
                        'lon' => $city['lon'] ?? null,
                    ];
                })
                ->filter(function ($city) {

                    return $city['name'] !== ''
                        && is_numeric($city['lat'])
                        && is_numeric($city['lon']);
                })
                ->unique(function ($city) {

                    return $city['name']
                        . '-' . $city['country']
                        . '-' . $city['lat']
                        . '-' . $city['lon'];
                })
                ->values();

            return response()->json($cities);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {

            return response()->json([
                'message' => '都市候補サービスに接続できませんでした。時間をおいて再度お試しください。',
            ], 503);
        }

    })->name('weather.cities');

    // -----------------------------------------------------
    // 天気情報を取得
    // -----------------------------------------------------
    Route::get('/weather', function (Request $request) {

        $validated = $request->validate([
            'city' => ['nullable', 'string', 'max:100'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lon' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $apiKey = config('services.openweathermap.key');

        if (empty($apiKey)) {
            return response()->json([
                'message' => '天気APIの設定がありません。.envとconfig/services.phpを確認してください。',
            ], 500);
        }

        $lat = $validated['lat'] ?? null;
        $lon = $validated['lon'] ?? null;
        $requestedCity = trim($validated['city'] ?? '');

        try {

            // 都市名で検索された場合は、緯度・経度を取得
            if ($requestedCity !== '' && ($lat === null || $lon === null)) {

                $geoResponse = Http::timeout(10)->get(
                    'https://api.openweathermap.org/geo/1.0/direct',
                    [
                        'q' => $requestedCity,
                        'limit' => 1,
                        'appid' => $apiKey,
                    ]
                );

                if (!$geoResponse->successful()) {
                    return response()->json([
                        'message' => '地域を検索できませんでした。時間をおいて再度お試しください。',
                    ], 422);
                }

                $locations = $geoResponse->json() ?? [];

                if (empty($locations)) {
                    return response()->json([
                        'message' => '該当する地域が見つかりませんでした。都市名を変更してお試しください。',
                    ], 404);
                }

                $location = $locations[0];

                $lat = $location['lat'] ?? null;
                $lon = $location['lon'] ?? null;
            }

            if ($lat === null || $lon === null) {
                return response()->json([
                    'message' => '現在地を取得するか、都市名を入力してください。',
                ], 422);
            }

            // 緯度・経度から天気を取得
            $response = Http::timeout(10)->get(
                'https://api.openweathermap.org/data/2.5/weather',
                [
                    'lat' => $lat,
                    'lon' => $lon,
                    'appid' => $apiKey,
                    'units' => 'metric',
                    'lang' => 'ja',
                ]
            );

            if (!$response->successful()) {
                return response()->json([
                    'message' => '天気情報を取得できませんでした。APIキーや利用状況をご確認ください。',
                ], 422);
            }

            $data = $response->json();

            return response()->json([
                'city' => $data['name'] ?? $requestedCity,
                'country' => $data['sys']['country'] ?? '',
                'temperature' => $data['main']['temp'] ?? null,
                'feels_like' => $data['main']['feels_like'] ?? null,
                'humidity' => $data['main']['humidity'] ?? null,
                'description' => $data['weather'][0]['description'] ?? '',

                // 映画の気分提案に使用する天気分類
                'weather_main' => $data['weather'][0]['main'] ?? '',

                'icon' => $data['weather'][0]['icon'] ?? '',
                'wind_speed' => $data['wind']['speed'] ?? null,
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {

            return response()->json([
                'message' => '天気サービスに接続できませんでした。時間をおいて再度お試しください。',
            ], 503);
        }

    })->name('weather.show');

    // =====================================================
    // 映画リソースルート
    // =====================================================
    Route::resource('movies', MovieController::class);

    // =====================================================
    // マイページ・プロフィール
    // =====================================================
    Route::get('/mypage', [
        ProfileController::class,
        'show',
    ])->name('mypage');

    Route::get('/profile', [
        ProfileController::class,
        'edit',
    ])->name('profile.edit');

    Route::put('/profile', [
        ProfileController::class,
        'update',
    ])->name('profile.update');

    Route::patch('/profile', [
        ProfileController::class,
        'update',
    ]);

    Route::delete('/profile', [
        ProfileController::class,
        'destroy',
    ])->name('profile.destroy');

    Route::put('/password', [
        PasswordController::class,
        'update',
    ])->name('password.update');

    // =====================================================
    // レビュー関連
    // =====================================================
    Route::get('/movies/{id}/reviews', [
        ReviewController::class,
        'index',
    ])->name('reviews.index');

    Route::get('/movies/{id}/reviews/create', [
        ReviewController::class,
        'create',
    ])->name('reviews.create');

    Route::post('/movies/{id}/reviews', [
        ReviewController::class,
        'store',
    ])->name('reviews.store');

    Route::put('/reviews/{review}', [
        ReviewController::class,
        'update',
    ])->name('reviews.update');

    Route::delete('/reviews/{review}', [
        ReviewController::class,
        'destroy',
    ])->name('reviews.destroy');

    Route::post('/reviews/{review}/like', [
        LikeController::class,
        'toggle',
    ])->name('reviews.like');

    // =====================================================
    // コメント関連
    // =====================================================
    Route::post('/reviews/{review}/comments', [
        ReviewController::class,
        'storeComment',
    ])->name('reviews.comments.store');

    Route::put('/reviews/comments/{comment}', [
        ReviewController::class,
        'updateComment',
    ])->name('reviews.comments.update');

    Route::delete('/comments/{comment}', [
        ReviewController::class,
        'destroyComment',
    ])->name('reviews.comments.destroy');

    // =====================================================
    // 通知一括既読
    // =====================================================
    Route::post('/notifications/read-all', function () {

        auth()->user()->unreadNotifications->markAsRead();

        return response()->json([
            'status' => 'success',
        ]);

    })->name('notifications.readAll');

    // =====================================================
    // 通知一括削除
    // =====================================================
    Route::delete('/notifications/delete-all', function () {

        auth()->user()->notifications()->delete();

        return back()->with(
            'success',
            'お知らせをすべて消去しました'
        );

    })->name('notifications.deleteAll');

    // =====================================================
    // ウォッチリスト関連
    // =====================================================
    Route::get('/watchlist', [
        WatchlistController::class,
        'index',
    ])->name('watchlist.index');

    Route::post('/watchlist/toggle', [
        WatchlistController::class,
        'toggle',
    ])->name('watchlist.toggle');

});

// =========================================================
// 映画タイトルのリアルタイム検索API
// =========================================================
Route::get('/api/movies/search', [
    MovieController::class,
    'autocomplete',
])->name('api.movies.search');

// =========================================================
// 通知削除用テストルート
// 本番環境では削除することをおすすめします
// =========================================================
Route::get('/clear-notifications', function () {

    DB::table('notifications')->truncate();

    return '通知データをすべて消去しました！';

});

// マイページ通知削除用テストルート
Route::get('/mypage-clear', function () {

    auth()->user()->notifications()->delete();

    return 'ログインユーザーの通知を削除しました！';

})->middleware('auth');