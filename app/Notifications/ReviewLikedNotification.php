<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReviewLikedNotification extends Notification
{
    use Queueable;

    protected $sender;
    protected $review;

    /**
     * 通知を作成
     */
    public function __construct($sender, $review)
    {
        $this->sender = $sender;
        $this->review = $review;
    }

    /**
     * 通知の送信先
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * データベースへ保存する通知データ
     */
    public function toArray($notifiable)
    {
        $movieTitle = $this->review->movie_title
            ?? $this->review->title
            ?? '映画';

        return [
            // ★ いいね通知であることを明確にする
            'type' => 'like',

            // いいねをしたユーザー
            'user_id' => $this->sender->id,
            'user_name' => $this->sender->name,
            'user_nickname' => $this->sender->name,

            // 映画情報
            'movie_id' => $this->review->movie_id
                ?? $this->review->tmdb_id
                ?? null,

            'movie_title' => $movieTitle,

            // レビュー情報
            'review_id' => $this->review->getKey(),

            // 表示用メッセージ
            'message' => 'に「いいね！」しました。',
        ];
    }
}