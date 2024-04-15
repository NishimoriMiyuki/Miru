<x-app-layout>
    <x-slot name="header">
        <x-page-header />
    </x-slot>
    
    <!-- ページ内容表示 -->
    <div class="w-3/4 h-[calc(100vh-45px)] bg-white overflow-auto p-4">
        <div>
            <input type="text" id="title" name="title" value="{{ $page->title }}" class="shadow appearance-none border border-transparent w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-3xl font-semibold" readonly>
        </div>
        <div>
            <textarea id="content" name="content" rows="50" class="shadow appearance-none border border-transparent w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" readonly>{{ $page->content }}</textarea>
        </div>
    </div>
</x-app-layout>