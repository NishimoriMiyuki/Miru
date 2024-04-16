<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Attributes\Validate; 
use Livewire\Component;

class PageEditor extends Component
{
    public Page $page;
    
    #[Validate('string', message: '文字を入力して下さい')]
    #[Validate('required', message: 'タイトルは必須です')]
    #[Validate('max:50', message: 'タイトルは最大255文字です')]  
    public $title;
    
    #[Validate('string', message: '文字を入力して下さい')]
    #[Validate('required', message: '内容は必須です')]
    #[Validate('max:1000', message: '内容はは最大1000文字です')]
    public $content;
    
    public function mount(Page $page)
    {
        $this->authorize('update', $page);
        
        $this->page = $page;
        $this->title = $this->page->title;
        $this->content = $this->page->content;
    }
    
    public function updatedTitle()
    {
        $this->authorize('update', $this->page);
        $this->validateOnly('title');
        $this->page->title = $this->title;
        $this->page->save();
        
        $this->dispatch('update-page-title');
    }
    
    public function updatedContent()
    {
        $this->authorize('update', $this->page);
        $this->validateOnly('content');
        $this->page->content = $this->content;
        $this->page->save();
    }
    
    public function render()
    {
        return <<<'HTML'
        <x-slot name="header">
            <x-page-header />
        </x-slot>
        
        <div class="w-1/2 h-full pt-4">
            <div class="flex flex-col">
                <input type="text" wire:model.live.debounce.500ms="title" placeholder="タイトルを入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none text-3xl font-semibold font-bold pr-10" value="{{ $page->title }}">
                <textarea wire:model.live.debounce.500ms="content" rows="30" placeholder="内容を入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none resize-none font-bold resize-none">{{ $page->content }}</textarea>
                
                <div class="bg-white pt-2">
                    編集日時: {{ $page->updated_at }}
                </div>
                
                <livewire:page-tool-button-group :page="$page" />
            </div>
        </div>
        HTML;
    }
}
