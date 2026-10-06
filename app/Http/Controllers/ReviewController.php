<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Comment;
use App\Notifications\ReviewLikedNotification;
use App\Notifications\ReviewCommented;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * コミュニティ画面
     */
    public function community(Request $request)
    {
        $query = Review::with([
            'user',
            'comments.user',
            'likes'
        ]);

        if ($request->filled('mood')) {
            $query->where(
                'mood',
                'like',
                '%' . $request->mood . '%'
            );
        }

        $reviews = $query
            ->latest()
            ->paginate(10);

        return view('community', compact('reviews'));
    }

    /**
     * レビュー投稿
     */
    public function store(Request $request, $movieId = null)
    {
        $request->validate([
            'comment' => 'required|string',
            'movie_title' => 'nullable|string',
        ]);

        $moods = $request->input(
            'moods',
            $request->input('mood', [])
        );

        if (is_array($moods)) {
            $moodString = implode(
                ',',
                array_filter($moods)
            );
        } else {
            $moodString = (string) $moods;
        }

        $commentText = $request->input('comment');

        $movieTitle = $request->input('movie_title')
            ?? $request->input('title')
            ?? $request->input('movie_name');

        Review::create([
            'user_id' => Auth::id(),
            'movie_id' => (string) (
                $movieId
                ?? $request->input('movie_id')
            ),
            'movie_title' => $movieTitle,
            'rating' => $request->input('rating', 8.0),
            'mood' => $moodString,
            'content' => $commentText,
            'comment' => $commentText,
        ]);

        return redirect()
            ->to(url()->previous() . '#reviews')
            ->with('success', 'レビューを投稿しました！');
    }

    /**
     * レビュー削除
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id === Auth::id()) {
            $review->delete();

            return back()->with(
                'success',
                'レビューを削除しました。'
            );
        }

        return back()->with(
            'error',
            '削除権限がありません。'
        );
    }

    /**
     * 映画ごとのレビュー一覧
     */
    public function index($id)
    {
        $reviews = Review::where(
            'movie_id',
            (string) $id
        )
            ->with([
                'user',
                'likes',
                'comments.user'
            ])
            ->latest()
            ->paginate(10);

        return view(
            'reviews.index',
            compact('reviews', 'id')
        );
    }

    /**
     * コメント投稿
     */
    public function storeComment(
        Request $request,
        $reviewId
    ) {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        // コメント対象のレビューを取得
        // レビュー投稿者も一緒に取得
        $review = Review::with('user')
            ->findOrFail($reviewId);

        // 現在ログインしているユーザー
        $user = Auth::user();

        // コメントを保存
        $comment = Comment::create([
            'user_id' => $user->id,
            'review_id' => $review->id,
            'comment' => $request->comment,
        ]);

        /*
         * 自分のレビューに自分でコメントした場合は
         * 通知を送らない
         */
        if ($review->user_id !== $user->id) {
            $review->user->notify(
                new ReviewCommented(
                    $user,
                    $review,
                    $comment
                )
            );
        }

        return back()->with(
            'success',
            'コメントを投稿しました'
        );
    }

    /**
     * コメント編集
     */
    public function updateComment(
        Request $request,
        $id
    ) {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            return back()->with(
                'error',
                '編集権限がありません。'
            );
        }

        $comment->update([
            'comment' => $request->comment,
        ]);

        return back()->with(
            'success',
            'コメントを更新しました！'
        );
    }

    /**
     * コメント削除
     */
    public function destroyComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if ($comment->user_id === Auth::id()) {
            $comment->delete();

            return back()->with(
                'success',
                'コメントを削除しました'
            );
        }

        return back()->with(
            'error',
            '削除権限がありません'
        );
    }
}