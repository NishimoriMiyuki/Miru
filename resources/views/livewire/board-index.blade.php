<x-slot name="header">
    <x-board-header :title="'ボード（一覧）'" />
</x-slot>

<div class="h-full container">
    <div wire:loading style="position: absolute; z-index: 9999;">
        <div class="loader">Loading...</div>
    </div>
    
    <!-- ボード作成 -->
    <div>
        @if(!$isOpen)
            <button wire:click="toggleIsOpen(true)" style="width: 500px; background-color: #fff; #ccc; padding: 6px 12px; cursor: text; text-align: left; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); border-radius: 10px;">ボードの作成...</button>
        @else
            <div style="display: inline-block; width: 500px; border-radius: 10px; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);">
                @error('name') <span class="error text-red-500 font-bold">{{ $message }}</span> @enderror
                <div style="text-align: right;">
                    <button wire:click="toggleIsOpen(false)">
                        <span class="material-symbols-outlined text-gray-500">
                            close
                        </span>
                    </button>
                </div>
                <div>
                    <input type="text" wire:model="name" maxlength="255" placeholder="ボード名を入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none" style="width: 100%;" autofocus>
                </div>
                <button wire:click="create" style="border: 1px solid; padding: 0 5px; border-radius: 5px;" class="text-gray-500">作成</button>
            </div>
        @endif
    </div>
    
    @if($boards->isEmpty())
        <p>ボードがありません。</p>
    @else
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
                box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
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
                position: absolute;
                bottom: 0;
                right: 0;
                display: none;
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
                            <button wire:click="delete({{ $board }})">
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