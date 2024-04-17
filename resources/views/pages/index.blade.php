<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="$title" />
    </x-slot>
    
    <div class="w-1/2 h-full space-y-4 pt-4 flex flex-col">
        <livewire:page-create />
        @if($pages->isEmpty())
            <p>ページはありません。</p>
        @else
            @foreach ($pages as $page)
                <div class="block">
                    <div class="bg-white border border-gray-300 p-2 rounded-lg">
                        <a href="{{ route($page->trashed() ? 'pages.deleted_show' : 'pages.edit', ['page' => $page->id]) }}">
                            <div class="font-bold text-3xl">
                                {{ $page->title }}
                            </div>
                            <div class="mt-2">
                                {{ $page->content }}
                            </div>
                        </a>
                        <div class="mt-0">
                            <livewire:page-tool-button-group :page="$page" />
                        </div>
                    </div>
                </div>
            @endforeach
        {{ $pages->appends(['type' => request('type')])->links() }}
        @endif
    </div>
</x-app-layout>