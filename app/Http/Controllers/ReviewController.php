<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Comment;
use App\Notifications\ReviewCommented;
use App\Notifications\ReviewLikedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ReviewController extends Controller
{
    /**
     * 指定された映画のすべてのレビュー一覧を表示
     */
    public function index($id)
    {
        $apiKey = config('services.tmdb.api_key', env('TMDB_API_KEY'));

        $response = Http::get("https://api.themoviedb.org/3/movie/{$id}", [
            'api_key' => $apiKey,
            'language' => 'ja-JP',
        ]);

        $movie = $response->successful() ? $response->json() : [
            'id' => $id,
            'title' => '映画作品',
            'poster_path' => '',
        ];

        // ⭕ tmdb_id の検索を削除し、movie_id だけにしてエラーを防止
        $reviews = Review::with(['user', 'likes', 'comments.user'])
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
            'rating' => 'required|numeric|min:0.1|max:10.0',
            'moods' => 'nullable|array',
            'comment' => 'required|string|max:1000',
        ]);

        // 気分タグの整理（#を除外してカンマ区切りに変換）
        $moodsArray = $request->moods ?? [];
        $cleanedMoods = array_map(function ($m) {
            return str_replace('#', '', $m);
        }, $moodsArray);
        $moodsString = !empty($cleanedMoods) ? implode(',', $cleanedMoods) : ($request->mood ?? null);

        // 基本データのセット
        $data = [
            'user_id' => Auth::id(),
            'movie_id' => $id,
            'movie_title' => $request->movie_title ?? $request->title ?? '映画作品',
            'rating' => $request->rating,
        ];

        // 本文カラム（comment / content）のどちらが存在しても両方/片方に確実にセット
        if (Schema::hasColumn('reviews', 'comment')) {
            $data['comment'] = $request->comment;
        }
        if (Schema::hasColumn('reviews', 'content')) {
            $data['content'] = $request->comment;
        }

        // 気分タグカラムのセット
        if (Schema::hasColumn('reviews', 'moods')) {
            $data['moods'] = $moodsString;
        }
        if (Schema::hasColumn('reviews', 'mood')) {
            $data['mood'] = $moodsString;
        }

        // 映画ID / ポスター画像のセット
        if (Schema::hasColumn('reviews', 'tmdb_id')) {
            $data['tmdb_id'] = $id;
        }
        if (Schema::hasColumn('reviews', 'movie_poster_path')) {
            $data['movie_poster_path'] = $request->poster_path ?? $request->movie_poster_path ?? '';
        }
        if (Schema::hasColumn('reviews', 'poster_path')) {
            $data['poster_path'] = $request->poster_path ?? '';
        }

        Review::create($data);

        return redirect()->route('community.index')->with('success', 'レビューを投稿しました！');
    }

    /**
     * 💬 レビューへのコメント投稿 ＆ 通知送信
     */
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $review = Review::findOrFail($id);
        $user = Auth::user();

        // 💡 comments テーブルのカラム名に合わせて保存（comment カラムを使用）
        $commentData = ['user_id' => $user->id];

        if (Schema::hasColumn('comments', 'comment')) {
            $commentData['comment'] = $request->comment;
        } elseif (Schema::hasColumn('comments', 'content')) {
            $commentData['content'] = $request->comment;
        } else {
            $commentData['body'] = $request->comment;
        }

        $comment = $review->comments()->create($commentData);

        // 🔔 レビュー投稿者（自分以外）へコメント通知を送信
        if ($review->user_id !== $user->id) {
            try {
                if ($review->user) {
                    $review->user->notify(new ReviewCommented($user, $review, $request->comment));
                }
            } catch (\Exception $e) {
                \Log::error('Comment Notification Error: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'コメントを投稿しました！');
    }

    /**
     * 🌟 いいねのトグル処理（1回で追加・再押下で削除） ＆ 通知送信
     */
    public function like(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $user = Auth::user();

        // すでに「いいね」しているか確認
        $likeQuery = $review->likes()->where('user_id', $user->id);

        if ($likeQuery->exists()) {
            // いいね解除
            $likeQuery->delete();
        } else {
            // いいね登録
            $review->likes()->create(['user_id' => $user->id]);

            // 🔔 レビュー投稿者（自分以外）へ「いいね」通知を送信
            if ($review->user_id !== $user->id) {
                try {
                    if ($review->user) {
                        $review->user->notify(new ReviewLikedNotification($user, $review));
                    }
                } catch (\Exception $e) {
                    \Log::error('Like Notification Error: ' . $e->getMessage());
                }
            }
        }

        return back();
    }

    /**
     * 🗑️ コメントの削除処理
     */
    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);

        // 本人確認
        if ($comment->user_id === Auth::id()) {
            $comment->delete();
            return back()->with('success', 'コメントを削除しました');
        }

        return back()->with('error', '削除権限がありません');
    }
}