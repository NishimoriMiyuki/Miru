<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;

class PageDeletedShow extends Component
{
    public $page;
    
    public function mount($page)
    {
        $page = Page::withTrashed()->findOrFail($page);
        $this->authorize('view', $page);
        
        $this->page = $page;
    }
    
    public function render()
    {
        return <<<'HTML'
        <div class="flex flex-col" style="width: 1000px; height: 800px;">
            <input type="text" name="title" placeholder="タイトルを入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none text-3xl font-semibold font-bold pr-10" value="{{ $page->title }}" readonly>
            <textarea name="content" rows="30" placeholder="内容を入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none resize-none font-bold resize-none" readonly>{{ $page->content }}</textarea>
                    
            <div class="bg-white pt-2">
                編集日時: {{ $page->updated_at }}
            </div>
            
            <div class="toolbar">
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
        HTML;
    }
}
