<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * レビューを投稿したユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * レビューに紐づくコメント一覧
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * レビューに紐づくいいね一覧
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * 指定ユーザーが「いいね」済みかどうかを判定
     */
    public function isLikedBy($user)
    {
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}