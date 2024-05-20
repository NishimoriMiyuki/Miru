<x-app-layout>
    <x-slot name="header">
        <x-header title="プロフィール">
            <x-slot name="content">
                <a href="{{ route('pages.index') }}" class="grid-item">メモ</a>
                <a href="{{ route('pages.public') }}" class="grid-item">公開メモ</a>
                <a href="{{ route('boards.index') }}" class="grid-item">ボード</a>
            </x-slot>
        </x-header>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
