<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ウォッチリスト情報（単数形呼び出し対応）
     */
    public function watchlist(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    /**
     * ウォッチリスト情報（複数形呼び出し対応）
     */
    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    /**
     * 投稿したレビュー一覧
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * いいね一覧
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
}