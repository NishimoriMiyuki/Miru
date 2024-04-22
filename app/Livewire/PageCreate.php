<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;

class PageCreate extends Component
{
    public $isOpen = false;
    
    #[Validate('required', message: 'タイトルは必須です。')]
    #[Validate('max:50', message: 'タイトルは最大50文字です。')]  
    public $title;
    
    #[Validate('max:2000', message: '内容は最大2000文字です。')]
    public $content;
    
    public function toggleIsOpen($value)
    {
        $this->resetValidation(['title', 'content']);
        $this->reset(['title', 'content']);
        $this->isOpen = $value;
    }
    
    public function create()
    {
        if (empty($this->title) && empty($this->content))
        {
            $this->toggleIsOpen(false);
            return;
        }
        
        $validated = $this->validate();
        $page = auth()->user()->pages()->create($validated);
        $this->toggleIsOpen(false);
        $this->dispatch('create-page', page: $page->id);
    }
    
    public function render()
    {
        return <<<'HTML'
        <div>
            @if(!$this->isOpen)
                <button wire:click="toggleIsOpen(true)" style="width: 500px; background-color: #fff; #ccc; padding: 6px 12px; cursor: text; text-align: left; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); border-radius: 10px;">メモの入力</button>
            @endif
            
            @if($this->isOpen)
            <div style="display: inline-block; width: 500px; border-radius: 10px; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);">
                @error('title') <span class="error text-red-500 font-bold">{{ $message }}</span> @enderror
                @error('content') <span class="error text-red-500 font-bold">{{ $message }}</span> @enderror
                <div style="text-align: right;">
                    <button wire:click="toggleIsOpen(false)">
                        <span class="material-symbols-outlined text-gray-500">
                            close
                        </span>
                    </button>
                </div>
                <div>
                    <input type="text" wire:model="title" maxlength="50" placeholder="タイトルを入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none font-bold" style="width: 100%; font-size: 20px;" autofocus>
                </div>
                <div>
                    <textarea wire:model="content" rows="2" maxlength="2000" placeholder="内容を入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none resize-none font-bold" style="width: 100%;"></textarea>
                </div>
                <button wire:click="create" style="border: 1px solid; padding: 0 5px; border-radius: 5px;" class="text-gray-500">作成</button>
            </div>
            @endif
        </div>
        HTML;
    }
}
