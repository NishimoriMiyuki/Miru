<x-slot name="header">
    <x-page-header />
</x-slot>
    
<div class="container">
    @if($pages->isEmpty())
    <div style="padding: 50px 0; font-size: 22px;">
        <p>ゴミ箱にメモがありません</p>
    </div>
    @else
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
                box-shadow: 0 0 5px 1px rgba(0,0,0,0.3);
            }
                        
            .page-header {
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
            
            .tool-button {
                padding: 4px 24px;
                border-radius: 5px;
            }
            
            .tool-button:hover {
                background-color: #f2f2f2;
            }
        </style>
        
        <div>
            <button wire:click="emptyTrash" class="tool-button" style="color: #1a73e8; font-size: 14px;" wire:confirm="ゴミ箱を空にしますか？ゴミ箱内のメモはすべて完全に削除されます。">ゴミ箱を空にする</button>
        </div>
        <div class="page-container">
            @foreach($pages as $page)
                <div class="page" wire:key="page-{{ $page->id }}">
                    <div class="page-header">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button type="button" class="tool">
                                    <span class="material-symbols-outlined">
                                        arrow_drop_down
                                    </span>
                                </button>
                            </x-slot>
                                
                            <x-slot name="content">
                                <button wire:click="restore({{ $page->id }})" style="font-size: 14px;" class="text-gray-600 drop-down-button">
                                    メモを復元
                                </button>
                                <button wire:click="forceDelete({{ $page->id }})" wire:confirm="永久に削除しますか？" style="font-size: 14px;" class="text-gray-600 drop-down-button">
                                    永久に削除
                                </button>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    <div class="text-content">
                        <a wire:click="show({{ $page }})" style="cursor: pointer;">
                            <p class="font-bold">{{ $page->firstLine }}</p>
                            <p>{{ $page->restOfContent }}</p>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    
    @if($isShow)
    <div class="modal-overlay">
        <div class="modal-content">
            <div class="flex flex-col" style="width: 500px; max-width: 100vw;">
                <div class="flex justify-end">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button type="button" class="tool">
                                <span class="material-symbols-outlined">
                                    arrow_drop_down
                                </span>
                            </button>
                        </x-slot>
                            
                        <x-slot name="content">
                            <button wire:click="restore({{ $selectPage->id }})" style="font-size: 14px;" class="text-gray-600 drop-down-button">
                                メモを復元
                            </button>
                            <button wire:click="forceDelete({{ $selectPage->id }})" wire:confirm="永久に削除しますか？" style="font-size: 14px;" class="text-gray-600 drop-down-button">
                                メモを永久削除
                            </button>
                        </x-slot>
                    </x-dropdown>
                    
                </div>
                
                <textarea 
                    name="content"
                    x-init="$nextTick(() => resize())"
                    x-data="{
                                resize() {
                                    $el.style.height = '0px';
                                    $el.style.height = $el.scrollHeight + 'px';
                                }
                            }"
                    placeholder="内容を入力" 
                    class="border-0 border-transparent focus:border-transparent focus:ring-0 focus:outline-none resize-none"
                    style="overflow: auto; max-height: 70vh"
                    readonly>{{ $selectPage->content }}</textarea>
                    
                <div class="bg-white pt-2" style="font-size: 12px;">
                    編集日時: {{ $selectPage->updated_at }}
                </div>
                
                <div style="display: flex; justify-content: flex-end;">
                    <button wire:click="$set('isShow', false)" class="tool-button" style="font-size: 14px;">
                        閉じる
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>