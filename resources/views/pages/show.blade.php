<x-app-layout>
    <div class="flex">
        <!-- ページ一覧部分 -->
        <x-page_list :pages="$pages" />
        
        <!-- メインコンテンツ部分 -->
        <div class="w-3/4 max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <!-- 更新フォーム -->
            <form method="POST" action="{{ route('pages.update', $select_page) }}">
                @csrf
                @method('PUT')
                <!-- タイトル入力欄 -->
                <textarea
                    name="title"
                    placeholder="{{ __('ページタイトルを入力') }}"
                    class="block w-full h-10 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                >{{ $select_page->title }}</textarea>
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                <!-- コンテンツ入力欄 -->
                <textarea
                    name="content"
                    placeholder="{{ __('あなたの考えを書きましょう') }}"
                    class="block w-full h-40 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    style="resize: horizontal;"
                >{{ $select_page->content }}</textarea>
                <x-input-error :messages="$errors->get('content')" class="mt-2" />
                <!-- 更新ボタン -->
                <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">{{ __('更新') }}</button>
            </form>
        </div>
    </div>
</x-app-layout>