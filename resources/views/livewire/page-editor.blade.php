<div class="flex flex-col" style="width: 500px; max-width: 100vw;">
    <style>
        textarea::-webkit-scrollbar {
            width: 10px;
        }
        
        textarea::-webkit-scrollbar-track {
            background: transparent;
        }
        
        textarea::-webkit-scrollbar-thumb {
            background: #b8b8b8;
        }
        
        textarea::-webkit-scrollbar-thumb:hover {
            background: #9e9e9e;
        }
    </style>
    
    <div class="flex justify-end">
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button type="button" class="tool">
                    <span class="material-symbols-outlined">
                        arrow_drop_down
                    </span>
                </button>
            </x-slot>
                
            <x-slot name="content">
                <button wire:click="delete" style="font-size: 14px;" class="text-gray-600 drop-down-button">
                    メモを削除する
                </button>
                <button wire:click="toggleFavorite" style="font-size: 14px;" class="text-gray-600 drop-down-button">
                    @if($page->is_favorite)
                        お気に入り解除
                    @else
                        お気に入り登録
                    @endif
                </button>
                <button wire:click="togglePublic" style="font-size: 14px;" class="text-gray-600 drop-down-button" wire:confirm="{{ $page->is_public ? '非公開にしますか？' : '公開しますか？' }}">
                    @if($page->is_public)
                        非公開にする
                    @else
                        公開する
                    @endif
                </button>
            </x-slot>
        </x-dropdown>
    </div>
    
    <textarea
        name="content"
        wire:ignore 
        wire:model.live.debounce.500ms="content"
        x-init="$nextTick(() => resize())"
        x-data="{
                    resize() {
                        $el.style.height = '0px';
                        $el.style.height = $el.scrollHeight + 'px';
                    }
                }"
        @input="resize();"
        x-on:resize-textarea.window="
            setTimeout(() => {
                resize();
            }, 100);
        "
        maxlength="2000" placeholder="内容を入力" 
        class="border-0 border-transparent focus:border-transparent focus:ring-0 focus:outline-none resize-none"
        style="overflow: auto; max-height: 70vh"
        autofocus>{{ $page->content }}</textarea>
    
    @error('content') <span class="error text-red-500 font-bold" style="font-size: 12px;">{{ $message }}</span> @enderror
    
    <div class="bg-white pt-2" style="font-size: 12px;">
        編集日時: {{ $page->updated_at }}
    </div>
    
    <div style="display: flex; justify-content: space-between;">
        <div style="font-size: 12px; display: flex; justify-content: space-between; gap: 2px;">
            @if($page->is_favorite)
                <div class="header-text">
                    お気に入り
                </div>
            @endif
            @if($page->is_public)
                <div class="header-text">
                    公開中
                </div>
            @endif
        </div>
        
        <button wire:click="close" class="tool-button" style="font-size: 14px;">
            閉じる
        </button>
    </div>
</div>