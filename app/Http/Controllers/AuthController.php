<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    /**
     * TMDb APIから人気の映画を取得する共通メソッド
     */
    private function getPopularMovies(): array
    {
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));
        $response = Http::get("https://api.themoviedb.org/3/movie/popular", [
            'api_key' => $apiKey,
            'language' => 'ja-JP',
            'page' => 1,
        ]);

        return $response->successful() ? $response->json()['results'] : [];
    }

    // 新規登録画面の表示
    public function showRegisterForm()
    {
        $popularMovies = $this->getPopularMovies();
        return view('register', compact('popularMovies'));
    }

    // 新規登録処理（パターンA：登録完了後そのままログインしてホームへ）
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // アイコン画像の保存処理
        $iconPath = null;
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = 'icon_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $iconPath = 'uploads/' . $filename;
        }

        // セッションに登録情報をセットして自動ログイン状態にする
        session([
            'user_name' => $request->name,
            'user_email' => $request->email,
            'user_icon' => $iconPath,
        ]);

        // フラッシュメッセージを渡してホームへ遷移
        return redirect('/')->with('success', '🎉 会員登録が完了しました！MovieMoodへようこそ！');
    }

    // ログイン画面の表示
    public function showLoginForm()
    {
        $popularMovies = $this->getPopularMovies();
        return view('login', compact('popularMovies'));
    }

    // ログイン処理
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 過去に設定された名前・アイコンがあればそれを優先保持する
        $userName = session('user_name') ?? explode('@', $request->email)[0];
        $userIcon = session('user_icon') ?? null;

        session([
            'user_name' => $userName,
            'user_email' => $request->email,
            'user_icon' => $userIcon,
        ]);

        return redirect('/');
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        session()->forget('user_email');
        return redirect('/login');
    }
}