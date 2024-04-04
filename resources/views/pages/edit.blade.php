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
                <a href="{{ route('pages.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Page作成
                </a>
                <a href="{{ route('pages.trashed') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ゴミ箱を開く
                </a>
            </div>
            
            <!-- 各ページのリンク -->
            <ul class="space-y-2 p-4">
                @foreach ($pages as $linkPage)
                    <li>
                        <div class="flex justify-between">
                            <a href="{{ route('pages.edit', $linkPage) }}" class="text-blue-500 hover:underline">
                                {{ \Illuminate\Support\Str::limit($linkPage->title, 20) }}
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
                                    <form method="POST" action="{{ route('pages.destroy', $linkPage) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-dropdown-link :href="route('pages.destroy', $linkPage)"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('削除') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        
        <!-- ページ編集フォーム -->
        <div class="w-3/4 h-screen bg-white overflow-auto p-4">
            <form action="{{ route('pages.update', $page) }}" method="POST">
                @csrf
                @method('PUT')
                <div>
                    <input type="text" id="title" name="title" placeholder="タイトルを入力して下さい" value="{{ old('title', $page->title) }}" class="shadow appearance-none border border-transparent w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-3xl font-semibold">
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                <div>
                    <textarea id="content" name="content" rows="50" placeholder="内容を入力して下さい" class="shadow appearance-none border border-transparent w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('content', $page->content) }}</textarea>
                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                </div>
                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        更新
                    </button>
                </div>
            </form>
        </div>
        
    </div>
    
</x-app-layout>