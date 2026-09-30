<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    // ウォッチリスト一覧画面
    public function index()
    {
        $watchlists = Auth::user()->watchlists()->latest()->get();
        return view('watchlists.index', compact('watchlists'));
    }

    // トグル登録・削除（フォーム送信 / Ajax 両対応）
    public function toggle(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|string',
            'title' => 'required|string',
            'poster_path' => 'nullable|string',
        ]);

        $user = Auth::user();
        $existing = Watchlist::where('user_id', $user->id)
            ->where('movie_id', $request->movie_id)
            ->first();

        if ($existing) {
            $existing->delete();

            // Ajaxリクエストの場合はJSONで返す
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'removed', 'message' => 'ウォッチリストから削除しました']);
            }

            // 通常のフォーム送信の場合は元の画面へ戻す
            return back()->with('success', 'ウォッチリストから削除しました');
        }

        Watchlist::create([
            'user_id' => $user->id,
            'movie_id' => $request->movie_id,
            'title' => $request->title,
            'poster_path' => $request->poster_path,
        ]);

        // Ajaxリクエストの場合はJSONで返す
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'added', 'message' => 'ウォッチリストに追加しました']);
        }

        // 通常のフォーム送信の場合は元の画面へ戻す
        return back()->with('success', 'ウォッチリストに追加しました');
    }
}