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
    
    <textarea 
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
        
        <div class="toolbar text-gray-300">
            <div class="tool">
                <button wire:click="toggleFavorite">
                    @if($page->is_favorite)
                        <span class="material-symbols-outlined text-pink-400">
                            heart_check
                        </span>
                    @else
                        <span class="material-symbols-outlined">
                            favorite
                        </span>
                    @endif
                </button>
                <div class="tooltip">
                    @if($page->is_favorite)
                        お気に入り解除
                    @else
                        お気に入り登録
                    @endif
                </div>
            </div>
            <div class="tool">
                <button wire:click="togglePublic" wire:confirm="{{ $page->is_public ? '非公開にしますか？':'公開しますか？' }}">
                    @if($page->is_public)
                        <span class="material-symbols-outlined text-blue-400">
                            public
                        </span>
                    @else
                        <span class="material-symbols-outlined">
                            public_off
                        </span>
                    @endif
                </button>
                <div class="tooltip">
                    @if($page->is_public)
                        非公開にする
                    @else
                        公開する
                    @endif
                </div>
            </div>
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button type="button" class="tool">
                        <span class="material-symbols-outlined">
                            more_horiz
                        </span>
                        <span class="tooltip">アクション</span>
                    </button>
                </x-slot>
                    
                <x-slot name="content">
                    <button wire:click="delete" style="font-size: 14px;" class="drop-down-button">
                        メモを削除する
                    </button>
                </x-slot>
            </x-dropdown>
        </div>
        
        <button wire:click="close" class="tool-button" style="font-size: 14px;">
            閉じる
        </button>
    </div>
</div>