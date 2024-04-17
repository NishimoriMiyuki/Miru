<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Attributes\Validate; 
use Livewire\Component;

class PageEditor extends Component
{
    public Page $page;
    
    #[Validate('required', message: 'タイトルは必須です')]
    #[Validate('max:50', message: 'タイトルは最大50文字です')]   
    public $title;
    
    #[Validate('max:2000', message: '内容は最大2000文字です')]
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
                @error('title') <span class="error text-red-500 font-bold">{{ $message }}</span> @enderror
                @error('content') <span class="error text-red-500 font-bold">{{ $message }}</span> @enderror
            <div class="flex flex-col border border-gray-300 p-2 rounded-lg">
                <input type="text" wire:model.live.debounce.500ms="title" maxlength="50" placeholder="タイトルを入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none text-3xl font-semibold font-bold pr-10" value="{{ $page->title }}">
                <textarea wire:model.live.debounce.500ms="content" rows="30" maxlength="2000" placeholder="内容を入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none resize-none font-bold resize-none">{{ $page->content }}</textarea>
                
                <div class="bg-white pt-2">
                    編集日時: {{ $page->updated_at }}
                </div>
                
                <livewire:page-tool-button-group :page="$page" />
            </div>
        </div>
        HTML;
    }
}
