<nav x-data="{ open: false }" class="bg-[#121824] border-b border-gray-800 text-gray-100 sticky top-0 z-50 w-full">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14 sm:h-16">

            <!-- 1. 左側：ロゴ＆ブランド名 -->
            <div class="flex items-center min-w-0">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-2 text-amber-500 font-extrabold text-base sm:text-lg tracking-wider shrink-0">
                    <i class="fa-solid fa-film text-amber-500 text-base sm:text-lg"></i>
                    <span class="truncate">MovieMood</span>
                </a>
            </div>

            <!-- 2. 右側（PC表示）：通知、コミュニティ、ユーザー dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-4">

                <!-- 🔔 お知らせドロップダウン -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-white transition">
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
                        class="absolute right-0 mt-2 w-80 bg-[#111827] border border-gray-800 rounded-2xl shadow-2xl z-50 overflow-hidden">

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

                        <div class="max-h-80 overflow-y-auto divide-y divide-gray-800/60">
                            @forelse (auth()->user()->notifications as $notification)
                                @php
                                    $data = $notification->data;

                                    // 1. 通知を起こしたユーザー名の取得（優先度: sender_name > user_name > nickname > name > '映画ファン'）
                                    $actorName = $data['sender_name']
                                        ?? $data['user_name']
                                        ?? $data['nickname']
                                        ?? $data['name']
                                        ?? '映画ファン';

                                    // 2. 通知の種別判定（'like', 'comment' または type 名で判別）
                                    $type = $data['type'] ?? (str_contains($notification->type, 'Comment') ? 'comment' : 'like');

                                    // 3. 映画タイトルの取得
                                    $movieTitle = $data['movie_title'] ?? $data['title'] ?? '映画';
                                @endphp

                                <a href="{{ $data['url'] ?? '#' }}"
                                    class="block p-3.5 hover:bg-gray-800/50 transition {{ $notification->unread() ? 'bg-indigo-950/20' : '' }}">
                                    <div class="flex items-start space-x-3">

                                        {{-- 🟢 いいね／コメント アイコンの区別表示 --}}
                                        @if($type === 'like')
                                            <div
                                                class="w-9 h-9 rounded-full bg-rose-500/20 border border-rose-500/40 text-rose-400 flex items-center justify-center shrink-0 font-bold text-sm">
                                                ♥
                                            </div>
                                        @else
                                            <div
                                                class="w-9 h-9 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center shrink-0 font-bold text-sm">
                                                💬
                                            </div>
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-gray-200 leading-snug">
                                                <span class="font-bold text-amber-400">{{ $actorName }}</span> さんが
                                                あなたの『<span class="font-semibold text-white">{{ $movieTitle }}</span>』のレビューに

                                                @if($type === 'like')
                                                    <span class="text-rose-400 font-bold">いいね！</span>しました
                                                @else
                                                    <span class="text-amber-400 font-bold">コメント</span>しました
                                                @endif
                                            </p>
                                            <span class="text-[10px] text-gray-400 mt-1 block">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>

                                    </div>
                                </a>
                            @empty
                                <div class="p-6 text-center text-xs text-gray-400">
                                    お知らせはありません
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- コミュニティ -->
                <a href="{{ route('dashboard') }}"
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
                                class="truncate max-w-[120px]">{{ Auth::user()->nickname ?? Auth::user()->name ?? 'ユーザー' }}</span>
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

            <!-- 3. 右側（スマホ表示）：三本線（ハンバーガーボタン） -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" type="button" aria-label="メニューを開く"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-amber-500 hover:text-amber-400 hover:bg-gray-800/80 focus:outline-none transition duration-150 ease-in-out">
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
                class="w-8 h-8 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center font-bold text-xs shrink-0">
                {{ mb_substr(Auth::user()->nickname ?? Auth::user()->name ?? '匿', 0, 1) }}
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
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-gray-200 hover:bg-gray-800/80 hover:text-amber-400 transition">
                <i class="fa-solid fa-house text-amber-500 w-4 text-center"></i>
                <span>ホーム</span>
            </a>

            <a href="{{ route('dashboard') }}"
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
                    class="w-full text-left px-3 py-2 rounded-lg text-xs font-bold text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition flex items-center gap-2.5">
                    <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                    <span>ログアウト</span>
                </button>
            </form>
        </div>
    </div>
</nav>