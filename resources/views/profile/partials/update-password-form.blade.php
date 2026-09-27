<section>
    <header>
        <h2 class="text-lg font-medium text-white">
            パスワード変更
        </h2>
        <p class="mt-1 text-xs text-gray-400">
            安全なパスワードを設定してください。
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" value="現在のパスワード" class="text-gray-300 text-xs" />
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class="mt-1 block w-full bg-white text-black rounded-full px-4 py-2" border-none text-sm
                placeholder="現在のパスワード" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" value="新しいパスワード" class="text-gray-300 text-xs" />
            <x-text-input id="update_password_password" name="password" type="password"
                class="mt-1 block w-full bg-white text-black rounded-full px-4 py-2 border-none text-sm placeholder="
                新しいパスワード" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" value="新しいパスワード（確認用）"
                class="text-gray-300 text-xs" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full bg-white text-black rounded-full px-4 py-2 border-none text-sm placeholder="
                新しいパスワード(確認)" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button
                class="bg-yellow-500 hover:bg-yellow-400 text-black font-bold rounded-full px-6 py-2 border-none">
                パスワードを変更
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-400">更新しました</p>
            @endif
        </div>
    </form>
</section>