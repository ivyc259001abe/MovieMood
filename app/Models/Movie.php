<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $primaryKey = 'movie_id';

    protected $fillable = [
        'title',
        'release_ym',
        'director',
        'synopsis',
        'poster_url',
    ];

    // 映画は複数のレビューを持つ
    public function reviews()
    {
        return $this->hasMany(Review::class, 'movie_id', 'movie_id');
    }
}