<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;

class PageCreate extends Component
{
    public $isOpen = false;
    
    #[Validate('required', message: '内容は必須です。')]
    #[Validate('max:2000', message: '内容は最大2000文字です。')]
    public $content;
    
    public function toggleIsOpen($value)
    {
        $this->resetValidation(['content']);
        $this->reset(['content']);
        $this->isOpen = $value;
    }
    
    public function create()
    {
        if (empty($this->content))
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
            <style>
                .create-bar {
                    width: 600px;
                    height: 46px;
                    background-color: #fff;
                    padding: 6px 12px;
                    cursor: text;
                    text-align: left;
                    box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5);
                    border-radius: 10px;
                    font-size: 14px;
                }
                
                .create-edit {
                    display: inline-block;
                    width: 600px;
                    border-radius: 10px; 
                    box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5);
                    text-align: right;
                    font-size: 14px;
                }
                
                .create-textarea {
                    border-color: transparent;
                    outline: none;
                    resize: none;
                    width: 100%;
                    border-radius: 10px;
                    font-size: 14px;
                }
                
                .create-textarea:focus {
                    border-color: transparent;
                    outline: none;
                    box-shadow: none;
                }
                
                .tool-button {
                    padding: 4px 24px;
                    border-radius: 5px;
                }
                
                .tool-button:hover {
                    background-color: #f2f2f2;
                }
                
            </style>
            
            @if(!$this->isOpen)
                <button wire:click="toggleIsOpen(true)" class="create-bar">メモを入力...</button>
            @endif
            
            @if($this->isOpen)
            <div class="create-edit">
                @error('content') <span class="error text-red-500 font-bold">{{ $message }}</span> @enderror
                <div>
                    <textarea wire:model="content" rows="3" maxlength="2000" placeholder="メモを入力..." class="create-textarea" autofocus></textarea>
                </div>
                <button class="tool-button" wire:click="create">作成</button>
                <button class="tool-button" wire:click="toggleIsOpen(false)">閉じる</button>
            </div>
            @endif
        </div>
        HTML;
    }
}
