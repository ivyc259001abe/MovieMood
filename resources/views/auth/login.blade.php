<x-guest-layout>
    <div class="flex flex-col items-center min-h-screen pt-6 sm:justify-center sm:pt-0 bg-black">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-yellow-500 tracking-wider">MovieMood</h1>
            <p class="text-center text-xl text-yellow-500 mt-2">ログイン</p>
        </div>

        <div
            class="w-full px-6 py-8 mt-6 overflow-hidden bg-gray-900 shadow-md sm:max-w-md sm:rounded-lg border border-gray-700">
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <label for="email" class="block font-medium text-sm text-gray-300">メールアドレス(ID)</label>
                    <x-text-input id="email"
                        class="block mt-1 w-full bg-white text-black border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-full"
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-300">パスワード(PW)</label>
                    <x-text-input id="password"
                        class="block mt-1 w-full bg-white text-black border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-full"
                        type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-yellow-600 shadow-sm focus:ring-yellow-500"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-400">ログイン状態を保存する</span>
                    </label>
                </div>

                <div class="flex flex-col items-center justify-end mt-6 action-buttons">
                    <button type="submit"
                        class="w-full py-3 px-4 bg-yellow-500 hover:bg-yellow-600 text-black font-bold rounded-full transition duration-150 ease-in-out text-center">
                        ログイン
                    </button>

                    <div class="flex items-center justify-between w-full mt-4 text-sm text-gray-400">
                        @if (Route::has('password.request'))
                            <a class="underline hover:text-gray-100" href="{{ route('password.request') }}">
                                パスワードを忘れた場合
                            </a>
                        @endif

                        <a class="underline hover:text-gray-100" href="{{ route('register') }}">
                            新規登録はこちら
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>