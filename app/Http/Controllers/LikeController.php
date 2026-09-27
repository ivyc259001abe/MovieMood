<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Review $review)
    {
        $user = Auth::user();

        // 該当するいいねの検索条件
        $query = Like::where('user_id', $user->id)
            ->where('review_id', $review->review_id);

        if ($query->exists()) {
            // すでにいいねしていればクエリ経由で直接削除
            $query->delete();
        } else {
            // まだしていなければ登録
            Like::create([
                'user_id' => $user->id,
                'review_id' => $review->review_id,
            ]);
        }

        return back();
    }
}