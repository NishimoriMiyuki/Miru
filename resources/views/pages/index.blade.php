<x-app-layout>
    <x-slot name="header">
        <x-page-header />
    </x-slot>
    
    <div class="w-1/2 h-full">
        @foreach ($pages as $page)
            <div>
                <h2><a href="{{ route('pages.edit', $page) }}">{{ $page->title }}</a></h2>
                <p>{{ $page->content }}</p>
            </div>
        @endforeach
    </div>
</x-app-layout>