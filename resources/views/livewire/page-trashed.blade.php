<x-slot name="header">
    <x-page-header :title="'メモ（ゴミ箱）'" />
</x-slot>

<x-slot name="alert">
    ゴミ箱内のメモは 7 日後に削除されます。
</x-slot>
    
<div class="h-full container">
    @if($pages->isEmpty())
        <p>ゴミ箱にはメモがありません。</p>
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
            
            .page .toolbar {
                position: absolute;
                bottom: 0;
                right: 0;
                display: none;
            }
        </style>
        
        <div class="page-container">
            @foreach($pages as $page)
                <div class="page">
                    <div class="text-content">
                        <a wire:click="show({{ $page->id }})">
                            <p class="font-bold">{{ $page->firstLine }}</p>
                            <p>{{ $page->restOfContent }}</p>
                        </a>
                    </div>
                    <div class="toolbar text-gray-300">
                        <div class="tool">
                            <button wire:click="forceDelete({{ $page->id }})" wire:confirm="永久に削除しますか？">
                                <span class="material-symbols-outlined">
                                    delete_forever
                                </span>
                            </button>
                            <div class="tooltip">
                                削除
                            </div>
                        </div>
                        <div class="tool">
                            <button wire:click="restore({{ $page->id }})">
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
    
    @if($isShow)
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 9999;">
        <div style="background-color: white; padding: 20px; border-radius: 8px;">
            <div class="flex flex-col" style="width: 1000px; height: 800px;">
                <textarea name="content" rows="30" placeholder="内容を入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none resize-none font-bold resize-none" readonly>{{ $selectPage->content }}</textarea>
                        
                <div class="bg-white pt-2">
                    編集日時: {{ $page->updated_at }}
                </div>
                
                <div class="toolbar">
                    <div class="tool">
                        <button wire:click="forceDelete({{ $selectPage->id }})" wire:confirm="永久に削除しますか？">
                            <span class="material-symbols-outlined">
                                delete_forever
                            </span>
                        </button>
                        <div class="tooltip">
                            削除
                        </div>
                    </div>
                    <div class="tool">
                        <button wire:click="restore({{ $selectPage->id }})">
                            <span class="material-symbols-outlined">
                                restore_from_trash
                            </span>
                        </button>
                        <div class="tooltip">
                            復元
                        </div>
                    </div>
                </div>
                
                <button wire:click="$set('isShow', false)" style="cursor: pointer;">
                    閉じる
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

@script
<script>
    document.querySelectorAll('.page').forEach(function(page) {
        page.addEventListener('mouseover', function() {
            this.style.boxShadow = '0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22)';
            this.querySelector('.toolbar').style.display = 'flex';
        });
        page.addEventListener('mouseout', function() {
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