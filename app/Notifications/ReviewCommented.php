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

        // 2. 映画タイトルの取得（$this->review や $this->movie から取得）
        $movieTitle = $this->review->movie_title
            ?? $this->review->movie->title
            ?? $this->movie->title
            ?? '映画作品';

        return [
            'user_name' => $userName,
            'movie_title' => $movieTitle, // 👈 ここで動的な映画タイトルをセットします
            'comment' => $this->comment->comment ?? '',
            'url' => route('reviews.index', ['movie_id' => $this->review->movie_id ?? 1]) . '#review-' . ($this->review->id ?? ''),
        ];
    }
}