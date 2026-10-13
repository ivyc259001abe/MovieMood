<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Watchlist;
use App\Models\Like;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * マイページ表示処理
     */
    public function show(): View
    {
        $user = auth()->user();

        // ウォッチリストを1ページ6件で取得
        $watchlist = Watchlist::where('user_id', $user->id)
            ->latest()
            ->paginate(6);

        // 評価値を補完
        $watchlist->getCollection()->transform(function ($item) {
            if (!isset($item->vote_average) && !isset($item->rating)) {
                $movieId = $item->movie_id
                    ?? $item->tmdb_id
                    ?? $item->id;

                $avgRating = Review::where('movie_id', $movieId)
                    ->avg('rating');

                $item->vote_average = $avgRating
                    ? round($avgRating, 1)
                    : null;
            }

            return $item;
        });

        // 自分のレビュー
        $myReviews = Review::where('user_id', $user->id)
            ->latest()
            ->get();

        // いいねしたレビュー
        $likedReviewIds = Like::where('user_id', $user->id)
            ->pluck('review_id');

        $likedReviews = Review::whereIn('id', $likedReviewIds)
            ->latest()
            ->get();

        return view('mypage', compact(
            'user',
            'watchlist',
            'myReviews',
            'likedReviews'
        ));
    }

    /**
     * プロフィール編集画面表示
     */
    public function edit(Request $request): View
    {
        $popularMovies = function_exists('getPopularMovies')
            ? getPopularMovies()
            : [];

        return view('profile.edit', [
            'user' => $request->user(),
            'popularMovies' => $popularMovies,
        ]);
    }

    /**
     * プロフィール更新
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 入力内容の検証
        $validated = $request->validate([
            'nickname' => ['required', 'string', 'max:255'],

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            // 画像は任意。最大2MB
            'avatar' => [
                'nullable',
                'image',
                'max:2048',
            ],

            'remove_avatar' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
         * 既存のローカル画像を削除するための処理。
         * uploads フォルダ内のファイルだけを対象にする。
         */
        $deleteOldAvatar = function ($path) {
            if (!is_string($path) || $path === '') {
                return;
            }

            // 外部URLは削除対象にしない
            if (preg_match('#^https?://#i', $path)) {
                return;
            }

            $relativePath = ltrim($path, '/');

            // uploads/ 以外のファイルは削除しない
            if (!str_starts_with($relativePath, 'uploads/')) {
                return;
            }

            $fullPath = public_path($relativePath);

            if (File::exists($fullPath) && File::isFile($fullPath)) {
                File::delete($fullPath);
            }
        };

        // ニックネームを更新
        if (Schema::hasColumn('users', 'nickname')) {
            $user->nickname = $validated['nickname'];
        }

        // 既存仕様との互換性のため name も更新
        $user->name = $validated['nickname'];

        // メールアドレスが入力された場合のみ更新
        if ($request->filled('email')) {
            $user->email = $validated['email'];

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
        }

        /*
         * 画像の変更処理
         *
         * 新しい画像が選択された場合は新しい画像を優先する。
         * 画像を選択していない場合は既存画像を維持する。
         */
        $avatarFile = $request->file('avatar');

        if ($avatarFile) {
            // 保存前に既存画像のパスを取得
            $oldAvatarPaths = [
                $user->avatar ?? null,
            ];

            if (Schema::hasColumn('users', 'icon')) {
                $oldAvatarPaths[] = $user->icon;
            }

            if (Schema::hasColumn('users', 'icon_path')) {
                $oldAvatarPaths[] = $user->icon_path;
            }

            // 保存先を用意
            File::ensureDirectoryExists(public_path('uploads'));

            // ファイル名の重複を防ぐ
            $filename = 'icon_'
                . uniqid('', true)
                . '.'
                . $avatarFile->extension();

            // public/uploads に保存
            $avatarFile->move(
                public_path('uploads'),
                $filename
            );

            $avatarPath = 'uploads/' . $filename;

            // avatar カラムを更新
            $user->avatar = $avatarPath;

            // 旧仕様のカラムが存在する場合はそちらも更新
            if (Schema::hasColumn('users', 'icon')) {
                $user->icon = $avatarPath;
            }

            if (Schema::hasColumn('users', 'icon_path')) {
                $user->icon_path = $avatarPath;
            }

            // 画像パスをセッションにも保存
            session(['user_icon' => $avatarPath]);

            $user->save();

            // 新しい画像を保存した後、古い画像を削除
            foreach (array_unique($oldAvatarPaths) as $oldPath) {
                if ($oldPath !== $avatarPath) {
                    $deleteOldAvatar($oldPath);
                }
            }
        } elseif ($request->boolean('remove_avatar')) {
            /*
             * 新しい画像が選択されておらず、
             * 削除チェックが入っている場合だけ削除する。
             */
            $oldAvatarPaths = [
                $user->avatar ?? null,
            ];

            if (Schema::hasColumn('users', 'icon')) {
                $oldAvatarPaths[] = $user->icon;
            }

            if (Schema::hasColumn('users', 'icon_path')) {
                $oldAvatarPaths[] = $user->icon_path;
            }

            // データベース上の画像情報をクリア
            $user->avatar = null;

            if (Schema::hasColumn('users', 'icon')) {
                $user->icon = null;
            }

            if (Schema::hasColumn('users', 'icon_path')) {
                $user->icon_path = null;
            }

            // セッションに残っている画像パスも削除
            session()->forget('user_icon');

            $user->save();

            // 古いローカル画像ファイルを削除
            foreach (array_unique($oldAvatarPaths) as $oldPath) {
                $deleteOldAvatar($oldPath);
            }
        }

        // パスワードが入力された場合のみ変更
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return Redirect::route('mypage')
            ->with('status', 'プロフィール情報を更新しました。');
    }

    /**
     * アカウント削除
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 削除対象となる画像パスを取得
        $avatarPaths = [
            $user->avatar ?? null,
        ];

        if (Schema::hasColumn('users', 'icon')) {
            $avatarPaths[] = $user->icon;
        }

        if (Schema::hasColumn('users', 'icon_path')) {
            $avatarPaths[] = $user->icon_path;
        }

        Auth::logout();

        // uploads フォルダ内のローカル画像だけ削除
        foreach (array_unique($avatarPaths) as $path) {
            if (!is_string($path) || $path === '') {
                continue;
            }

            if (preg_match('#^https?://#i', $path)) {
                continue;
            }

            $relativePath = ltrim($path, '/');

            if (!str_starts_with($relativePath, 'uploads/')) {
                continue;
            }

            $fullPath = public_path($relativePath);

            if (File::exists($fullPath) && File::isFile($fullPath)) {
                File::delete($fullPath);
            }
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
