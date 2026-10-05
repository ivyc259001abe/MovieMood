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
        // 送信者（いいねを押した人）の名前を取得
        $userName = 'ユーザー';
        if ($this->liker && is_object($this->liker)) {
            $userName = $this->liker->nickname ?? $this->liker->name ?? 'ユーザー';
        } elseif (auth()->check()) {
            $userName = auth()->user()->nickname ?? auth()->user()->name ?? 'ユーザー';
        }

        $movieId = $this->review->movie_id ?? $this->review->tmdb_id ?? null;
        $url = $movieId ? route('reviews.index', $movieId) . '#review-' . $this->review->id : '#';

        return [
            'type' => 'like', // ⭕ ハートアイコン判別用
            'user_name' => $userName,
            'sender_nickname' => $userName,
            'sender_id' => $this->liker->id ?? auth()->id(), // ⭕ ユーザーIDの保存
            'message' => 'あなたのレビューに「いいね！」しました',
            'movie_id' => $movieId,
            'movie_title' => $this->review->movie_title ?? $this->review->title ?? null,
            'review_id' => $this->review->id,
            'url' => $url,
        ];
    }
}