<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Review;

class ReviewCommented extends Notification
{
    use Queueable;

    public $commenter;
    public $review;
    public $commentBody;

    public function __construct($commenter, Review $review, $commentBody = '')
    {
        $this->commenter = $commenter;
        $this->review = $review;
        $this->commentBody = $commentBody;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // 1. 投稿者名の判定（nickname > name > username > フォールバック）
        $userName = 'ユーザー';
        if (is_object($this->commenter)) {
            $userName = $this->commenter->nickname
                ?? $this->commenter->name
                ?? $this->commenter->user_name
                ?? 'ユーザー';
        } elseif (auth()->check()) {
            $userName = auth()->user()->nickname
                ?? auth()->user()->name
                ?? 'ユーザー';
        }

        // 2. 映画IDの取得（movie_id または tmdb_id）
        $movieId = $this->review->movie_id ?? $this->review->tmdb_id ?? null;

        // 3. URLの生成
        $url = '#';
        if ($movieId) {
            $url = route('reviews.index', $movieId) . '#review-' . $this->review->id;
        }

        return [
            'user_name' => $userName,
            'user_nickname' => $userName,
            'userName' => $userName,
            // 💡 先頭に $userName を結合して「○○さんが〜」となるように修正
            'message' => $userName . 'さんがあなたの『' . ($this->review->movie_title ?? '映画') . '』のレビューにコメントしました',
            'movie_id' => $movieId,
            'review_id' => $this->review->id,
            'url' => $url,
        ];
    }
}