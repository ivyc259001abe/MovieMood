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
        'rating',
        'comment',
    ];

    // レビューは1つの映画に属する
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'movie_id');
    }

    // レビューは1人のユーザーに属する
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}