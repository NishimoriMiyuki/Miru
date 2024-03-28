<x-app-layout>
    <div class="flex">
        <!-- ページ一覧部分 -->
        <x-page-list :pages="$pages" />
        
        <!-- ゴミ箱の中身一覧 -->
        <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 一括削除ボタン -->
            <form method="POST" action="{{ route('pages.delete_all') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('一括削除') }}
                </button>
            </form>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- 各ページ -->
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($trashedPages->count())
                        <!-- ページ操作のためのフォーム -->
                        <form id="pageForm" method="POST" action="">
                            @csrf
                            <ul>
                                @foreach ($trashedPages as $trashedPage)
                                    <li class="mb-4">
                                        <!-- チェックボックス -->
                                        <input type="checkbox" name="selectedPages[]" value="{{ $trashedPage->id }}">
                                        <h2 class="font-bold text-xl">{{ $trashedPage->title }}</h2>
                                        <p>{{ $trashedPage->content }}</p>
                                    </li>
                                @endforeach
                            </ul>
                            <!-- ページ操作のボタン -->
                            <button type="submit" class="mt-4" onclick="submitForm('DELETE', '{{ route('pages.delete_selected') }}')">{{ __('選択したページを削除') }}</button>
                            <button type="submit" class="mt-4" onclick="submitForm('PATCH', '{{ route('pages.restore_selected') }}')">{{ __('選択したページを復元') }}</button>
                        </form>
                    @else
                        <p>{{ __('論理削除されたページはありません。') }}</p>
                    @endif
                </div>
            </div>
            <!-- ページネーション -->
            {{ $trashedPages->links() }}
        </div>
    </div>
    </div>
    
<script>
    // フォームの送信方法と送信先を設定するメソッド
    function submitForm(method, action) {
        var form = document.getElementById('pageForm');
        form.method = 'POST';
        form.action = action;
        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = '_method';
        hiddenInput.value = method;
        form.appendChild(hiddenInput);
        form.submit();
    }
</script>
</x-app-layout>