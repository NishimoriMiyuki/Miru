<x-slot name="header">
    <x-board-header />
</x-slot>

<div class="container">
    <style>
        .board-container {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
          gap: 8px 4px;
          padding: 50px 0;
        }
        
        .board-container > * {
            flex: 0 0 240px;
        }
        
        .board {
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
        
        .board:hover {
            z-index: 999;
        }
        
        .board-header {
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
    
    <!-- ボード作成 -->
    <div>
        @if(!$isOpen)
            <button wire:click="toggleIsOpen(true)" class="create-bar">ボードの作成...</button>
        @else
            <div class="create-edit">
                @error('name') <span class="error text-red-500 font-bold">{{ $message }}</span> @enderror
                <div>
                    <textarea name="name" wire:model="name" maxlength="255" placeholder="ボード名を入力..." class="create-textarea" autofocus></textarea>
                </div>
                <button class="tool-button" wire:click="create">作成</button>
                <button class="tool-button" wire:click="toggleIsOpen(false)">閉じる</button>
            </div>
        @endif
    </div>
    
    @if($boards->isEmpty())
        <div style="padding: 50px 0; font-size: 22px;">
            <p>ボードがありません</p>
        </div>
    @else
        <!-- ボード一覧 -->
        <div class="board-container" wire:sortable="updateBoardOrder" wire:sortable.options="{chosenClass: 'sortable-chosen', ghostClass: 'sortable-ghost'}">
            @foreach($boards as $board)
                <div class="board" wire:sortable.item="{{ $board->id }}" wire:key="board-{{ $board->id }}">
                    <div class="board-header" wire:sortable.handle>
                        <div style="font-size: 12px; display: flex; justify-content: space-between; gap: 2px;">
                            <!-- スペース確保 -->
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
                                <button wire:click="delete({{ $board->id }})" style="font-size: 14px;" class="text-gray-600 drop-down-button">
                                    ボードを削除する
                                </button>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    <div class="text-content">
                        <a href="{{ route('boards.edit', ['board' => $board]) }}">
                            <p>{{ \Illuminate\Support\Str::limit($board->name, 20) }}</p>
                        </a>
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
                }
            });

            element.addEventListener('mouseout', function() {
                this.style.boxShadow = '';
            });
        }
    }

    document.querySelectorAll('.board').forEach(setupListeners);
</script>
@endscript