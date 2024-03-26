<!-- ページ一覧部分 -->
<div class="w-1/4 h-screen bg-white p-4 overflow-auto">
    <!-- ページ作成ボタン -->
    <a href="{{ route('pages.create') }}">{{ __('新しいページを作成') }}</a>
    <!-- ゴミ箱ボタン -->
    <a href="{{ route('pages.trashed') }}" class="ml-4">{{ __('ゴミ箱') }}</a>
    <h2 class="font-bold mb-4">{{ __('ページ一覧') }}</h2>
    <ul>
        @foreach ($pages as $page)
            <li class="mb-2 flex justify-between">
                <!-- 各ページのボタンと削除ボタン -->
                <a href="{{ route('pages.show', $page) }}" class="mr-4">{{ $page->title }}</a>
                <form method="POST" action="{{ route('pages.destroy', $page) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500">{{ __('削除') }}</button>
                </form>
            </li>
        @endforeach
    </ul>
</div>