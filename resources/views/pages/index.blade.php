<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="$title" />
    </x-slot>
    
    <div class="w-1/2 h-full space-y-4 pt-4 flex flex-col">
        <livewire:page-create />
        @if($pages->isEmpty())
            <p>ページはありません。</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($pages as $page)
                    <div class="block h-64">
                        <div class="bg-white border border-gray-300 p-2 rounded-lg h-full flex flex-col">
                            <a href="{{ route($page->trashed() ? 'pages.deleted_show' : 'pages.edit', ['page' => $page->id]) }}" class="flex-grow overflow-auto">
                                <div class="font-bold text-3xl">
                                    {{ \Illuminate\Support\Str::limit($page->title, 20) }}
                                </div>
                                <div class="mt-2">
                                    {{ \Illuminate\Support\Str::limit($page->content, 100) }}
                                </div>
                            </a>
                            <div class="mt-0">
                                <livewire:page-tool-button-group :page="$page" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $pages->appends(['type' => request('type')])->links() }}
        @endif
    </div>
</x-app-layout>