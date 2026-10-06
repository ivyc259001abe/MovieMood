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
        // 映画タイトルの取得（リレーション movie も安全にフォールバック）
        $movieTitle = $this->review->movie_title
            ?? $this->review->movie->title
            ?? $this->review->title
            ?? '映画';

        // Reviewモデル内の映画ID
        $movieId = $this->review->movie_id ?? $this->review->tmdb_id ?? null;

        // 遷移先のURLを事前に生成
        $url = '#';
        if ($movieId) {
            if (\Illuminate\Support\Facades\Route::has('movies.show')) {
                $url = route('movies.show', $movieId);
            } elseif (\Illuminate\Support\Facades\Route::has('reviews.index')) {
                $url = route('reviews.index', $movieId);
            } else {
                $url = url('/movies/' . $movieId);
            }
            $url .= '#review-' . $this->review->id;
        }

        return [
            'type' => 'like',
            'user_id' => $this->liker->id,
            'user_nickname' => $this->liker->nickname ?? $this->liker->name ?? '映画ファン',
            'sender_name' => $this->liker->nickname ?? $this->liker->name ?? '映画ファン', // 互換用
            'movie_id' => $movieId,
            'movie_title' => $movieTitle,
            'review_id' => $this->review->id,
            'message' => 'に「いいね！」しました。', // ★末尾につく文章のみを指定
            'url' => $url,
        ];
    }
}