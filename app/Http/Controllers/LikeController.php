<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Like;
use App\Notifications\ReviewLiked;
use App\Notifications\ReviewCommented;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Review $review)
    {
        $user = Auth::user();

        // Reviewモデルの主キー（review_id または id）を自動取得
        $reviewId = $review->getKey();

        $like = Like::where('user_id', $user->id)
            ->where('review_id', $reviewId)
            ->first();

        if ($like) {
            // すでに「いいね」していれば解除
            $like->delete();
            $liked = false;
        } else {
            // まだ「いいね」していなければ新規登録
            Like::create([
                'user_id' => $user->id,
                'review_id' => $reviewId,
            ]);
            $liked = true;

            // 🌟 ここを追加！：レビュー投稿者（自分以外）に通知を送信
            if ($review->user_id !== $user->id) {
                $review->user->notify(new ReviewLiked($user, $review));
            }
        }

        return back();
    }
}