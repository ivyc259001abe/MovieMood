<nav x-data="{ open: false }"
    class="bg-[#121824] border-b border-gray-800 text-gray-100 sticky top-0 z-50 w-full overflow-hidden">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14 sm:h-16">

            <!-- 1. 左側：ロゴ＆キャッチコピー（スマホ時はコンパクト表示） -->
            <div class="flex items-center min-w-0">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-2 text-amber-500 font-extrabold text-base sm:text-lg tracking-wider shrink-0">
                    <i class="fa-solid fa-film text-amber-500 text-base sm:text-lg"></i>
                    <span class="truncate">MovieMood</span>
                </a>
            </div>

            <!-- 2. 右側（PC表示）：通知、ユーザー dropdown、コミュニティ -->
            <div class="hidden sm:flex sm:items-center sm:gap-4">
                <!-- 通知アイコン -->
                <button type="button" class="text-gray-400 hover:text-amber-400 transition p-1.5 relative">
                    <i class="fa-solid fa-bell text-base"></i>
                </button>

                <!-- コミュニティ -->
                <a href="{{ route('dashboard') }}"
                    class="text-xs font-bold text-gray-300 hover:text-white flex items-center gap-1.5 bg-[#1a2332] px-3 py-1.5 rounded-full border border-gray-800">
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

            <!-- 3. 右側（スマホ表示）：三本線（ハンバーガーボタン）のみ表示 -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" type="button" aria-label="メニューを開く"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-amber-500 hover:text-amber-400 hover:bg-gray-800/80 focus:outline-none transition duration-150 ease-in-out">
                    <!-- 三本線アイコン -->
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
                {{ mb_substr(Auth::user()->name ?? Auth::user()->nickname ?? '匿', 0, 1) }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="font-bold text-xs text-amber-400 truncate">
                    {{ Auth::user()->name ?? Auth::user()->nickname ?? 'ユーザー' }}</div>
                <div class="font-medium text-[10px] text-gray-500 truncate">{{ Auth::user()->email ?? '' }}</div>
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