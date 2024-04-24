<x-slot name="header">
    <x-page-header :title="'メモ（一覧）'" />
</x-slot>
    
<div class="h-full container">
    <div wire:loading style="position: absolute; z-index: 9999; right: 0;">
        <div class="loader">Loading...</div>
    </div>
    
    <livewire:page-create />
    
    @if($pages->isEmpty())
        <p>メモがありません。</p>
    @else
        <style>
            .page-container {
              display: grid;
              grid-template-columns: repeat(auto-fill, minmax(225px, 1fr));
              gap: 20px;
              padding: 50px 0;
            }
            
            .page-container > * {
                flex: 0 0 225px;
            }
            
            .page {
                position: relative;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
                padding: 20px;
                transition: all 0.3s cubic-bezier(.25,.8,.25,1);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                width: 225px;
                height: 225px;
            }
            
            .text-content {
                max-height: calc(100% - 20px);
                overflow: hidden;
                position: relative;
                cursor: pointer;
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
                padding: 5px 10px;
                border-radius: 6px;
                z-index: 1;
                top: 100%; 
                left: 50%; 
                transform: translateX(-50%);
                writing-mode: vertical-lr;
            }
            
            .page .toolbar {
                position: absolute;
                bottom: 0;
                right: 0;
                display: none;
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
                padding: 20px;
                border-radius: 8px;
            }
            
            .sortable-chosen {
              opacity: 0.5;
              background-color: #f0f0f0;
              border: 2px dashed #333;
              transition: all 0.3s ease;
            }
            
            .sortable-ghost {
              opacity: 0;
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
                            <button wire:click="delete({{ $page }})">
                                <span class="material-symbols-outlined">
                                    delete
                                </span>
                            </button>
                            <div class="tooltip">
                                削除
                            </div>
                        </div>
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
                    this.style.boxShadow = '0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22)';
                    this.querySelector('.toolbar').style.display = 'flex';
                }
            });

            element.addEventListener('mouseout', function() {
                this.style.boxShadow = '';
                this.querySelector('.toolbar').style.display = '';
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