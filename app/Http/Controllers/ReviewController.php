<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Comment;
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
            'likes',
        ]);

        if ($request->filled('mood')) {
            $query->where(
                'mood',
                'like',
                '%' . $request->mood . '%'
            );
        }

        $reviews = $query->latest()->paginate(10);

        return view('community', compact('reviews'));
    }

    /**
     * レビュー投稿
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required',
            'status' => 'required|in:watched,want',
            'review' => 'nullable|string|max:5000',
            'rating' => 'nullable|numeric|min:1|max:10',
            'mood' => 'nullable|string|max:255',
        ]);

        $review = new Review();
        $review->user_id = Auth::id();
        $review->movie_id = $validated['movie_id'];
        $review->status = $validated['status'];
        $review->review = $validated['review'] ?? null;
        $review->rating = $validated['rating'] ?? null;
        $review->mood = $validated['mood'] ?? null;
        $review->save();

        return redirect()
            ->to(url()->previous() . '#review-' . $review->id)
            ->with('success', 'レビューを投稿しました。');
    }

    /**
     * 自分のレビューを編集
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        abort_unless(
            (int) $review->user_id === (int) Auth::id(),
            403,
            '編集権限がありません。'
        );

        $validated = $request->validate([
            'review' => 'nullable|string|max:5000',
            'rating' => 'nullable|numeric|min:1|max:10',
            'mood' => 'nullable|string|max:255',
        ]);

        $review->update($validated);

        $previousUrl = preg_replace(
            '/#.*$/',
            '',
            url()->previous()
        );

        return redirect()
            ->to($previousUrl . '#review-' . $review->id)
            ->with('success', 'レビューを更新しました。');
    }

    /**
     * 自分のレビューを削除
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        abort_unless(
            (int) $review->user_id === (int) Auth::id(),
            403,
            '削除権限がありません。'
        );

        $review->delete();

        $previousUrl = preg_replace(
            '/#.*$/',
            '',
            url()->previous()
        );

        return redirect()
            ->to($previousUrl . '#reviews')
            ->with('success', 'レビューを削除しました。');
    }

    /**
     * 映画ごとのレビュー一覧
     */
    public function index($id)
    {
        $reviews = Review::where('movie_id', (string) $id)
            ->with([
                'user',
                'likes',
                'comments.user',
            ])
            ->latest()
            ->paginate(10);

        return view('reviews.index', compact('reviews', 'id'));
    }

    /**
     * コメント投稿
     */
    public function storeComment(Request $request, $reviewId)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $review = Review::with('user')->findOrFail($reviewId);
        $user = Auth::user();

        $comment = Comment::create([
            'user_id' => $user->id,
            'review_id' => $review->id,
            'comment' => $validated['comment'],
        ]);

        // レビュー投稿者本人以外からのコメントは通知する
        if ((int) $review->user_id !== (int) $user->id) {
            $review->user->notify(
                new ReviewCommented($user, $review, $comment)
            );
        }

        $previousUrl = preg_replace(
            '/#.*$/',
            '',
            url()->previous()
        );

        return redirect()
            ->to($previousUrl . '#review-' . $review->id)
            ->with('success', 'コメントを投稿しました。');
    }

    /**
     * 自分のコメントを編集
     */
    public function updateComment(Request $request, $id)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment = Comment::findOrFail($id);

        abort_unless(
            (int) $comment->user_id === (int) Auth::id(),
            403,
            '編集権限がありません。'
        );

        $comment->update([
            'comment' => $validated['comment'],
        ]);

        $previousUrl = preg_replace(
            '/#.*$/',
            '',
            url()->previous()
        );

        return redirect()
            ->to($previousUrl . '#review-' . $comment->review_id)
            ->with('success', 'コメントを更新しました。');
    }

    /**
     * 自分のコメントを削除
     */
    public function destroyComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        abort_unless(
            (int) $comment->user_id === (int) Auth::id(),
            403,
            '削除権限がありません。'
        );

        $reviewId = $comment->review_id;
        $comment->delete();

        $previousUrl = preg_replace(
            '/#.*$/',
            '',
            url()->previous()
        );

        return redirect()
            ->to($previousUrl . '#review-' . $reviewId)
            ->with('success', 'コメントを削除しました。');
    }
}