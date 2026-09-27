<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'review_id';

    protected $fillable = [
        'user_id',
        'movie_id',
        'movie_title',
        'poster_path',
        'rating',
        'moods',
        'comment',
    ];

    // ユーザーとのリレーション（これが消えていたためエラーになっていました）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // いいねとのリレーション
    public function likes()
    {
        return $this->hasMany(Like::class, 'review_id');
    }

    // ユーザーがすでにいいねしているか判定
    public function isLikedBy($user)
    {
        if (!$user)
            return false;
        return $this->likes->contains('user_id', $user->id);
    }
}