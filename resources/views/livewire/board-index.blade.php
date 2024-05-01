<x-slot name="header">
    <x-board-header :title="'ボード'" />
</x-slot>

<div class="h-full container">
    <style>
        .board-container {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(225px, 1fr));
          gap: 20px;
          padding: 50px 0;
        }
        
        .board-container > * {
            flex: 0 0 225px;
        }
        
        .board {
            position: relative;
            background-color: #fff;
            border-radius: 8px;
            border: 1px solid #D1D5DB;
            padding: 20px;
            transition: all 0.3s cubic-bezier(.25,.8,.25,1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 225px;
            height: 225px;
        }
        
        .name-content {
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
        
        .board .toolbar {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
            position: absolute;
            bottom: 0;
            right: 0;
        }
        
        .sortable-chosen {
          opacity: 0.5;
          background-color: #f0f0f0;
          transition: all 0.3s ease;
        }
        
        .sortable-ghost {
          opacity: 0;
        }
        
        .create-bar {
            width: 600px;
            height: 46px;
            background-color: #fff;
            padding: 6px 12px;
            cursor: text;
            text-align: left;
            box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            font-size: 14px;
        }
        
        .create-edit {
            display: inline-block;
            width: 600px;
            border-radius: 10px; 
            box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5);
            text-align: right;
            font-size: 14px;
        }
        
        .create-textarea {
            border-color: transparent;
            outline: none;
            resize: none;
            width: 100%;
            border-radius: 10px;
            font-size: 14px;
        }
        
        .create-textarea:focus {
            border-color: transparent;
            outline: none;
            box-shadow: none;
        }
        
        .tool-button {
            padding: 4px 24px;
            border-radius: 5px;
        }
        
        .tool-button:hover {
            background-color: #f2f2f2;
        }
    </style>
    
    <!-- ボード作成 -->
    <div>
        @if(!$isOpen)
            <button wire:click="toggleIsOpen(true)" class="create-bar">ボードの作成...</button>
        @else
            <div class="create-edit">
                @error('name') <span class="error text-red-500 font-bold">{{ $message }}</span> @enderror
                <div>
                    <textarea wire:model="name" maxlength="255" placeholder="ボード名を入力..." class="create-textarea" autofocus></textarea>
                </div>
                <button class="tool-button" wire:click="create">作成</button>
                <button class="tool-button" wire:click="toggleIsOpen(false)">閉じる</button>
            </div>
        @endif
    </div>
    
    @if($boards->isEmpty())
        <p>ボードがありません。</p>
    @else
        <!-- ボード一覧 -->
        <div class="board-container" wire:sortable="updateBoardOrder" wire:sortable.options="{chosenClass: 'sortable-chosen', ghostClass: 'sortable-ghost'}">
            @foreach($boards as $board)
                <div class="board" wire:sortable.item="{{ $board->id }}" wire:key="board-{{ $board->id }}">
                    <div class="drag-handle" wire:sortable.handle>
                        <span class="material-symbols-outlined">
                        drag_handle
                        </span>
                    </div> 
                    <div class="text-content">
                        <a href="{{ route('boards.edit', ['board' => $board]) }}">
                            <p>{{ \Illuminate\Support\Str::limit($board->name, 20) }}</p>
                        </a>
                    </div>
                    <div class="toolbar">
                        <div class="tool">
                            <button wire:click="delete({{ $board->id }})">
                                <span class="material-symbols-outlined">
                                    delete
                                </span>
                            </button>
                            <div class="tooltip">
                                削除
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
        if (element.matches('.board')) {
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
    
    document.querySelectorAll('.board, .tool').forEach(setupListeners);
    
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    setupListeners(node);
                    node.querySelectorAll('.board, .tool').forEach(setupListeners);
                }
            });
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });
</script>
@endscript