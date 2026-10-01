<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Review;

class ReviewLiked extends Notification
{
    use Queueable;

    public $liker;
    public $review;

    public function __construct(User $liker, Review $review)
    {
        $this->liker = $liker;
        $this->review = $review;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $movieTitle = $this->review->movie_title ?? '映画';

        // Reviewモデル内の映画IDカラム名（movie_id または tmdb_id）を取得
        $movieId = $this->review->movie_id ?? $this->review->tmdb_id ?? null;

        // 遷移先のURLを事前に生成（movies.show や reviews.index などプロジェクトのルート名に対応）
        $url = '#';
        if ($movieId) {
            if (\Illuminate\Support\Facades\Route::has('movies.show')) {
                $url = route('movies.show', $movieId);
            } elseif (\Illuminate\Support\Facades\Route::has('reviews.index')) {
                $url = route('reviews.index', $movieId);
            } else {
                $url = url('/movies/' . $movieId);
            }
            // レビューの特定の場所までスクロールさせたい場合
            $url .= '#review-' . $this->review->id;
        }

        return [
            'message' => "{$this->liker->name} さんがあなたの『{$movieTitle}』のレビューにいいね！しました",
            'review_id' => $this->review->id,
            'liker_id' => $this->liker->id,
            'movie_id' => $movieId, // ← 追加：映画ID
            'url' => $url,     // ← 追加：遷移先URL
        ];
    }
}