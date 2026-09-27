<section>
    <header>
        <h2 class="text-lg font-medium text-white">
            プロフィール情報
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            アカウントのプロフィール情報、メールアドレス、アイコン画像を更新できます。
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- 万が一エラーが発生した場合に赤枠で原因を表示 -->
        @if ($errors->any())
            <div class="bg-red-500/20 border border-red-500 text-red-300 p-4 rounded-xl text-xs space-y-1">
                <p class="font-bold text-red-400">更新できませんでした：</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 現在のアイコン画像プレビュー & 画像アップロード -->
        <div>
            <x-input-label for="avatar" value="アイコン画像" class="text-gray-300" />

            <div class="mt-2 flex items-center space-x-4">
                <!-- 現在設定されている画像の表示 -->
                <div
                    class="w-16 h-16 rounded-full bg-gray-800 text-white flex items-center justify-center overflow-hidden font-bold border border-gray-700 shrink-0">
                    @php
                        $avatarPath = $user->avatar ?? $user->icon ?? $user->icon_path ?? session('user_icon');
                    @endphp

                    @if($avatarPath && file_exists(public_path($avatarPath)))
                        <img src="{{ asset($avatarPath) }}?t={{ time() }}" class="w-full h-full object-cover">
                    @elseif($avatarPath && file_exists(public_path('storage/' . $avatarPath)))
                        <img src="{{ asset('storage/' . $avatarPath) }}?t={{ time() }}" class="w-full h-full object-cover">
                    @elseif($avatarPath)
                        <img src="{{ asset($avatarPath) }}?t={{ time() }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-xs text-gray-500">画像なし</span>
                    @endif
                </div>

                <!-- ファイル選択インプット -->
                <div class="space-y-1">
                    <input id="avatar" name="avatar" type="file" accept="image/*"
                        class="text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-yellow-500 file:text-black hover:file:bg-yellow-400 cursor-pointer" />
                    <p class="text-[10px] text-gray-400">※ 2MB以下の画像（JPG, PNG, GIF, WebP）を選択してください。</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <!-- ニックネーム（名前） -->
        <div>
            <x-input-label for="name" value="ニックネーム" class="text-gray-300" />
            <x-text-input id="name" name="name" type="text"
                class="mt-1 block w-full bg-gray-900 border-gray-800 text-white focus:border-yellow-500 focus:ring-yellow-500 rounded-xl"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- メールアドレス -->
        <div>
            <x-input-label for="email" value="メールアドレス" class="text-gray-300" />
            <x-text-input id="email" name="email" type="email"
                class="mt-1 block w-full bg-gray-900 border-gray-800 text-white focus:border-yellow-500 focus:ring-yellow-500 rounded-xl"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- 保存ボタン & 通知メッセージ -->
        <div class="flex items-center gap-4 pt-2">
            <x-primary-button
                class="bg-yellow-500 hover:bg-yellow-400 text-black font-bold rounded-full px-6 py-2 border-none">
                変更を保存
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-400">プロフィールを更新しました！</p>
            @endif
        </div>
    </form>
</section>