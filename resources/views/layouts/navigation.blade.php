<nav x-data="{ open: false }" class="bg-[#121824] border-b border-gray-800 text-gray-100 sticky top-0 z-50 w-full">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14 sm:h-16">

            <!-- 1. 左側：ロゴ＆ブランド名 -->
            <div class="flex items-center min-w-0">
                <a href="{{ \Illuminate\Support\Facades\Route::has('home') ? route('home') : url('/') }}"
                    class="flex items-center gap-2 text-amber-500 font-extrabold text-base sm:text-lg tracking-wider shrink-0 no-underline">
                    <i class="fa-solid fa-film text-amber-500 text-base sm:text-lg"></i>
                    <span class="truncate">MovieMood</span>
                </a>
            </div>

            <!-- 2. 右側（PC表示）：通知、コミュニティ、ユーザー dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-4">

                <!-- 🔔 お知らせドロップダウン -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="relative p-2 text-gray-400 hover:text-white transition cursor-pointer">
                        <span class="text-xl">🔔</span>
                        @php
                            $unreadCount = auth()->user()->unreadNotifications->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span
                                class="absolute top-1 right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <!-- ドロップダウンメニュー -->
                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute right-0 mt-2 w-80 sm:w-96 bg-[#111827] border border-gray-800 rounded-2xl shadow-2xl z-50 overflow-hidden">

                        <div class="p-3 bg-gray-900/80 border-b border-gray-800 flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-200 flex items-center space-x-1.5">
                                <span>🔔</span>
                                <span>お知らせ</span>
                            </span>
                            <span
                                class="text-xs bg-amber-500/10 text-amber-400 border border-amber-500/30 px-2 py-0.5 rounded-full font-semibold">
                                {{ $unreadCount }}件の未読
                            </span>
                        </div>

                        <div class="max-h-80 overflow-y-auto divide-y divide-gray-800/60 custom-scrollbar">
                            @forelse(Auth::user()->notifications->take(10) as $notification)
                                @php
                                    $data = $notification->data ?? [];

                                    // 1. ユーザー名の多角的な全探索（どんなキー名でも取得）
                                    $userName = $data['user_nickname']
                                        ?? $data['sender_nickname']
                                        ?? $data['user_name']
                                        ?? $data['sender_name']
                                        ?? $data['nickname']
                                        ?? $data['name']
                                        ?? $data['user']['nickname']
                                        ?? $data['user']['name']
                                        ?? $data['user']['username']
                                        ?? null;

                                    // 2. 映画タイトルの全探索
                                    $movieTitle = $data['movie_title']
                                        ?? $data['title']
                                        ?? $data['movie']['title']
                                        ?? $data['movie_name']
                                        ?? null;

                                    // 3. メッセージ全体の判定（すでに完成テキストが入っている場合の抽出）
                                    $rawMsg = $data['message'] ?? '';

                                    // 4. 通知タイプの判別
                                    $notificationType = strtolower($data['type'] ?? $notification->type ?? '');
                                    $isComment = str_contains($notificationType, 'comment') || str_contains($rawMsg, 'コメント');

                                    // 5. アクション文言
                                    $actionText = $isComment ? 'コメントしました' : '「いいね！」しました';

                                    // 6. 遷移先URLの生成
                                    $movieId = $data['movie_id'] ?? $data['tmdb_id'] ?? $data['movie']['id'] ?? null;
                                    $reviewId = $data['review_id'] ?? null;
                                    $targetUrl = '#';
                                    if (!empty($data['url']) && $data['url'] !== '#') {
                                        $targetUrl = $data['url'];
                                    } elseif ($movieId) {
                                        $targetUrl = \Illuminate\Support\Facades\Route::has('movies.show') ? route('movies.show', $movieId) : url('/movies/' . $movieId);
                                        if ($reviewId) {
                                            $targetUrl .= '#review-' . $reviewId;
                                        }
                                    }
                                    $isUnread = is_null($notification->read_at);
                                @endphp

                                <a href="{{ $targetUrl }}"
                                    class="group block relative rounded-xl transition duration-150 overflow-hidden border border-transparent {{ $isUnread ? 'bg-[#1a2332]/90' : 'bg-transparent hover:bg-gray-800/60' }} p-3 no-underline">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 mt-0.5 border {{ $isComment ? 'bg-amber-500/20 border-amber-500/50 text-amber-400' : 'bg-red-500/20 border-red-500/50 text-red-500' }}">
                                            @if($isComment)
                                                <i class="fa-solid fa-comment text-amber-400 text-xs"></i>
                                            @else
                                                <i class="fa-solid fa-heart text-red-500 text-xs"></i>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0 text-xs text-gray-300 leading-relaxed space-y-1">
                                            <div>
                                                @if($userName)
                                                    <span class="font-bold text-white">{{ $userName }}</span> さんが
                                                @else
                                                    <span class="font-bold text-white">映画ファン</span> さんが
                                                @endif

                                                @if(!empty($movieTitle))
                                                    『<span class="font-bold text-amber-400">{{ $movieTitle }}</span>』のレビューに
                                                @else
                                                    レビューに
                                                @endif

                                                {{ $actionText }}
                                            </div>
                                            <div class="flex items-center justify-between text-[10px] text-gray-500 pt-1">
                                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                                                @if($isUnread)
                                                    <span
                                                        class="w-2 h-2 rounded-full bg-amber-500 inline-block shadow-[0_0_6px_rgba(245,158,11,0.8)]"></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <p class="text-center text-xs text-gray-500 py-6 m-0">新着のお知らせはありません</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- コミュニティ -->
                @if(\Illuminate\Support\Facades\Route::has('community.index'))
                    <a href="{{ route('community.index') }}"
                        class="text-xs font-bold text-gray-300 hover:text-white flex items-center gap-1.5 bg-[#1a2332] px-3 py-1.5 rounded-full border border-gray-800 transition no-underline">
                        <i class="fa-solid fa-users text-amber-500"></i>
                        <span>コミュニティ</span>
                    </a>
                @endif

                <!-- ユーザーメニュー (Dropdown) -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 px-3 py-1.5 border border-amber-500/40 text-xs font-medium rounded-full text-gray-200 bg-[#1a2332] hover:border-amber-400 focus:outline-none transition cursor-pointer">
                            <div
                                class="w-6 h-6 rounded-full overflow-hidden bg-gray-800 flex items-center justify-center shrink-0 border border-amber-500">
                                @php
                                    $avatar = Auth::user()->avatar ?? Auth::user()->icon ?? Auth::user()->icon_path ?? null;
                                @endphp
                                @if($avatar)
                                    <img src="{{ Str::startsWith($avatar, 'http') ? $avatar : asset($avatar) }}" alt="user"
                                        class="w-full h-full object-cover">
                                @else
                                    <span class="text-amber-400 font-bold text-[10px]">
                                        {{ mb_substr(Auth::user()->nickname ?? Auth::user()->name ?? 'U', 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <span
                                class="truncate max-w-[120px] font-bold">{{ Auth::user()->nickname ?? Auth::user()->name ?? 'ユーザー' }}</span>
                            <i class="fa-solid fa-chevron-down ms-1 text-[10px] text-gray-400"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if(\Illuminate\Support\Facades\Route::has('mypage'))
                            <x-dropdown-link :href="route('mypage')" class="text-xs">
                                {{ __('マイページ') }}
                            </x-dropdown-link>
                        @endif
                        @if(\Illuminate\Support\Facades\Route::has('profile.edit'))
                            <x-dropdown-link :href="route('profile.edit')" class="text-xs">
                                {{ __('プロフィール編集') }}
                            </x-dropdown-link>
                        @endif

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

            <!-- 3. 右側（スマホ表示）：三本線（ハンバーガーボタン） -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" type="button" aria-label="メニューを開く"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-amber-500 hover:text-amber-400 hover:bg-gray-800/80 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- 4. スマホ用展開メニュー（三本線タップ時に表示） -->
    <div :class="{'block': open, 'hidden': ! open}"
        class="hidden sm:hidden bg-[#0e131f] border-t border-gray-800 px-4 pt-3 pb-4 space-y-3">

        <!-- ユーザー情報表示 -->
        <div class="flex items-center gap-3 pb-2 border-b border-gray-800">
            <div
                class="w-8 h-8 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center font-bold text-xs shrink-0 overflow-hidden">
                @php
                    $avatar = Auth::user()->avatar ?? Auth::user()->icon ?? null;
                @endphp
                @if($avatar)
                    <img src="{{ Str::startsWith($avatar, 'http') ? $avatar : asset($avatar) }}"
                        class="w-full h-full object-cover">
                @else
                    {{ mb_substr(Auth::user()->nickname ?? Auth::user()->name ?? '匿', 0, 1) }}
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <div class="font-bold text-xs text-amber-400 truncate">
                    {{ Auth::user()->nickname ?? Auth::user()->name ?? 'ユーザー' }}
                </div>
                <div class="font-medium text-[10px] text-gray-500 truncate">
                    {{ Auth::user()->email ?? '' }}
                </div>
            </div>
        </div>

        <!-- メニューリンク一覧 -->
        <div class="space-y-1">
            <a href="{{ \Illuminate\Support\Facades\Route::has('home') ? route('home') : url('/') }}"
                class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-gray-200 hover:bg-gray-800/80 hover:text-amber-400 transition no-underline">
                <i class="fa-solid fa-house text-amber-500 w-4 text-center"></i>
                <span>ホーム</span>
            </a>

            @if(\Illuminate\Support\Facades\Route::has('mypage'))
                <a href="{{ route('mypage') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-gray-200 hover:bg-gray-800/80 hover:text-amber-400 transition no-underline">
                    <i class="fa-solid fa-user text-amber-500 w-4 text-center"></i>
                    <span>マイページ</span>
                </a>
            @endif

            @if(\Illuminate\Support\Facades\Route::has('community.index'))
                <a href="{{ route('community.index') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-gray-200 hover:bg-gray-800/80 hover:text-amber-400 transition no-underline">
                    <i class="fa-solid fa-users text-amber-500 w-4 text-center"></i>
                    <span>コミュニティ</span>
                </a>
            @endif

            @if(\Illuminate\Support\Facades\Route::has('profile.edit'))
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-gray-200 hover:bg-gray-800/80 hover:text-amber-400 transition no-underline">
                    <i class="fa-solid fa-user-pen text-gray-400 w-4 text-center"></i>
                    <span>プロフィール編集</span>
                </a>
            @endif
        </div>

        <!-- ログアウトボタン -->
        <div class="pt-2 border-t border-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left px-3 py-2 rounded-lg text-xs font-bold text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition flex items-center gap-2.5 cursor-pointer bg-transparent border-0">
                    <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                    <span>ログアウト</span>
                </button>
            </form>
        </div>
    </div>
</nav>