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
        <x-slot name="header">
            <x-page-header />
        </x-slot>
        
        <x-slot name="alert">
            このページはゴミ箱に入っています。{{ $page->deleted_at->format('Y年m月d日') }}に削除されました。 
        </x-slot>
            
        <div class="w-1/2 h-full pt-4">
            <div class="flex flex-col border border-gray-300 p-2 rounded-lg">
                <input type="text" name="title" placeholder="タイトルを入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none text-3xl font-semibold font-bold pr-10" value="{{ $page->title }}" readonly>
                <textarea name="content" rows="30" placeholder="内容を入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none resize-none font-bold resize-none" readonly>{{ $page->content }}</textarea>
                    
                <div class="bg-white pt-2">
                    編集日時: {{ $page->updated_at }}
                </div>
                    
                <livewire:page-tool-button-group :page="$page" />
            </div>
        </div>
        HTML;
    }
}
