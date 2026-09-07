<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('patch')

    <div class="flex flex-col items-center space-y-3 mb-6">
        <span class="text-xs text-yellow-500/80 font-semibold">アイコン ※任意項目 (写真など)</span>

        <div
            class="relative w-28 h-28 rounded-full border-2 border-dashed border-gray-700 bg-gray-900 flex items-center justify-center overflow-hidden shadow-inner">
            @if(Auth::user()->avatar && Storage::disk('public')->exists(Auth::user()->avatar))
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
            @else
                <div class="flex flex-col items-center justify-center text-gray-500 text-center">
                    <span class="text-xs font-bold bg-white text-black px-2 py-1 rounded mb-1">アイコン</span>
                    <span class="text-[10px] text-gray-400">(写真など)</span>
                </div>
            @endif
        </div>

        <input type="file" name="avatar" class="hidden" id="avatarInput" accept="image/*">
        <button type="button" onclick="document.getElementById('avatarInput').click()"
            class="px-4 py-1.5 bg-gray-800 hover:bg-gray-700 text-white rounded-full text-xs transition border border-gray-700">
            アイコン変更
        </button>
        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
    </div>

    <div>
        <label for="name" class="block text-xs font-bold text-gray-300 mb-1">氏名</label>
        <input id="name" name="name" type="text"
            class="w-full bg-white text-black rounded-full py-2.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500"
            value="{{ old('name', $user->name) }}" required autofocus>
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <label for="nickname" class="block text-xs font-bold text-gray-300 mb-1">ニックネーム</label>
        <input id="nickname" name="nickname" type="text"
            class="w-full bg-white text-black rounded-full py-2.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500"
            value="{{ old('nickname', $user->nickname) }}" placeholder="ニックネーム">
        <x-input-error class="mt-2" :messages="$errors->get('nickname')" />
    </div>

    <div>
        <label for="email" class="block text-xs font-bold text-gray-300 mb-1">メールアドレス</label>
        <input id="email" name="email" type="email"
            class="w-full bg-white text-black rounded-full py-2.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500"
            value="{{ old('email', $user->email) }}" required>
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    <div class="pt-2 space-y-3">
        <label class="block text-xs font-bold text-yellow-500">新しいパスワード (※変更する場合のみ)</label>
        <input id="password" name="password" type="password"
            class="w-full bg-white text-black rounded-full py-2.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500"
            placeholder="パスワード">
        <x-input-error class="mt-2" :messages="$errors->get('password')" />

        <input id="password_confirmation" name="password_confirmation" type="password"
            class="w-full bg-white text-black rounded-full py-2.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500"
            placeholder="パスワード(確認用)">
        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
    </div>

    <div class="flex justify-center pt-6">
        <button type="submit"
            class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-3 px-6 rounded-full text-sm transition tracking-wider">
            変更を保存する
        </button>
    </div>
</form>