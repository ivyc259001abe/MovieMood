<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\WatchlistController;

// ★ TMDb人気映画取得ヘルパー関数
if (!function_exists('getPopularMovies')) {
    function getPopularMovies()
    {
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));
        $response = Http::get("https://api.themoviedb.org/3/movie/popular", [
            'api_key' => $apiKey,
            'language' => 'ja-JP',
            'page' => 1,
        ]);
        return $response->successful() ? $response->json()['results'] : [];
    }
}

// 🌟 トップページ（ログイン時は MovieController@home、未ログイン時は ログイン画面）
Route::get('/', function () {
    if (Auth::check()) {
        return app(MovieController::class)->home();
    }
    $popularMovies = getPopularMovies();
    return view('login', compact('popularMovies'));
})->name('login');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/');
    }
    $popularMovies = getPopularMovies();
    return view('login', compact('popularMovies'));
});

// ログイン処理
Route::post('/', [AuthenticatedSessionController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// 新規登録・ログアウト
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// 🔑 パスワード再設定
Route::get('/password/reset', function () {
    $popularMovies = getPopularMovies();
    return view('password_reset', compact('popularMovies'));
})->name('password.request');

Route::post('/password/reset', [PasswordController::class, 'resetPassword'])->name('password.reset.update');

// 🎬 コミュニティ表示ルート
Route::get('/community', function () {
    $popularMovies = getPopularMovies();

    $reviews = Review::with('user')
        ->latest()
        ->paginate(10);

    return view('community', compact('reviews', 'popularMovies'));
})->name('community.index');

// 🌟 コミュニティ（投稿保存処理）
Route::post('/community', function (Request $request) {
    $validated = $request->validate([
        'movie_title' => 'required|string|max:255',
        'rating' => 'required|integer|min:1|max:10', // ★ max:5 から max:10 に変更
        'comment' => 'required|string|max:1000',
        'moods' => 'nullable|array',
    ]);

    $moodsString = !empty($request->moods) ? implode(', ', $request->moods) : null;

    Review::create([
        'user_id' => auth()->id(),
        'movie_title' => $validated['movie_title'],
        'rating' => $validated['rating'],
        'comment' => $validated['comment'],
        'mood' => $moodsString,
    ]);

    return redirect()->route('community.index')->with('success', 'レビューを投稿しました！');
})->name('community.store')->middleware('auth');

// 🌟 ログインユーザー専用機能グループ
Route::middleware('auth')->group(function () {
    // ホーム画面
    Route::get('/home', [MovieController::class, 'home'])->name('home');

    // 🔍 映画検索
    Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
    Route::get('/result', [MovieController::class, 'search'])->name('result');

    // 🎬 映画リソースルート
    Route::resource('movies', MovieController::class);

    // 👤 マイページ＆プロフィール関連
    Route::get('/mypage', [ProfileController::class, 'show'])->name('mypage');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    // ✏️ レビュー・いいね関連
    Route::get('/movies/{id}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/movies/{id}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/movies/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/like', [LikeController::class, 'toggle'])->name('reviews.like');

    // 🔖 ウォッチリスト関連
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist/toggle', [WatchlistController::class, 'toggle'])->name('watchlist.toggle');
});

// 🔍 映画タイトルのリアルタイム検索API（TMDb連携）
Route::get('/api/movies/search', function (Request $request) {
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

    return $response->successful() ? $response->json()['results'] : [];
});