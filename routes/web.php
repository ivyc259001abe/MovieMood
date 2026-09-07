<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// トップページアクセス時（ログイン状態で分岐）
Route::get('/', function () {
    // ログイン済み、またはセッションにユーザー名があればホーム画面（home.blade.php）を表示
    if (Auth::check() || session()->has('user_name')) {
        return view('home');
    }

    // 未ログイン時のTMDb取得処理...
    $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));
    $response = Http::get("https://api.themoviedb.org/3/movie/popular", [
        'api_key' => $apiKey,
        'language' => 'ja-JP',
        'page' => 1,
    ]);

    $popularMovies = $response->successful() ? $response->json()['results'] : [];

    return view('login', compact('popularMovies'));
});

// --------------------------------------------------------------------------
// 認証関連（新規登録・ログイン・ログアウト）
// --------------------------------------------------------------------------

// 新規登録
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// ログイン
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// ログアウト
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']); // GETでのログアウトも許可


// --------------------------------------------------------------------------
// アプリ機能（映画・コミュニティ・結果・プロフィール）
// --------------------------------------------------------------------------

// 映画関連のルート
Route::resource('movies', MovieController::class);

// コミュニティ（感想・投稿一覧）画面
Route::get('/community', function () {
    $posts = [
        [
            'id' => 1,
            'user_name' => '映画好きたろう',
            'user_icon' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=100&q=80',
            'movie_title' => 'トップガン マーヴェリック',
            'poster_path' => '/628311.jpg',
            'mood' => '😆 スカッと',
            'content' => '映画館で3回観ました！冒頭の離陸シーンから鳥肌が止まりません。最高の爽快感です！',
            'likes' => 12,
            'comments_count' => 3,
            'created_at' => '10分前',
            'is_liked' => false,
        ],
        [
            'id' => 2,
            'user_name' => 'シネマニア花子',
            'user_icon' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=100&q=80',
            'movie_title' => '花束みたいな恋をした',
            'poster_path' => '/528311.jpg',
            'mood' => '❤️ キュン',
            'content' => 'リアルすぎる展開に胸が締め付けられました…。観終わったあとしばらく余韻から抜け出せなかったです。',
            'likes' => 25,
            'comments_count' => 8,
            'created_at' => '1時間前',
            'is_liked' => true,
        ],
    ];

    return view('community', compact('posts'));
})->name('community.index');

// 結果画面
Route::get('/result', function (Request $request) {
    $mood = $request->query('mood', 'all');

    $movies = [
        'cry' => [
            'mood_name' => '😭 号泣したい',
            'title' => 'きみの瞳が問いかけている',
            'description' => '視力を失った女性と罪を背負った青年の切ない純愛ストーリー。'
        ],
        'action' => [
            'mood_name' => '😆 スカッと',
            'title' => 'トップガン マーヴェリック',
            'description' => '圧倒的なスピード感と空戦アクション！胸が高鳴る爽快感100%の超大作。'
        ],
        'thrill' => [
            'mood_name' => '😱 ハラハラ',
            'title' => 'ミッシング',
            'description' => '一瞬も目が離せない怒涛の展開。最後まで緊張感が途切れないサスペンス。'
        ],
        'love' => [
            'mood_name' => '❤️ キュン',
            'title' => '花束みたいな恋をした',
            'description' => '偶然出会った2人の5年間を描いたリアルで愛おしいラブストーリー。'
        ],
    ];

    $selectedMovie = $movies[$mood] ?? [
        'mood_name' => '🎬 おすすめ',
        'title' => '映画タイトル',
        'description' => '様々な気分に合わせておすすめの映画を提案します。'
    ];

    return view('result', ['movie' => $selectedMovie]);
});

// 設定（アカウント編集）画面
Route::get('/profile', function () {
    return view('profile');
});

// プロフィール更新処理
Route::post('/profile', function (Request $request) {
    $newName = $request->input('nickname') ?: $request->input('name') ?: session('user_name', 'テストユーザー');
    session(['user_name' => $newName]);

    if ($request->hasFile('icon')) {
        $file = $request->file('icon');
        $filename = 'icon_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);
        session(['user_icon' => '/uploads/' . $filename]);
    }

    return redirect('/');
});