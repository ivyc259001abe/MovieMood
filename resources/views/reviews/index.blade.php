<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMood - レビュー一覧</title>

    <!-- 1. Vite（Tailwind CSS & JS）の読み込み -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- 2. FontAwesome (アイコン用 CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- 3. Alpine.js 初期表示ちらつき防止 (x-cloak) -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#0b0e14] text-gray-100 min-h-screen">

    <!-- ナビゲーションバー -->
    <nav x-data="{ open: false, showNotif: false }"
        class="bg-[#121824] border-b border-gray-800 text-gray-100 sticky top-0 z-50 w-full">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">

                <!-- 1. 左側：ロゴ＆キャッチコピー -->
                <div class="flex items-center min-w-0">
                    <a href="{{ route('home') }}"
                        class="flex items-center gap-2 text-amber-500 font-extrabold text-base sm:text-lg tracking-wider shrink-0">
                        <i class="fa-solid fa-film text-amber-500 text-base sm:text-lg"></i>
                        <span class="truncate">MovieMood</span>
                    </a>
                </div>

                <!-- 2. 右側（PC表示）：通知、ユーザー dropdown、コミュニティ -->
                <div class="hidden sm:flex sm:items-center sm:gap-4">

                    <!-- 🔔 通知アイコン ＆ ポップアップメニュー -->
                    <div class="relative">
                        <button @click="showNotif = !showNotif" type="button"
                            class="text-gray-400 hover:text-amber-400 transition p-1.5 relative cursor-pointer">
                            <i class="fa-solid fa-bell text-base"></i>
                            @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1 right-1 w-2 h-2 bg-amber-500 rounded-full"></span>
                            @endif
                        </button>

                        <!-- 通知ポップアップ画面 -->
                        <div x-show="showNotif" @click.outside="showNotif = false" x-cloak
                            class="absolute right-0 mt-2 w-80 sm:w-96 bg-[#121824] border border-gray-700 rounded-2xl shadow-2xl z-50 overflow-hidden">

                            <!-- ヘッダー -->
                            <div class="p-3 bg-[#1a2332] border-b border-gray-800 flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-200 flex items-center gap-1.5">
                                    <i class="fa-solid fa-bell text-amber-500"></i> お知らせ
                                </span>
                                <span
                                    class="text-[10px] bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded-full font-bold">
                                    {{ Auth::check() ? Auth::user()->unreadNotifications->count() : 0 }}件の未読
                                </span>
                            </div>

                            <!-- 通知リスト -->
                            <div class="max-h-80 overflow-y-auto divide-y divide-gray-800/80">
                                @if(Auth::check())
                                    @forelse(Auth::user()->notifications as $notification)
                                        <div class="p-3 text-xs hover:bg-[#1a2332] transition space-y-1">
                                            <p class="text-gray-300 leading-snug">
                                                <span class="font-bold text-white">
                                                    {{ $notification->data['user_name'] ?? $notification->data['commenter_name'] ?? 'ユーザー' }}
                                                </span>
                                                さんがあなたの

                                                <a href="{{ $notification->data['url'] ?? '#' }}"
                                                    class="text-amber-400 font-bold hover:underline mx-0.5">
                                                    {{ $notification->data['movie_title'] ?? $notification->data['title'] ?? '映画' }}
                                                </a>

                                                のレビューにコメントしました。
                                            </p>
                                            <span class="text-[10px] text-gray-500 block">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    @empty
                                        <div class="p-6 text-center text-xs text-gray-500">
                                            お知らせはありません
                                        </div>
                                    @endforelse
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- コミュニティ -->
                    <a href="{{ Route::has('community.index') ? route('community.index') : route('home') }}"
                        class="text-xs font-bold text-gray-300 hover:text-white flex items-center gap-1.5 bg-[#1a2332] px-3 py-1.5 rounded-full border border-gray-800 transition">
                        <i class="fa-solid fa-users text-amber-500"></i>
                        <span>コミュニティ</span>
                    </a>

                    <!-- ユーザーメニュー (Dropdown) -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-1.5 border border-gray-700 text-xs font-medium rounded-full text-gray-300 bg-[#1a2332] hover:text-white hover:border-gray-600 focus:outline-none transition ease-in-out duration-150">
                                <span
                                    class="truncate max-w-[120px]">{{ Auth::user()->name ?? Auth::user()->nickname ?? 'ユーザー' }}</span>
                                <i class="fa-solid fa-chevron-down ms-1.5 text-[10px] text-gray-400"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="text-xs">
                                {{ __('プロフィール編集') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-xs text-rose-400 hover:text-rose-300">
                                    {{ __('ログアウト') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- 3. 右側（スマホ表示）：三本線ハンバーガーボタン -->
                <div class="flex items-center sm:hidden">
                    <button @click="open = ! open" type="button" aria-label="メニューを開く"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-amber-500 hover:text-amber-400 hover:bg-gray-800/80 focus:outline-none transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- 4. スマホ用展開メニュー -->
        <div :class="{'block': open, 'hidden': ! open}"
            class="hidden sm:hidden bg-[#0e131f] border-t border-gray-800 px-4 pt-3 pb-4 space-y-3">

            <!-- ユーザー情報表示 -->
            @if(Auth::check())
                <div class="flex items-center gap-3 pb-2 border-b border-gray-800">
                    <div
                        class="w-8 h-8 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center font-bold text-xs shrink-0">
                        {{ mb_substr(Auth::user()->name ?? Auth::user()->nickname ?? '匿', 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-xs text-amber-400 truncate">
                            {{ Auth::user()->name ?? Auth::user()->nickname ?? 'ユーザー' }}
                        </div>
                        <div class="font-medium text-[10px] text-gray-500 truncate">{{ Auth::user()->email ?? '' }}</div>
                    </div>
                </div>
            @endif

            <!-- メニューリンク一覧 -->
            <div class="space-y-1">
                <a href="{{ route('home') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-gray-200 hover:bg-gray-800/80 hover:text-amber-400 transition">
                    <i class="fa-solid fa-house text-amber-500 w-4 text-center"></i>
                    <span>ホーム</span>
                </a>

                <a href="{{ Route::has('community.index') ? route('community.index') : route('home') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-gray-200 hover:bg-gray-800/80 hover:text-amber-400 transition">
                    <i class="fa-solid fa-users text-amber-500 w-4 text-center"></i>
                    <span>コミュニティ</span>
                </a>

                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-gray-200 hover:bg-gray-800/80 hover:text-amber-400 transition">
                    <i class="fa-solid fa-user-pen text-gray-400 w-4 text-center"></i>
                    <span>プロフィール編集</span>
                </a>
            </div>

            <!-- ログアウトボタン -->
            <div class="pt-2 border-t border-gray-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-3 py-2 rounded-lg text-xs font-bold text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition flex items-center gap-2.5 cursor-pointer">
                        <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                        <span>ログアウト</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- メインコンテンツ領域 -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ status: 'after' }">
        <h1 class="text-xl font-bold text-amber-400 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-comments"></i> レビュー一覧
        </h1>

        <!-- 🏷️ タグ選択・フィルターセクション -->
        <div class="bg-[#121824] border border-gray-800 rounded-2xl p-5 mb-8 shadow-xl">
            <!-- 鑑賞前 / 鑑賞後 切替スイッチ -->
            <div class="flex gap-2 mb-4">
                <button type="button" @click="status = 'after'"
                    :class="status === 'after' ? 'bg-amber-500 text-gray-900 font-bold' : 'bg-gray-800 text-gray-400 hover:text-gray-200'"
                    class="px-4 py-1.5 rounded-full text-xs transition-all duration-200">
                    <i class="fa-solid fa-circle-check mr-1"></i> 鑑賞後タグ
                </button>
                <button type="button" @click="status = 'before'"
                    :class="status === 'before' ? 'bg-amber-500 text-gray-900 font-bold' : 'bg-gray-800 text-gray-400 hover:text-gray-200'"
                    class="px-4 py-1.5 rounded-full text-xs transition-all duration-200">
                    <i class="fa-solid fa-hourglass-half mr-1"></i> 観る前タグ
                </button>
            </div>

            <!-- 鑑賞後のタグ一覧 (4つ) -->
            <div x-show="status === 'after'" class="flex flex-wrap gap-2">
                @php
                    $afterTags = ['号泣', 'スカッと', 'ハラハラ', 'キュン'];
                @endphp
                @foreach($afterTags as $tag)
                    <span
                        class="inline-flex items-center gap-1 bg-[#1a2332] text-amber-400 border border-amber-500/30 px-3 py-1 rounded-full text-xs font-medium cursor-pointer hover:bg-amber-500/20 transition">
                        #{{ $tag }}
                    </span>
                @endforeach
            </div>

            <!-- 観る前のタグ一覧 (4つ) -->
            <div x-show="status === 'before'" x-cloak class="flex flex-wrap gap-2">
                @php
                    $beforeTags = ['号泣するかも', 'スカッとするかも', 'ハラハラするかも', 'キュンとするかも'];
                @endphp
                @foreach($beforeTags as $tag)
                    <span
                        class="inline-flex items-center gap-1 bg-[#1a2332] text-sky-400 border border-sky-500/30 px-3 py-1 rounded-full text-xs font-medium cursor-pointer hover:bg-sky-500/20 transition">
                        #{{ $tag }}
                    </span>
                @endforeach
            </div>
        </div>

        <!-- 横2列レイアウト (md:grid-cols-2) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- 横2列レイアウト (md:grid-cols-2) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($reviews ?? [] as $review)
                    <div
                        class="bg-[#121824] border border-gray-800 rounded-xl p-4 shadow-lg hover:border-gray-700 transition">

                        <!-- ⭕ ユーザー情報（アイコン ＋ 名前 ＋ 評価） -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <!-- ⭕ 丸いアイコン部分 -->
                                <div
                                    class="w-8 h-8 rounded-full overflow-hidden flex items-center justify-center shrink-0 border border-amber-500/30 bg-[#1a2332]">
                                    @php
                                        $user = $review->user ?? null;
                                        $avatar = $user->avatar ?? $user->profile_image ?? $user->icon_image ?? $user->avatar_path ?? null;
                                        $userName = $user->nickname ?? $user->name ?? '匿名ユーザー';
                                    @endphp

                                    @if($avatar)
                                        <!-- 画像が登録されている場合 -->
                                        <img src="{{ Str::startsWith($avatar, 'http') ? $avatar : asset('storage/' . $avatar) }}"
                                            alt="{{ $userName }}" class="w-full h-full object-cover">
                                    @else
                                        <!-- 画像がない場合のフォールバック（名前の頭文字） -->
                                        <span class="text-amber-400 font-bold text-xs">
                                            {{ mb_substr($userName, 0, 1) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- ユーザー名 -->
                                <span class="font-bold text-xs text-gray-200 truncate">
                                    {{ $userName }}
                                </span>
                            </div>

                            <!-- 評価（★） -->
                            <span class="text-amber-400 font-bold text-xs shrink-0">
                                ★ {{ sprintf('%.1f', $review->rating ?? 0) }}
                            </span>
                        </div>

                        <!-- 投稿についた感情タグを表示 -->
                        @if(isset($review->tag))
                            <div class="mb-2">
                                <span
                                    class="inline-block bg-amber-500/10 text-amber-400 text-[11px] px-2 py-0.5 rounded-full border border-amber-500/20 font-medium">
                                    #{{ $review->tag }}
                                </span>
                            </div>
                        @endif

                        <!-- レビューコメント -->
                        <p class="text-xs text-gray-300 leading-relaxed mb-3">
                            {{ $review->comment ?? $review->content ?? '' }}
                        </p>

                        <!-- 投稿時間 -->
                        <div class="text-[10px] text-gray-500 text-right">
                            {{ isset($review->created_at) ? $review->created_at->diffForHumans() : '' }}
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full bg-[#121824] border border-gray-800 rounded-xl p-8 text-center text-xs text-gray-400">
                        まだレビューが投稿されていません。
                    </div>
                @endforelse
            </div>
    </main>

</body>

</html>