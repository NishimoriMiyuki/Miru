<x-app-layout>
    <div class="flex">
        <!-- ページ一覧部分 -->
        <x-board-list :boards="$boards" />

        <div class="container mx-auto">
            
            <!-- タイトルを変更するフォーム -->
            <div class="mt-4">
                <form action="{{ route('boards.update', $board) }}" method="POST" class="flex items-center space-x-2">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $board->name }}" class="border border-gray-300 px-2 py-1">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white">更新</button>
                </form>
            </div>

            <!-- 新しいレコードを作るページへのリンク -->
            <div class="mt-4">
                <a href="{{ route('board_rows.create', $board) }}" class="inline-block mt-2 px-4 py-2 bg-blue-500 text-white">新規作成</a>
            </div>
            
            <!-- boardRow表示 -->
            <div class="mt-4 flex flex-wrap">
                @foreach ($statuses as $status)
                    <div class="w-1/4 p-4">
                        <div class="bg-white rounded shadow p-4">
                            <h2 class="text-xl">{{ $status->type }}</h2>
                            @if (isset($groupedRows[$status->id]))
                                @foreach ($groupedRows[$status->id] as $boardRow)
                                    <div class="mt-4 shadow-md flex">
                                        <a href="{{ route('board_rows.edit', $boardRow) }}">
                                            <h3 class="text-lg">{{ $boardRow->title }}</h3>
                                            <p>{{ $boardRow->quiz_content }}</p>
                                        </a>
                                        <!-- 削除ボタン -->
                                        <form action="{{ route('board_rows.destroy', $boardRow) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">削除</button>
                                        </form>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
    
        </div>
    </div>
</x-app-layout>