<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // ★ TMDB API通信用に追加
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $popularMovies = [];

        try {
            // TMDB APIから人気の映画データを取得
            $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));
            $token = config('services.tmdb.token', env('TMDB_TOKEN'));

            if ($token) {
                $response = Http::withToken($token)->get('https://api.themoviedb.org/3/movie/popular', [
                    'language' => 'ja-JP',
                    'page' => 1,
                ]);
            } else {
                $response = Http::get('https://api.themoviedb.org/3/movie/popular', [
                    'api_key' => $apiKey,
                    'language' => 'ja-JP',
                    'page' => 1,
                ]);
            }

            if ($response->successful()) {
                $popularMovies = $response->json()['results'] ?? [];
            }
        } catch (\Exception $e) {
            // エラー時は空配列のまま処理を続行
            $popularMovies = [];
        }

        // ビューに $popularMovies を渡す
        return view('auth.login', compact('popularMovies'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // 🌟 ログインしたユーザーの DB アバター情報をセッションに復元
        if (Auth::user()->avatar) {
            session(['user_icon' => Auth::user()->avatar]);
        }

        // RouteServiceProvider::HOME が存在すればそこへ、なければトップページへリダイレクト
        if (class_exists(RouteServiceProvider::class) && defined('App\Providers\RouteServiceProvider::HOME')) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return redirect()->intended('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}