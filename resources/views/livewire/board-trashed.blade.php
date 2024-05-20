<x-slot name="header">
    <x-header title="ボード">
        <x-slot name="content">
            <a href="{{ route('boards.index') }}" class="grid-item">ボード</a>
            <a href="{{ route('pages.index') }}" class="grid-item">メモ</a>
            <a href="{{ route('pages.public') }}" class="grid-item">公開メモ</a>
            <a href="{{ route('boards.trashed') }}" class="grid-item">ゴミ箱</a>
        </x-slot>
    </x-header>
</x-slot>
    
<div class="container">
    @if($boards->isEmpty())
    <div style="padding: 50px 0; font-size: 22px;">
        <p>ゴミ箱にボードがありません</p>
    </div>
    @else
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
                box-shadow: 0 0 5px 1px rgba(0,0,0,0.3);
            }
            
            .board-header {
                display: flex;
                justify-content: flex-end;
            }
            
            .text-content {
                flex-grow: 1;
                max-height: calc(100% - 20px);
                overflow: hidden;
                position: relative;
                word-break: break-all;
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
            
            .tool-button {
                padding: 4px 24px;
                border-radius: 5px;
            }
            
            .tool-button:hover {
                background-color: #f2f2f2;
            }
        </style>
        
        <div>
            <button wire:click="emptyTrash" class="tool-button" style="color: #1a73e8; font-size: 14px;" wire:confirm="ゴミ箱を空にしますか？ゴミ箱内のボードはすべて完全に削除されます。">ゴミ箱を空にする</button>
        </div>
        <div class="board-container">
            @foreach($boards as $board)
                <div class="board">
                    <div class="board-header">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button type="button" class="tool">
                                    <span class="material-symbols-outlined">
                                        arrow_drop_down
                                    </span>
                                </button>
                            </x-slot>
                                
                            <x-slot name="content">
                                <button wire:click="restore({{ $board->id }})" style="font-size: 14px;" class="text-gray-600 drop-down-button">
                                    ボードを復元
                                </button>
                                <button wire:click="forceDelete({{ $board->id }})" wire:confirm="永久に削除しますか？" style="font-size: 14px;" class="text-gray-600 drop-down-button">
                                    永久に削除
                                </button>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    <div class="text-content">
                        <p class="font-bold">{{ $board->name }}</p>
                        <p style="font-size: 12px; text-align: right;">問題数：{{ $board->boardRows->count() }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>