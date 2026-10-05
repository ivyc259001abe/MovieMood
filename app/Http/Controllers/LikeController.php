<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Like;
use App\Notifications\ReviewLikedNotification; // ← ここを ReviewLikedNotification に修正
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

            // 🌟 レビュー投稿者（自分以外）に通知を送信
            if ($review->user_id !== $user->id && $review->user) {
                // 第1引数にログインユーザー($user)、第2引数にレビュー($review)を正しく渡す
                $review->user->notify(new ReviewLikedNotification($user, $review));
            }
        }

        return back();
    }
}