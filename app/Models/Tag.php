<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    // タグは複数のレビューに紐づく（多対多）
    public function reviews(): BelongsToMany
    {
        return $this->belongsToMany(Review::class, 'review_tags');
    }
}