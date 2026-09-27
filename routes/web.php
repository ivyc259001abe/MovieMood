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

// ログイン処理（重複を整理）
Route::post('/', [AuthenticatedSessionController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// 新規登録・ログアウト
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// 🔑 パスワード再設定
Route::get('/password/reset', function () {
    return view('password_reset');
})->name('password.request');

Route::post('/password/reset', [PasswordController::class, 'resetPassword'])->name('password.reset.update');

// 🌟 映画関連（MovieControllerに集約）
Route::get('/home', [MovieController::class, 'home'])->name('home');
Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
Route::get('/result', [MovieController::class, 'search'])->name('result');
Route::resource('movies', MovieController::class);

// 🎬 コミュニティ表示ルート
Route::get('/community', function () {
    // TMDbから人気映画（候補用）を取得
    $popularMovies = getPopularMovies();

    // レビュー一覧を取得
    $reviews = Review::with('user')
        ->latest()
        ->paginate(10);

    return view('community', compact('reviews', 'popularMovies'));
})->name('community.index');

// 🌟 コミュニティ（投稿保存処理）
Route::post('/community', function (Request $request) {
    // 1. バリデーション
    $validated = $request->validate([
        'movie_title' => 'required|string|max:255',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|max:1000',
        'moods' => 'nullable|array', // 複数選択（配列）
    ]);

    // 2. 気分（ムード）タグをカンマ区切り文字列に結合
    $moodsString = !empty($request->moods) ? implode(', ', $request->moods) : null;

    // 3. レビューの保存（データベースへ追加）
    Review::create([
        'user_id' => auth()->id(),
        'movie_title' => $validated['movie_title'],
        'rating' => $validated['rating'],
        'comment' => $validated['comment'],
        'mood' => $moodsString,
    ]);

    return redirect()->route('community.index')->with('success', 'レビューを投稿しました！');
})->name('community.store')->middleware('auth');

// ★ マイページ・プロフィール・レビュー関連（ログインユーザー専用）
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [ProfileController::class, 'show'])->name('mypage');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    // レビュー・いいね関連
    Route::get('/movies/{id}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/movies/{id}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/movies/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/like', [LikeController::class, 'toggle'])->name('reviews.like');
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