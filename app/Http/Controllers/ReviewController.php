<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Comment;
use App\Notifications\ReviewCommented;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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

        // ★ 映画タイトルが入っていない既存データに対して、TMDB API からタイトルを取得・補完する
        foreach ($reviews as $review) {
            if (empty($review->movie_title) && !empty($review->movie_id)) {
                try {
                    $apiKey = config('services.tmdb.key') ?? env('TMDB_API_KEY');
                    if ($apiKey) {
                        $response = Http::get("https://api.themoviedb.org/3/movie/{$review->movie_id}", [
                            'api_key' => $apiKey,
                            'language' => 'ja-JP',
                        ]);

                        if ($response->successful()) {
                            $data = $response->json();
                            $title = $data['title'] ?? $data['original_title'] ?? null;
                            if ($title) {
                                $review->movie_title = $title;
                                // 今後のためにDBにも保存しておく
                                $review->save();
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // API通信エラー時は無視してスキップ
                }
            }
        }

        return view('community', compact('reviews'));
    }

    public function store(Request $request)
    {
        // フォームのステータスをデータベース用の値に統一
        $status = $request->input('status');

        if ($status === 'want_to_watch') {
            $status = 'want';
        }

        // フォームから送信された内容を検証
        $validated = validator(
            array_merge($request->all(), [
                'status' => $status,
            ]),
            [
                'movie_id' => 'required',
                'movie_title' => 'nullable|string|max:255', // ★ movie_title を受け取れるように追加
                'status' => 'required|in:watched,want',
                'comment' => 'required|string|max:5000',
                'rating' => 'required|numeric|min:1|max:10',
                'moods' => 'nullable|array|max:4',
                'moods.*' => 'string|max:50',
            ]
        )->validate();

        // レビューを新規作成
        $review = new Review();

        $review->user_id = Auth::id();
        $review->movie_id = $validated['movie_id'];

        // ★ 映画タイトルを保存（フォームから送られていない場合は API から補完）
        $movieTitle = $request->input('movie_title');
        if (empty($movieTitle)) {
            try {
                $apiKey = config('services.tmdb.key') ?? env('TMDB_API_KEY');
                if ($apiKey) {
                    $res = Http::get("https://api.themoviedb.org/3/movie/{$validated['movie_id']}", [
                        'api_key' => $apiKey,
                        'language' => 'ja-JP',
                    ]);
                    if ($res->successful()) {
                        $movieTitle = $res->json()['title'] ?? null;
                    }
                }
            } catch (\Exception $e) {
                // 通信エラー時
            }
        }
        $review->movie_title = $movieTitle; // ★ movie_title カラムに保存

        $review->status = $validated['status'];

        // content は必須カラムのため、必ず値を設定
        $review->content = $validated['comment'];

        // 既存の一覧表示との互換性を維持
        $review->comment = $validated['comment'];

        $review->rating = $validated['rating'];

        // 選択したタグをカンマ区切りで保存
        $review->mood = implode(',', $validated['moods'] ?? []);

        // データベースへ保存
        $review->save();

        // 投稿したレビューの位置へ戻る
        $previousUrl = preg_replace(
            '/#.*$/',
            '',
            url()->previous()
        );

        return redirect()
            ->to($previousUrl . '#review-' . $review->id)
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