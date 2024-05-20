<div class="p-6">
    
    <input type="text" placeholder="メモの内容を検索..." class="w-full p-2 border border-gray-300 rounded-md mb-4" wire:model.live.debounce.250ms="searchTerm">
    
    <ul class="divide-y divide-gray-200">
        @forelse($pages as $page)
            <li class="py-4 flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="mt-2 text-sm text-gray-500 text-left" style="white-space: pre-line;">{{ $page->content }}</p>
                    <div class="mt-2 text-xs text-gray-500 flex justify-end space-x-4">
                        <span>更新日時: {{ $page->updated_at->format('Y-m-d H:i') }}</span>
                        <span>作成者: {{ optional($page->user)->name ?? '不明' }}</span>
                    </div>
                </div>
            </li>
        @empty
            <li class="py-4 flex items-center justify-center">
                <p style="font-size: 22px;">公開されているメモがありません</p>
            </li>
        @endforelse
    </ul>
    
    <div class="mt-4">
        {{ $pages->links() }}
    </div>
</div>