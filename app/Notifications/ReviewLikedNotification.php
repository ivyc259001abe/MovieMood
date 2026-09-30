<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Review;

class ReviewLikedNotification extends Notification
{
    use Queueable;

    protected $liker;
    protected $review;

    public function __construct($liker, Review $review)
    {
        $this->liker = $liker;
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // ユーザー名の安全な取得
        $userName = 'ユーザー';
        if (is_object($this->liker)) {
            $userName = $this->liker->nickname ?? $this->liker->name ?? 'ユーザー';
        } elseif (auth()->check()) {
            $userName = auth()->user()->nickname ?? auth()->user()->name ?? 'ユーザー';
        }

        // 💡 $movieId をここで事前に定義
        $movieId = $this->review->movie_id ?? $this->review->tmdb_id ?? null;

        // 正しいルート名（reviews.index）でURL作成
        $url = $movieId ? route('reviews.index', $movieId) . '#review-' . $this->review->id : '#';

        return [
            'user_name' => $userName,
            'message' => 'さんがあなたのレビューに「いいね！」しました',
            'movie_id' => $movieId,
            'review_id' => $this->review->id,
            'url' => $url,
        ];
    }
}