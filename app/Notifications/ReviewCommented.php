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

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'comment',
            'sender_id' => $this->commenter->id ?? null,
            'sender_name' => $this->commenter->nickname ?? $this->commenter->name ?? 'ユーザー',
            'movie_title' => $this->review->movie_title ?? $this->review->title ?? '映画',
            'review_id' => $this->review->id ?? null,
            'comment_body' => $this->commentBody,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}