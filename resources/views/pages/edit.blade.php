<x-app-layout>
    <x-slot name="header">
        <x-page-header />
    </x-slot>
    
    <div class="w-1/2 h-full">
        @livewire('page-editor', ['page' => $page])
    </div>
</x-app-layout>