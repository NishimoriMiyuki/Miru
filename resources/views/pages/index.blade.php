<x-app-layout>
    <!-- フラッシュメッセージ -->
    @if (session('message'))
        <div class="alert alert-success relative">
            {{ session('message') }}
        <!-- フラッシュメッセージを消すボタン -->
        <button type="button" class="absolute top-0 right-0 px-4 py-3" onclick="this.parentElement.remove();">
            ×
        </button>
        </div>
    @endif
    
    <div class="flex">
        <!-- ページ一覧部分 -->
        <x-page_list :pages="$pages" />
    </div>
</x-app-layout>