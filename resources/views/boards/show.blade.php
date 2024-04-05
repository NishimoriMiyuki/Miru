<x-app-layout>
    <!-- フラッシュメッセージ -->
    @if (session('message'))
        <div class="bg-green-500 text-white relative">
            {{ session('message') }}
        <!-- フラッシュメッセージを消すボタン -->
        <button type="button" class="absolute top-0 right-0 px-4 py-3 text-white" onclick="this.parentElement.remove();">
            ×
        </button>
        </div>
    @endif
    
    <div class="flex">
            <!-- サイドバー -->
        <div class="w-1/4 h-screen bg-gray-200 overflow-auto">
            
            <!-- ボタン -->
            <div class="p-4 flex flex-col space-y-2">
                <a href="{{ route('boards.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ゴミ箱を閉じる
                </a>
            </div>
            
            <!-- 各ページのリンク -->
            <ul class="space-y-2 p-4">
                @foreach ($trashedBoards as $trashedBoard)
                    <li>
                        <div class="flex justify-between">
                            <a href="{{ route('boards.show', $trashedBoard) }}" class="text-blue-500 hover:underline">
                                {{ \Illuminate\Support\Str::limit($trashedBoard->name, 20) }}
                            </a>
                            <!-- ドロップダウンメニュー -->
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <!-- 削除ボタン -->
                                    <form method="POST" action="{{ route('boards.force_delete', $trashedBoard) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-dropdown-link :href="route('boards.force_delete', $trashedBoard)"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('削除') }}
                                        </x-dropdown-link>
                                    </form>
                                    <!-- 復元ボタン -->
                                    <form method="POST" action="{{ route('boards.restore', $trashedBoard) }}">
                                        @csrf
                                        <x-dropdown-link :href="route('boards.restore', $trashedBoard)"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('復元') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        
        <div class="container mx-auto">
            
            <!-- ボード名入力フォーム -->
            <div class="p-4">
                <form action="{{ route('boards.update', $board) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="ボード名を入力" value="{{ $board->name }}" readOnly>
                </form>
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
                                            <h3 class="text-lg">{{ $boardRow->title }}</h3>
                                            <p>{{ $boardRow->quiz_content }}</p>
                                        </a>
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