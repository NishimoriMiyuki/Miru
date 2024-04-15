<x-app-layout>
    <x-slot name="header">
        <x-page-header />
    </x-slot>
    
    <div class="w-1/2 h-full">
        @foreach ($trashedPages as $trashedPage)
            <div>
                <h2>{{ $trashedPage->title }}</h2>
                <p>{{ $trashedPage->content }}</p>
            </div>
        @endforeach
    </div>
</x-app-layout>