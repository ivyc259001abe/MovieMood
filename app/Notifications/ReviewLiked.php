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
        return [
            'message' => "{$this->liker->name} さんがあなたの『{$movieTitle}』のレビューにいいね！しました",
            'review_id' => $this->review->id,
            'liker_id' => $this->liker->id,
        ];
    }
}