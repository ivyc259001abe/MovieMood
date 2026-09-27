<x-app-layout>
    <div style="max-width: 1100px; margin: 0 auto; padding: 24px 16px;">

        <!-- 1. ページ上部エリア -->
        <div style="margin-bottom: 28px; display: flex; flex-direction: column; gap: 16px;">

            <div
                style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                <a href="{{ route('home') }}"
                    style="display: inline-flex; align-items: center; gap: 6px; color: #9ca3af; text-decoration: none; font-size: 13px; font-weight: bold; transition: color 0.2s;"
                    onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#9ca3af'">
                    &larr; ホームへ戻る
                </a>

                <!-- 再検索用バー -->
                <form action="{{ route('movies.search') }}" method="GET"
                    style="flex: 1; max-width: 480px; display: flex; gap: 8px; margin: 0;">
                    <input type="text" name="query" value="{{ request('query') }}" placeholder="キーワードで再検索..." required
                        style="flex: 1; min-width: 0; background-color: #000000; border: 1px solid #374151; color: #ffffff; border-radius: 9999px; padding: 8px 16px; font-size: 13px; outline: none;">
                    <button type="submit"
                        style="background-color: #f59e0b; color: #000000; font-weight: bold; padding: 8px 18px; border-radius: 9999px; font-size: 13px; border: none; cursor: pointer; flex-shrink: 0;">
                        検索
                    </button>
                </form>
            </div>

            <!-- 2. タイトルヘッダーカード -->
            <div class="bg-gray-900/90 rounded-2xl border border-gray-800 shadow-xl" style="padding: 20px 24px;">
                <span
                    style="font-size: 11px; color: #fbbf24; font-weight: 800; display: block; letter-spacing: 0.08em; text-transform: uppercase;">
                    KEYWORD SEARCH RESULT
                </span>
                <h1 style="font-size: 20px; font-weight: 800; color: #ffffff; margin: 4px 0 0 0;">
                    🔍 「{{ request('query') }}」の検索結果
                </h1>
            </div>

        </div>

        <!-- 3. 映画一覧グリッド -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 16px;">
            @forelse($movies ?? [] as $movie)
                <a href="{{ route('movies.show', $movie['id']) }}"
                    class="bg-gray-900/90 rounded-2xl border border-gray-800 shadow-xl group"
                    style="padding: 12px; text-decoration: none; display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s, border-color 0.2s;">
                    <div class="overflow-hidden rounded-xl border border-gray-700"
                        style="position: relative; aspect-ratio: 2/3; margin-bottom: 10px;">
                        <img src="{{ !empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=500&q=80' }}"
                            alt="{{ $movie['title'] }}"
                            style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;"
                            class="group-hover:scale-105">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <h3 class="line-clamp-1" style="font-size: 13px; font-weight: bold; color: #ffffff; margin: 0;">
                            {{ $movie['title'] }}
                        </h3>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 11px;">
                            <span
                                style="color: #9ca3af;">{{ !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) . '年' : '' }}</span>
                            @if(!empty($movie['vote_average']))
                                <span style="color: #fbbf24; font-weight: bold;">⭐
                                    {{ number_format($movie['vote_average'], 1) }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-gray-900/90 rounded-2xl border border-gray-800"
                    style="grid-column: 1 / -1; padding: 40px; text-align: center; color: #9ca3af;">
                    <p style="margin: 0; font-size: 14px;">「{{ request('query') }}」に一致する映画が見つかりませんでした。</p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>