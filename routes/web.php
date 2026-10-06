<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\ReviewController;

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

// 🌟 トップページ（ログイン時は /home へ移動、未ログイン時は ログイン画面）
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    $popularMovies = getPopularMovies();
    return view('login', compact('popularMovies'));
});

// 🔑 ログイン画面（認証エラー回避のため ->name('login') を付与）
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    $popularMovies = getPopularMovies();
    return view('login', compact('popularMovies'));
})->name('login');

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

// 🎬 コミュニティ表示・投稿（ReviewControllerへ集約）
Route::get('/community', [ReviewController::class, 'community'])->name('community.index');
Route::post('/community', [ReviewController::class, 'store'])->name('community.store')->middleware('auth');

// 🌟 ログインユーザー専用機能グループ
Route::middleware('auth')->group(function () {
    // 1. ホーム画面
    Route::get('/home', [MovieController::class, 'home'])->name('home');

    // 2. 🔍 映画検索・Mood絞り込み（★404防止のためRoute::resourceより前に定義）
    Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
    Route::get('/result', [MovieController::class, 'search'])->name('result');

    // 3. 🎬 映画リソースルート（★searchの後に配置）
    Route::resource('movies', MovieController::class);

    // 👤 マイページ＆プロフィール関連
    Route::get('/mypage', [ProfileController::class, 'show'])->name('mypage');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    // ✏️ レビュー・いいね・コメント関連
    Route::get('/movies/{id}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/movies/{id}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/movies/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/reviews/{review}/like', [LikeController::class, 'toggle'])->name('reviews.like');

    // 💬 コメント関連
    Route::post('/reviews/{review}/comments', [ReviewController::class, 'storeComment'])->name('reviews.comments.store');
    Route::put('/reviews/comments/{comment}', [ReviewController::class, 'updateComment'])->name('reviews.comments.update'); // ← ★修正：ReviewControllerのupdateCommentを指定
    Route::delete('/comments/{comment}', [ReviewController::class, 'destroyComment'])->name('reviews.comments.destroy');

    // 🔔 通知一括既読用ルート
    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'success']);
    })->name('notifications.readAll');

    // 🗑️ 通知一括削除用ルート
    Route::delete('/notifications/delete-all', function () {
        auth()->user()->notifications()->delete();
        return back()->with('success', 'お知らせをすべて消去しました');
    })->name('notifications.deleteAll');

    // 🔖 ウォッチリスト関連
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist/toggle', [WatchlistController::class, 'toggle'])->name('watchlist.toggle');
});

// 🔍 映画タイトルのリアルタイム検索API（MovieController@autocompleteへ接続）
Route::get('/api/movies/search', [MovieController::class, 'autocomplete'])->name('api.movies.search');

Route::get('/clear-notifications', function () {
    \Illuminate\Support\Facades\DB::table('notifications')->truncate();
    return '通知データをすべて消去しました！';
});
// マイページ（/mypage）を開いた瞬間に通知を全削除する（テスト用）
Route::get('/mypage-clear', function () {
    auth()->user()->notifications()->delete();
    return 'ログインユーザーの通知を削除しました！';
})->middleware('auth');