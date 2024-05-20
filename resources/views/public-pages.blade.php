<x-app-layout>
    <x-slot name="header">
        <x-header title="公開メモ">
            <x-slot name="content">
                <a href="{{ route('pages.public') }}" class="grid-item">公開メモ</a>
                <a href="{{ route('pages.index') }}" class="grid-item">メモ</a>
                <a href="{{ route('boards.index') }}" class="grid-item">ボード</a>
            </x-slot>
        </x-header>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8" style="width: 80vw; min-width: 340px;">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                @livewire('public-page-list')
            </div>
        </div>
    </div>
</x-app-layout>