<x-app-layout>
    <div class="py-12 pt-28 bg-black min-h-screen flex flex-col items-center text-white">

        <div
            class="w-full max-w-md px-6 py-8 bg-gray-900 shadow-md rounded-[2.5rem] border border-gray-700 flex flex-col items-center">

            <header class="text-center mb-4">
                <h1 class="text-3xl font-bold text-yellow-500 tracking-wider">MovieMood</h1>
            </header>

            <div class="w-full flex flex-col items-center mb-6">
                <p class="text-yellow-500 font-semibold text-lg mb-3">
                    Welcome {{ Auth::user()->nickname ?? Auth::user()->name }} さん！
                </p>

                <div
                    class="flex items-center space-x-3 bg-gray-800 px-6 py-3 rounded-full border border-gray-700 w-11/12 justify-center">
                    <div
                        class="w-12 h-12 rounded-full bg-white overflow-hidden border-2 border-yellow-500 flex items-center justify-center shadow-md">
                        @if (Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-[10px] text-gray-400">画像なし</span>
                        @endif
                    </div>
                    <span class="text-white font-bold text-lg truncate max-w-[150px]">
                        {{ Auth::user()->nickname ?? Auth::user()->name }}
                    </span>
                </div>
            </div>

            <div class="w-full px-2 mb-6">
                <form method="GET" action="#" class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="h-5 w-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" placeholder="キーワード検索"
                        class="w-full py-2.5 pl-12 pr-4 bg-white text-black font-medium rounded-full focus:outline-none focus:ring-2 focus:ring-yellow-500 border-none shadow-md placeholder-gray-400">
                </form>
            </div>

            <div class="w-full mb-6">
                <div class="flex items-center justify-center space-x-1 mb-4 text-gray-300">
                    <span class="text-lg">▼</span>
                    <span class="font-bold text-sm">今のあなたの「気分」は？</span>
                </div>

                <div class="grid grid-cols-2 gap-3 px-2">
                    <a href="#"
                        class="flex items-center justify-center space-x-1 py-2 bg-white text-black font-bold rounded-full hover:bg-yellow-500 hover:text-black transition shadow-md text-sm">
                        <span>😭</span> <span>#号泣</span>
                    </a>
                    <a href="#"
                        class="flex items-center justify-center space-x-1 py-2 bg-white text-black font-bold rounded-full hover:bg-yellow-500 hover:text-black transition shadow-md text-sm">
                        <span>🤩</span> <span>#スカッと</span>
                    </a>
                    <a href="#"
                        class="flex items-center justify-center space-x-1 py-2 bg-white text-black font-bold rounded-full hover:bg-yellow-500 hover:text-black transition shadow-md text-sm">
                        <span>😱</span> <span>#ハラハラ</span>
                    </a>
                    <a href="#"
                        class="flex items-center justify-center space-x-1 py-2 bg-white text-black font-bold rounded-full hover:bg-yellow-500 hover:text-black transition shadow-md text-sm">
                        <span>💖</span> <span>#キュン</span>
                    </a>
                </div>
            </div>

            <div class="w-full px-2 mb-2">
                <div
                    class="bg-gray-800 border border-gray-700 rounded-[1.5rem] p-4 text-center relative overflow-hidden shadow-inner">
                    <p class="text-[10px] text-gray-400">本日のピックアップムード</p>
                    <p class="text-sm font-semibold text-white mt-1">「月曜から夜更かし...」</p>
                    <a href="#" class="text-xs text-yellow-500 hover:underline block mt-2">
                        おすすめが出る 👉
                    </a>
                </div>
            </div>

        </div>

        <div class="w-full max-w-md flex items-center justify-between px-6 mt-6">

            <a href="{{ route('profile.edit') }}"
                class="flex items-center space-x-1.5 px-3 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-full text-xs shadow-md transition">
                <span>編集⚙️</span>
            </a>

            <a href="#"
                class="flex items-center space-x-1.5 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-black font-bold rounded-full text-xs shadow-md transition">
                <span>マイページへ 👉</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full text-xs shadow-md transition">
                    ログアウト
                </button>
            </form>
        </div>

    </div>
</x-app-layout>