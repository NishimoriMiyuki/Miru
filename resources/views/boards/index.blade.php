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
        <!-- ページ一覧部分 -->
        <x-board-list :boards="$boards" />
    </div>
</x-app-layout>