<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * コミュニティ（感情タイムライン）一覧表示
     */
    public function community(Request $request)
    {
        $query = Review::with(['user', 'comments.user', 'likes']);

        // 気分（mood）による絞り込み
        if ($request->filled('mood')) {
            $query->where('mood', 'like', '%' . $request->mood . '%');
        }

        $reviews = $query->latest()->paginate(10);

        return view('community', compact('reviews'));
    }

    /**
     * レビュー投稿の保存処理
     */
    public function store(Request $request, $movieId)
    {
        // 1. バリデーション
        $request->validate([
            'comment' => 'required|string',
        ]);

        // 2. タグ（moods）の配列を文字列（カンマ区切り）に変換する処理
        $moods = $request->input('moods', $request->input('mood', []));
        if (is_array($moods)) {
            $moodString = implode(',', array_filter($moods));
        } else {
            $moodString = (string) $moods;
        }

        $commentText = $request->input('comment');

        // 3. レビュー作成（content カラムへ投稿内容を保存）
        Review::create([
            'user_id' => Auth::id(),
            'movie_id' => (string) $movieId,
            'rating' => $request->input('rating', 8.0),
            'mood' => $moodString,
            'content' => $commentText, // DBの必須カラム content に値を代入
            'comment' => $commentText, // comment カラムが存在する場合にも備えて両方指定
        ]);

        // 4. 元のページの一覧エリア (#reviews) へリダイレクト
        return redirect()->to(url()->previous() . '#reviews')->with('success', 'レビューを投稿しました！');
    }

    /**
     * 映画ごとのレビュー一覧
     */
    public function index($id)
    {
        $reviews = Review::where('movie_id', (string) $id)->with(['user', 'comments.user'])->latest()->get();
        return view('reviews.index', compact('reviews', 'id'));
    }

    /**
     * コメントの保存処理
     */
    public function storeComment(Request $request, $reviewId)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        // 該当のメソッド（storeComment）内
        $commentText = $request->comment;

        Comment::create([
            'user_id' => Auth::id(),
            'review_id' => $reviewId,
            // 'content' => $commentText,  ← ★この行を消去（削除）してください！
            'comment' => $commentText,
        ]);

        return back()->with('success', 'コメントを投稿しました');
    }

    /**
     * コメントの削除
     */
    public function destroyComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if ($comment->user_id === Auth::id()) {
            $comment->delete();
            return back()->with('success', 'コメントを削除しました');
        }

        return back()->with('error', '削除権限がありません');
    }
}