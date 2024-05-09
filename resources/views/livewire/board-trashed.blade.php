<x-slot name="header">
    <x-board-header />
</x-slot>

<x-slot name="alert">
    ゴミ箱内のボードは 7 日後に削除されます。
</x-slot>
    
<div class="h-full container">
    @if($boards->isEmpty())
        <p>ゴミ箱にはボードがありません。</p>
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
            
            .text-content {
                max-height: calc(100% - 20px);
                overflow: hidden;
                position: relative;
                word-break: break-all;
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
        </style>
        
        <div class="board-container">
            @foreach($boards as $board)
                <div class="board">
                    <div class="text-content">
                        <p class="font-bold">{{ $board->name }}</p>
                    </div>
                    <div class="toolbar text-gray-300">
                        <div class="tool">
                            <button wire:click="forceDelete({{ $board->id }})" wire:confirm="永久に削除しますか？">
                                <span class="material-symbols-outlined">
                                    delete_forever
                                </span>
                            </button>
                            <div class="tooltip">
                                削除
                            </div>
                        </div>
                        <div class="tool">
                            <button wire:click="restore({{ $board->id }})">
                                <span class="material-symbols-outlined">
                                    restore_from_trash
                                    </span>
                            </button>
                            <div class="tooltip">
                                復元
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
    document.querySelectorAll('.board').forEach(function(board) {
        board.addEventListener('mouseover', function() {
            this.style.boxShadow = '0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22)';
            this.querySelector('.toolbar').style.display = 'flex';
        });
        board.addEventListener('mouseout', function() {
            this.style.boxShadow = '';
            this.querySelector('.toolbar').style.display = '';
        });
    });
    
    document.querySelectorAll('.tool').forEach(function(tool) {
        tool.addEventListener('mouseover', function() {
            this.querySelector('.tooltip').style.display = 'block';
        });
        tool.addEventListener('mouseout', function() {
            this.querySelector('.tooltip').style.display = '';
        });
    });
</script>
@endscript