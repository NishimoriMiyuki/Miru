<!-- ページ一覧部分 -->
<div class="w-1/4 h-screen bg-white p-4 overflow-auto">
    <!-- ページ作成ボタン -->
    <a href="{{ route('boards.create') }}">{{ __('新しいboardを作成') }}</a>
    <!-- ゴミ箱ボタン -->
    <a href="{{ route('boards.trashed') }}" class="ml-4">{{ __('ゴミ箱') }}</a>
    <h2 class="font-bold mb-4">{{ __('board一覧') }}</h2>
    <ul>
        @foreach ($boards as $board)
            <li class="mb-2 flex justify-between">
                <!-- 各ページのボタンと削除ボタン -->
                <a href="{{ route('boards.show', $board) }}" class="mr-4">{{ $board->name }}</a>
                <form method="POST" action="{{ route('boards.destroy', $board) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500">{{ __('削除') }}</button>
                </form>
            </li>
        @endforeach
    </ul>
</div>