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
    
    <div class="container mx-auto px-4">
        <!-- 投稿画面へ -->
        <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            投稿画面へ
        </a>
    </div>
    
    <!-- 投稿一覧 -->
    <div class="flex flex-col items-center">
        @foreach ($posts as $post)
            <div class="mt-4 bg-white rounded shadow-lg overflow-hidden w-full">
                <div class="p-6">
                    <h5 class="text-lg font-bold">{{ $post->user->name }}</h5>
                    <p class="mt-2 text-gray-600">{{ $post->content }}</p>
                    <p class="mt-2 text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @endforeach
    </div>
    
    
</x-app-layout>