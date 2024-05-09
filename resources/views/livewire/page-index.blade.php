<x-slot name="header">
    <x-page-header />
</x-slot>
    
<div class="container">
    <livewire:page-create />
    
    @if($pages->isEmpty())
        <p>メモがありません。</p>
    @else
        <style>
            .page-container {
              display: grid;
              grid-template-columns: repeat(auto-fill, minmax(225px, 1fr));
              gap: 8px 4px;
              padding: 50px 0;
            }
            
            .page-container > * {
                flex: 0 0 225px;
            }
            
            .page {
                position: relative;
                background-color: #fff;
                border-radius: 8px;
                border: 1px solid #D1D5DB;
                padding: 20px;
                transition: all 0.3s cubic-bezier(.25,.8,.25,1);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                width: 240px;
                height: 100px;
            }
            
            .text-content {
                max-height: calc(100% - 20px);
                overflow: hidden;
                position: relative;
                cursor: pointer;
                word-break: break-all;
            }
            
            .drag-handle {
              position: absolute;
              top: 0;
              left: 50%;
              transform: translate(-50%);
              z-index: 1;
              cursor: pointer;
            }
        
            .toolbar {
                display: flex;
                justify-content: flex-end;
            }
            
            .tool {
                display: inline-block;
                margin-right: 10px;
                position: relative;
            }
            
            .tool .tooltip {
                display: none;
                position: absolute;
                background-color: #555;
                color: #fff;
                text-align: center;
                padding: 4px 4px;
                border-radius: 4px;
                z-index: 999;
                top: 100%; 
                left: 50%; 
                transform: translateX(-50%);
                writing-mode: vertical-lr;
                line-height: 1;
                font-size: 12px;
            }
            
            .page .toolbar {
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
                position: absolute;
                bottom: 0;
                right: 0;
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
                padding: 24px;
                padding-bottom: 8px;
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
        }
        </style>
        
        <div class="page-container" wire:sortable="updatePageOrder" wire:sortable.options="{chosenClass: 'sortable-chosen', ghostClass: 'sortable-ghost'}">
            @foreach($pages as $page)
                <div class="page" wire:sortable.item="{{ $page->id }}" wire:key="page-{{ $page->id }}">
                    <div class="drag-handle" wire:sortable.handle>
                        <span class="material-symbols-outlined text-gray-300">
                            drag_handle
                        </span>
                    </div> 
                    <div class="text-content">
                        <a wire:click="edit({{ $page }})">
                            <p class="font-bold">{{ $page->firstLine }}</p>
                            <p>{{ $page->restOfContent }}</p>
                        </a>
                    </div>
                    <div class="toolbar text-gray-300">
                        <div class="tool">
                            <button wire:click="toggleFavorite({{ $page }})">
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
                            <button wire:click="togglePublic({{ $page }})" wire:confirm="{{ $page->is_public ? '非公開にしますか？':'公開しますか？' }}">
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
                                <button wire:click="delete({{ $page }})" style="font-size: 14px;" class="text-gray-600 drop-down-button">
                                    メモを削除する
                                </button>
                            </x-slot>
                        </x-dropdown>
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
                    const toolbar = this.querySelector('.toolbar');
                    toolbar.style.opacity = '1';
                    toolbar.style.visibility = 'visible';
                }
            });

            element.addEventListener('mouseout', function() {
                this.style.boxShadow = '';
                const toolbar = this.querySelector('.toolbar');
                toolbar.style.opacity = '0';
                toolbar.style.visibility = 'hidden';
            });
        }

        if (element.matches('.tool')) {
            element.addEventListener('mouseover', function() {
                if (!isDragging) {
                    this.querySelector('.tooltip').style.display = 'block';
                }
            });

            element.addEventListener('mouseout', function() {
                this.querySelector('.tooltip').style.display = '';
            });
        }
    }

    document.querySelectorAll('.page, .tool').forEach(setupListeners);

    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    setupListeners(node);
                    node.querySelectorAll('.page, .tool').forEach(setupListeners);
                }
            });
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });
</script>
@endscript