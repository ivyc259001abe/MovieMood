<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * 指定された映画のすべてのレビュー一覧を表示
     */
    public function index($id)
    {
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));

        // 1. TMDb APIから映画情報を取得
        $response = Http::get("https://api.themoviedb.org/3/movie/{$id}", [
            'api_key' => $apiKey,
            'language' => 'ja-JP',
        ]);

        $movie = $response->successful() ? $response->json() : [
            'id' => $id,
            'title' => '映画作品',
            'poster_path' => '',
        ];

        // 2. この映画に関連するレビューを投稿日時が新しい順で取得（ユーザーといいねの情報も一緒に取得）
        $reviews = Review::with(['user', 'likes'])
            ->where('movie_id', $id)
            ->latest()
            ->paginate(10);

        return view('reviews.index', compact('movie', 'reviews'));
    }

    /**
     * レビュー投稿画面の表示
     */
    public function create($id)
    {
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));

        // TMDb APIから対象映画の基本情報を取得
        $response = Http::get("https://api.themoviedb.org/3/movie/{$id}", [
            'api_key' => $apiKey,
            'language' => 'ja-JP',
        ]);

        $movie = $response->successful() ? $response->json() : [
            'id' => $id,
            'title' => '映画作品',
            'poster_path' => '',
        ];

        return view('reviews.create', compact('movie'));
    }

    /**
     * レビューの保存処理
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'moods' => 'nullable|array',
            'comment' => 'required|string|max:1000',
        ]);

        // 気分タグの配列をカンマ区切り文字列に変換
        $moodsString = $request->has('moods') ? implode(',', $request->moods) : null;

        Review::create([
            'user_id' => Auth::id(),
            'movie_id' => $id,
            'movie_title' => $request->movie_title ?? '映画作品',
            'poster_path' => $request->poster_path ?? '',
            'rating' => $request->rating,
            'moods' => $moodsString,
            'comment' => $request->comment,
        ]);

        // 投稿完了後はコミュニティ画面（タイムライン）へ移動
        return redirect()->route('community.index')->with('success', 'レビューを投稿しました！');
    }
}