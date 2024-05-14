<x-slot name="header">
    <x-page-header />
</x-slot>
    
<div class="container">
    <livewire:page-create />
    
    @if($pages->isEmpty())
        <div style="padding: 50px 0; font-size: 22px;">
            <p>メモがありません</p>
        </div>
    @else
        <style>
            .page-container {
              display: grid;
              grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
              gap: 8px 4px;
              padding: 50px 0;
            }
            
            .page-container > * {
                flex: 0 0 240px;
            }
            
            .page {
                position: relative;
                background-color: #fff;
                border-radius: 8px;
                border: 1px solid #D1D5DB;
                padding: 8px;
                transition: all 0.3s cubic-bezier(.25,.8,.25,1);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                minmax-width: 240px;
                height: 100px;
            }
            
            .page:hover {
                z-index: 999;
            }
                        
            .page-header {
                display: flex;
                justify-content: space-between;
                cursor: move;
            }
        
            .text-content {
                flex-grow: 1;
                max-height: calc(100% - 20px);
                overflow: hidden;
                position: relative;
                word-break: break-all;
            }
        
            .modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
            }
            
            .modal-content {
                background-color: white;
                padding: 8px 24px;
                border-radius: 8px;
                border: 1px solid #cccccc;
            }
            
            .sortable-chosen {
              opacity: 0.5;
              background-color: #f0f0f0;
              transition: all 0.3s ease;
            }
            
            .sortable-ghost {
              opacity: 0;
            }
            
            .drop-down-button {
                display: block;
                width: 100%;
                padding: 0.5rem 1rem;
                text-align: start;
                font-size: 0.875rem;
                line-height: 1.25rem;
                color: #4a4a4a;
                transition: all 0.15s ease-in-out;
            }
            
            .drop-down-button:hover, .drop-down-button:focus {
                background-color: #f2f2f2;
                outline: none;
            }
            
            .header-text {
                border: 1px solid #D1D5DB;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 18px;
                border-radius: 8px;
                padding: 4px;
            }
        </style>
        
        <div class="page-container" wire:sortable="updatePageOrder" wire:sortable.options="{chosenClass: 'sortable-chosen', ghostClass: 'sortable-ghost'}">
            @foreach($pages as $page)
                <div class="page" wire:sortable.item="{{ $page->id }}" wire:key="page-{{ $page->id }}">
                    <div class="page-header" wire:sortable.handle>
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
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button type="button">
                                    <span class="material-symbols-outlined">
                                        arrow_drop_down
                                    </span>
                                </button>
                            </x-slot>
                                
                            <x-slot name="content">
                                <button wire:click="delete({{ $page }})" style="font-size: 14px;" class="text-gray-600 drop-down-button">
                                    メモを削除する
                                </button>
                                <button
                                    style="font-size: 14px;" 
                                    class="text-gray-600 drop-down-button" 
                                    x-data="{ isFavorite: {{ $page->is_favorite }} }"
                                    x-text="isFavorite ? 'お気に入り解除' : 'お気に入り登録'"
                                    x-on:click="$wire.toggleFavorite(!isFavorite, {{ $page }}); isFavorite = !isFavorite;">
                                </button>
                                <button
                                    style="font-size: 14px;" 
                                    class="text-gray-600 drop-down-button" 
                                    x-data="{ isPublic: {{ $page->is_public }} }"
                                    x-text="isPublic ? '非公開にする' : '公開する'"
                                    x-on:click="if (confirm(isPublic ? '非公開にしますか？' : '公開しますか？')) { $wire.togglePublic(!isPublic, {{ $page }}); isPublic = !isPublic; }">
                                </button>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    <div class="text-content">
                        <a wire:click="edit({{ $page }})" style="cursor: pointer;">
                            <p class="font-bold">{{ $page->firstLine }}</p>
                            <p>{{ $page->restOfContent }}</p>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    
    @if($isEditOpen)
        <div class="modal-overlay">
            <div class="modal-content">
                <livewire:page-editor :page="$selectedPage" />
            </div>
        </div>
    @endif
</div>

@script
<script>
    let isDragging = false;

    document.addEventListener('dragstart', function() {
        isDragging = true;
    });

    document.addEventListener('dragend', function() {
        isDragging = false;
    });

    function setupListeners(element) {
        if (element.matches('.page')) {
            element.addEventListener('mouseover', function() {
                if (!isDragging) {
                    this.style.boxShadow = '0 0 5px 1px rgba(0,0,0,0.3)';
                }
            });

            element.addEventListener('mouseout', function() {
                this.style.boxShadow = '';
            });
        }
    }
    
    // 呼ばなくて良い時にも呼ばれているので処理を変える必要がある
    Livewire.hook('element.init', ({ component, el }) => {
        document.querySelectorAll('.page').forEach(setupListeners);
    })
</script>
@endscript