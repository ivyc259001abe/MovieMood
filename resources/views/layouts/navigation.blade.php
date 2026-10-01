<nav x-data="{ open: false, notifOpen: false }" class="bg-black/90 border-b border-gray-800 relative z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- 左側：ロゴ & サブコピー -->
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="text-xl font-black tracking-widest text-amber-500 no-underline">
                    MovieMood
                </a>
                <span class="text-xs text-gray-400 hidden md:inline">
                    〜 あなたの「今の気分」が、次に観る映画を決める。〜
                </span>
            </div>

            <!-- 右側：1枚目通りの横並びエリア（ベル / ユーザー名 / コミュニティ / ログアウト） -->
            <div class="hidden sm:flex sm:items-center space-x-5">

                <!-- 🔔 1. 通知ベル -->
                <div class="relative" x-data="{ notifOpen: false }">
                    <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false"
                        class="relative p-2 text-amber-500 hover:text-amber-400 focus:outline-none transition">
                        <i class="fa-solid fa-bell text-lg"></i>
                        @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-rose-500 rounded-full animate-ping"></span>
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-rose-500 rounded-full"></span>
                        @endif
                    </button>

                    <!-- 通知ドロップダウン -->
                    <div x-show="notifOpen" x-transition
                        class="absolute right-0 mt-2 w-80 bg-[#121824] border border-gray-800 rounded-2xl shadow-2xl overflow-hidden z-50"
                        style="display: none;">
                        <div class="p-3 border-b border-gray-800 flex items-center justify-between bg-[#0d1117]">
                            <span class="text-xs font-bold text-gray-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-bell text-amber-500"></i> お知らせ
                            </span>
                            <span
                                class="text-[10px] bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded-full font-extrabold">
                                {{ Auth::check() ? Auth::user()->unreadNotifications->count() : 0 }}件の未読
                            </span>
                        </div>

                        <div class="max-h-64 overflow-y-auto divide-y divide-gray-800/60">
                            @if(Auth::check())
                                @forelse(Auth::user()->notifications as $notification)
                                    <div class="p-3 hover:bg-gray-800/80 transition text-left">
                                        <p class="text-xs text-gray-200 leading-snug m-0">
                                            {{ $notification->data['message'] ?? '反応がありました' }}
                                        </p>
                                        <span class="text-[10px] text-gray-500 block mt-1">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-xs text-gray-500">
                                        お知らせはありません
                                    </div>
                                @endforelse
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 👤 2. ユーザーアイコン ＋ 名前（枠付きピル型ボタン） -->
                <a href="{{ route('mypage') }}"
                    class="inline-flex items-center gap-2 px-3 py-1 bg-gray-900/90 border border-amber-500/50 rounded-full text-xs font-bold text-gray-200 hover:border-amber-400 transition no-underline">
                    <div
                        class="w-6 h-6 rounded-full overflow-hidden bg-gray-800 shrink-0 border border-amber-400/30 flex items-center justify-center">
                        @if(Auth::check() && Auth::user()->avatar)
                            <img src="{{ asset(Auth::user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-user text-[10px] text-gray-400"></i>
                        @endif
                    </div>
                    <span>{{ Auth::check() ? (Auth::user()->nickname ?? Auth::user()->name) : 'ゲスト' }}</span>
                </a>

                <!-- 👥 3. コミュニティ -->
                @if(\Illuminate\Support\Facades\Route::has('community.index'))
                    <a href="{{ route('community.index') }}"
                        class="flex items-center gap-1.5 text-xs font-bold text-amber-500 hover:text-amber-400 transition no-underline">
                        <i class="fa-solid fa-users text-amber-500"></i>
                        <span>コミュニティ</span>
                    </a>
                @endif

                <!-- 🚪 4. ログアウト -->
                <form method="POST" action="{{ route('logout') }}" class="m-0 flex items-center">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-1.5 text-xs font-bold text-rose-500 hover:text-rose-400 transition bg-transparent border-0 cursor-pointer p-0">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>ログアウト</span>
                    </button>
                </form>

            </div>

            <!-- スマホ用ハンバーガーボタン -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 text-gray-400 hover:text-amber-500 focus:outline-none">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
            </div>

        </div>
    </div>
</nav>