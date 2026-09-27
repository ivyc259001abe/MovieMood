<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'review_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        // レビュー側の主キーが review_id の場合は第二引数に 'review_id' を指定
        return $this->belongsTo(Review::class, 'review_id', 'review_id');
    }
}