<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReviewLikedNotification extends Notification
{
    use Queueable;

    protected $review;
    protected $sender;

    public function __construct($review, $sender)
    {
        $this->review = $review;
        $this->sender = $sender;
    }

    public function via($notifiable)
    {
        return ['database']; // データベースに保存
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'like',
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->nickname ?? $this->sender->name,
            'movie_title' => $this->review->movie_title ?? $this->review->title ?? '映画',
            'review_id' => $this->review->id,
        ];
    }
}