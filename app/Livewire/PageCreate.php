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
        auth()->user()->pages()->create($validated);
        $this->toggleIsOpen(false);
    }
    
    public function render()
    {
        return <<<'HTML'
        <div>
            @if(!$this->isOpen)
                <button wire:click="toggleIsOpen(true)">＋新規メモ</button>
            @endif
            
            @if($this->isOpen)
                <div class="h-full pt-4">
                        @error('title') <span class="error text-red-500 font-bold">{{ $message }}</span> @enderror
                        @error('content') <span class="error text-red-500 font-bold">{{ $message }}</span> @enderror
                    <div class="flex flex-col border border-gray-300 p-2 rounded-lg">
                        <div class="flex justify-end">
                            <button wire:click="toggleIsOpen(false)">
                                <span class="material-symbols-outlined text-gray-500">
                                    close
                                </span>
                            </button>
                        </div>
                        <input type="text" wire:model="title" maxlength="50" placeholder="タイトルを入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none text-3xl font-semibold font-bold pr-10">
                        <textarea wire:model="content" rows="2" maxlength="2000" placeholder="内容を入力" class="border-transparent focus:border-transparent focus:ring-0 focus:outline-none resize-none font-bold resize-none"></textarea>
                        <button wire:click="create" class="font-bold rounded shadow text-gray-500">作成</button>
                    </div>
                </div>
            @endif
        </div>
        HTML;
    }
}
