<x-app-layout>
    <div class="py-12 min-h-screen bg-black flex flex-col items-center justify-start">

        <div class="text-center mb-8 mt-4">
            <h1 class="text-4xl font-extrabold tracking-widest text-yellow-500">MovieMood</h1>
            <p class="text-xs text-gray-400 mt-2">ACCOUNT SETTINGS</p>
        </div>

        <div class="w-full max-w-md mx-auto bg-gray-950 p-8 rounded-3xl border border-gray-800 shadow-2xl space-y-8">

            <div>
                @include('profile.partials.update-profile-information-form')
            </div>

            <hr class="border-gray-800/60">

            <div class="pt-2">
                @include('profile.partials.delete-user-form')
            </div>

        </div>

        <div class="w-full max-w-md flex items-center justify-between px-6 mt-6">
            <a href="{{ route('dashboard') }}" class="text-xs text-gray-400 hover:text-white transition">
                キャンセル(戻る)
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