<x-guest-layout>
    <div class="flex flex-col items-center min-h-screen pt-6 sm:justify-center sm:pt-0 bg-black">
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-yellow-500 tracking-wider">MovieMood</h1>
            <p class="text-xl text-yellow-500 mt-2">新規登録</p>
        </div>

        <div
            class="w-full px-6 py-8 mt-6 overflow-hidden bg-gray-900 shadow-md sm:max-w-md sm:rounded-3xl border border-gray-700">
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                <div class="flex flex-col items-center mb-6">
                    <div
                        class="relative w-24 h-24 rounded-full bg-white flex items-center justify-center border border-gray-300 overflow-hidden cursor-pointer shadow-lg">
                        <img id="avatar-preview" src="" class="w-full h-full object-cover hidden">
                        <span id="avatar-text" class="text-[10px] text-gray-500 text-center px-1 leading-tight">アイコン
                            <span class="text-yellow-600">※任意項目</span><br>(写真など)</span>
                        <input type="file" name="avatar" id="avatar-input"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*"
                            onchange="previewImage(this)">
                    </div>
                    <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
                </div>

                <div>
                    <label for="name" class="block font-medium text-sm text-gray-300">氏名</label>
                    <x-text-input id="name"
                        class="block mt-1 w-full bg-white text-black border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-full h-10 px-4"
                        type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                        placeholder="氏名" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label for="nickname" class="block font-medium text-sm text-gray-300">ニックネーム</label>
                    <x-text-input id="nickname"
                        class="block mt-1 w-full bg-white text-black border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-full h-10 px-4"
                        type="text" name="nickname" :value="old('nickname')" placeholder="ニックネーム" />
                    <x-input-error :messages="$errors->get('nickname')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label for="email" class="block font-medium text-sm text-gray-300">メールアドレス</label>
                    <x-text-input id="email"
                        class="block mt-1 w-full bg-white text-black border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-full h-10 px-4"
                        type="email" name="email" :value="old('email')" required autocomplete="username"
                        placeholder="sample@sample.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-300">パスワード</label>
                    <x-text-input id="password"
                        class="block mt-1 w-full bg-white text-black border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-full h-10 px-4"
                        type="password" name="password" required autocomplete="new-password" placeholder="パスワード" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-text-input id="password_confirmation"
                        class="block mt-1 w-full bg-white text-black border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-full h-10 px-4"
                        type="password" name="password_confirmation" required autocomplete="new-password"
                        placeholder="パスワード（確認用）" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex flex-col items-center justify-end mt-8">
                    <button type="submit"
                        class="w-2/3 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-black font-semibold rounded-full transition duration-150 ease-in-out text-center shadow-md text-sm">
                        登録する
                    </button>

                    <a class="underline text-sm text-gray-400 hover:text-gray-100 mt-4" href="{{ route('login') }}">
                        ログインはこちら
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('avatar-preview');
            const text = document.getElementById('avatar-text');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (text) text.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-guest-layout>