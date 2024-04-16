<x-app-layout>
    <x-slot name="header">
        <x-page-header />
    </x-slot>
    
    <div class="w-1/2 h-full space-y-4 pt-4 flex flex-col">
        @foreach ($pages as $page)
            <div class="block">
                <div class="bg-white border border-gray-300 p-2 rounded-lg">
                    <a href="{{ route('pages.edit', ['page' => $page->id]) }}">
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
    </div>
</x-app-layout>