<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
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