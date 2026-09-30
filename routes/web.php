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

// 🎬 コミュニティ表示ルート（気分絞り込み＆コメント等事前読み込み対応）
Route::get('/community', function (Request $request) {
    $popularMovies = getPopularMovies();

    // ユーザー・コメント・コメント投稿者・いいねをまとめて事前に取得（Eager Loading）
    $query = Review::with(['user', 'comments.user', 'likes'])->latest();

    // リクエストに気分（mood）パラメータが存在する場合は絞り込み
    if ($request->filled('mood')) {
        $query->where('mood', 'like', '%' . $request->mood . '%'); // ⭕️ 存在する mood カラムのみ指定
    }

    // ページネーション（パラメータを維持）
    $reviews = $query->paginate(10)->withQueryString();

    return view('community', compact('reviews', 'popularMovies'));
})->name('community.index');

// 🌟 コミュニティ（投稿保存処理）
Route::post('/community', function (Request $request) {
    $validated = $request->validate([
        'movie_title' => 'nullable|string|max:255',
        'rating' => 'required|numeric|min:1|max:10',
        'comment' => 'required|string|max:1000',
        'moods' => 'nullable|array',
    ]);

    $moodsString = !empty($request->moods) ? implode(', ', $request->moods) : null;
    $title = !empty($validated['movie_title']) ? $validated['movie_title'] : 'お気に入り映画';

    Review::create([
        'user_id' => auth()->id(),
        'movie_id' => $request->input('movie_id', 0),
        'movie_title' => $title,
        'rating' => floatval($validated['rating']),
        'comment' => $validated['comment'],
        'content' => $validated['comment'],
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

    // ✏️ レビュー・いいね・コメント関連
    Route::get('/movies/{id}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/movies/{id}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/movies/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/like', [LikeController::class, 'toggle'])->name('reviews.like');

    // 💬 コメント保存用ルート
    Route::post('/reviews/{review}/comments', [ReviewController::class, 'storeComment'])->name('reviews.comments.store');

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

// 🔍 映画タイトルのリアルタイム検索API（TMDb連携）
Route::get('/api/movies/search', function (Illuminate\Http\Request $request) {
    $query = $request->query('query');
    if (!$query) {
        return response()->json([]);
    }

    $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));

    $response = Illuminate\Support\Facades\Http::get("https://api.themoviedb.org/3/search/movie", [
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
});