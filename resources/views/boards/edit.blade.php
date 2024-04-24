<x-app-layout>
    <x-slot name="header">
        <x-board-header :title="'ボード（一覧）'" />
    </x-slot>
    
    <!-- BoardRow作成ボタン -->
    <div class="p-4">
        <a href="{{ route('board_rows.store', $board) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            問題作成
        </a>
    </div>
    
    <!-- boardRow表示用 -->
    <div class="mt-4 flex flex-wrap">
        @foreach ($statuses as $status)
            <div class="w-1/3 p-4">
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
</x-app-layout>