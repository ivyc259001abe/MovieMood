<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReviewCommented extends Notification
{
    use Queueable;

    protected $sender;
    protected $review;
    protected $comment;

    /**
     * コメント通知を作成
     */
    public function __construct($sender, $review, $comment = null)
    {
        $this->sender = $sender;
        $this->review = $review;
        $this->comment = $comment;
    }

    /**
     * 通知の送信方法
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
            // コメント通知であることを明確にする
            'type' => 'comment',

            // コメントしたユーザー
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

            // コメントID
            'comment_id' => $this->comment?->getKey(),

            // 表示用メッセージ
            'message' => 'コメントしました。',
        ];
    }
}