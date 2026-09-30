<nav x-data="{ open: false, notifOpen: false }" class="bg-black/90 border-b border-gray-800 relative z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="text-xl font-black tracking-widest text-yellow-500">
                        MovieMood
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="url('/')" :active="request()->is('/')">
                        ホーム
                    </x-nav-link>
                    <x-nav-link :href="route('mypage')" :active="request()->routeIs('mypage')">
                        マイページ
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings & Notification Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">

                <!-- 🔔 1. 通知ベルマーク ＆ ドロップダウンメニュー -->
                <div class="relative" x-data="{ notifOpen: false }">
                    <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false"
                        class="relative p-2 text-gray-400 hover:text-amber-400 focus:outline-none transition">
                        <i class="fa-solid fa-bell text-lg"></i>
                        @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-rose-500 rounded-full animate-ping"></span>
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-rose-500 rounded-full"></span>
                        @endif
                    </button>

                    <!-- ドロップダウン本体 -->
                    <div x-show="notifOpen" x-transition
                        class="absolute right-0 mt-2 w-80 bg-[#121824] border border-gray-800 rounded-2xl shadow-2xl overflow-hidden z-50"
                        style="display: none;">
                        <div class="p-3 border-b border-gray-800/80 flex items-center justify-between bg-[#0d1117]">
                            <span class="text-xs font-bold text-gray-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-bell text-amber-500"></i> お知らせ
                            </span>
                            @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                                <span
                                    class="text-[10px] bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded-full font-extrabold">
                                    {{ Auth::user()->unreadNotifications->count() }}件未読
                                </span>
                            @endif
                        </div>

                        <!-- 通知リスト -->
                        <div class="max-h-64 overflow-y-auto divide-y divide-gray-800/60">
                            @if(Auth::check())
                                @forelse(Auth::user()->notifications as $notification)
                                    @php
                                        // IDなどの取得
                                        $movieId = $notification->data['movie_id'] ?? $notification->data['tmdb_id'] ?? null;
                                        $reviewId = $notification->data['review_id'] ?? null;

                                        // URLの構築
                                        $targetUrl = '#';
                                        if (!empty($notification->data['url']) && $notification->data['url'] !== '#') {
                                            $targetUrl = $notification->data['url'];
                                        } elseif ($movieId) {
                                            // Routeの存在確認をしてから生成（無ければフォールバックURL）
                                            if (\Illuminate\Support\Facades\Route::has('reviews.index')) {
                                                $targetUrl = route('reviews.index', $movieId);
                                            } else {
                                                $targetUrl = url('/movies/' . $movieId);
                                            }

                                            if ($reviewId) {
                                                $targetUrl .= '#review-' . $reviewId;
                                            }
                                        }

                                        $senderName = $notification->data['user_name'] ?? $notification->data['user_nickname'] ?? 'ユーザー';
                                    @endphp

                                    {{-- 💡 確実に画面遷移させるために @click に window.location.href を設定 --}}
                                    <a href="{{ $targetUrl }}"
                                        @click="if ('{{ $targetUrl }}' !== '#') { window.location.href = '{{ $targetUrl }}'; }"
                                        class="block w-full p-3 hover:bg-gray-800/80 transition text-left border-b border-gray-800/40 cursor-pointer">
                                        <p class="text-xs text-gray-200 leading-snug">
                                            <span class="font-bold text-amber-400">
                                                {{ $senderName }}
                                            </span>
                                            {{ $notification->data['message'] ?? 'さんから反応がありました' }}
                                        </p>
                                        <span class="text-[10px] text-gray-500 block mt-1">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </a>
                                @empty
                                    <div class="p-4 text-center text-xs text-gray-500">
                                        お知らせはありません
                                    </div>
                                @endforelse
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 👤 2. アカウントメニュー -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-gray-900 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div
                                class="w-8 h-8 rounded-full overflow-hidden bg-gray-800 border border-amber-500 flex items-center justify-center shrink-0">
                                @if(Auth::check() && Auth::user()->avatar)
                                    <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-user text-gray-400 text-xs"></i>
                                @endif
                            </div>
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            アカウント設定
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                ログアウト
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
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

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="url('/')" :active="request()->is('/')">
                ホーム
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('mypage')" :active="request()->routeIs('mypage')">
                マイページ
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-800">
            <div class="px-4 flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-full overflow-hidden bg-gray-800 border border-amber-500 flex items-center justify-center shrink-0">
                    @if(Auth::check() && Auth::user()->avatar)
                        <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-user text-gray-400 text-sm"></i>
                    @endif
                </div>
                <div>
                    <div class="font-medium text-base text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    アカウント設定
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        ログアウト
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>