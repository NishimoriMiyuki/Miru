<x-app-layout>
    <div class="flex">
        <!-- ページ一覧部分 -->
        <x-board-list :boards="$boards" />
        
        <div class="container mx-auto">
            <!-- タイトルを変更するフォーム -->
            <form action="{{ route('boards.update', $board) }}" method="POST" class="flex items-center space-x-2">
                @csrf
                @method('PUT')
                <input type="text" name="name" value="{{ $board->name }}" class="border border-gray-300 px-2 py-1">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white">更新</button>
            </form>
            
            <!-- 新しいレコードを作るページへのリンク -->
            <a href="{{ route('board_rows.create', $board) }}" class="inline-block mt-2 px-4 py-2 bg-blue-500 text-white">新規作成</a>
            
            <!-- レコードをカードで表示 -->
            @foreach ($board->boardRows as $boardRow)
                <div class="mt-4">
                    <div class="w-full md:w-1/3">
                        <div class="border border-gray-300 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-200 flex justify-between items-center">
                                <span>{{ $boardRow->title }}</span>
                                <!-- レコード削除ボタン -->
                                <form method="POST" action="{{ route('board_rows.destroy', $boardRow) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500" onclick="event.stopPropagation();">{{ __('削除') }}</button>
                                </form>
                            </div>
                            <div class="p-4 cursor-pointer">
                                {{ $boardRow->quiz_content }}
                                {{ $boardRow->quiz_answer }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>