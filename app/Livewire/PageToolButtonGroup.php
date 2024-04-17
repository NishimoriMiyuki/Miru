<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;

class PageToolButtonGroup extends Component
{
    public Page $page;
    public $isFavorite;
    public $isPublic;
    
    public function mount(Page $page)
    {
        $this->page = $page;
        $this->isFavorite = $this->page->is_favorite;
        $this->isPublic = $this->page->is_public;
    }
    
    public function toggleFavorite()
    {
        $this->isFavorite = !$this->isFavorite;
        
        $this->authorize('update', $this->page);
        $this->page->is_favorite = $this->isFavorite;
        $this->page->save();
    }
    
    public function togglePublic()
    {
        $this->isPublic = !$this->isPublic;
        
        $this->authorize('update', $this->page);
        $this->page->is_public = $this->isPublic;
        $this->page->save();
    }
    
    public function delete(\Illuminate\Http\Request $request)
    {
        $this->authorize('delete', $this->page);
        $this->page->delete();
        
        $type = $request->session()->get('type', 'private');

        return redirect()->route('pages.index', ['type' => $type]);
    }
    
    public function forceDelete(\Illuminate\Http\Request $request)
    {
        $this->authorize('forceDelete', $this->page);
        $this->page->forceDelete();
        
        $type = $request->session()->get('type', 'private');
        return redirect()->route('pages.index', ['type' => $type]);
    }
    
    public function restore(\Illuminate\Http\Request $request)
    {
        $this->authorize('restore', $this->page);
        $this->page->restore();
        
        $type = $request->session()->get('type', 'private');
        return redirect()->route('pages.index', ['type' => $type]);
    }
    
    public function render()
    {
        if ($this->page->trashed())
        {
            return <<<'HTML'
            <div class="flex bg-white pt-2 text-gray-500">
                <div class="relative group">
                    <button wire:click="restore" class="px-2 py-1">
                        <span class="material-symbols-outlined">
                            restore_from_trash
                        </span>
                    </button>
                    <x-tooltip>
                        復元する
                    </x-tooltip>
                </div>
                <div class="relative group">
                    <button wire:click="forceDelete" class="px-2 py-1" wire:confirm="完全に削除されます。よろしいですか？">
                        <span class="material-symbols-outlined">
                            delete_forever
                        </span>
                    </button>
                    <x-tooltip>
                        完全に削除する
                    </x-tooltip>
                </div>
            </div>
            HTML;
        }
        else 
        {
            return <<<'HTML'
            <div class="flex bg-white pt-2 text-gray-500">
                <div class="relative group">
                    <button wire:click="delete" class="px-2 py-1">
                        <span class="material-symbols-outlined">
                            delete
                        </span>
                    </button>
                    <x-tooltip>
                        ゴミ箱に入れる
                    </x-tooltip>
                </div>
                <div class="relative group">
                    <button wire:click="toggleFavorite" class="px-2 py-1">
                        @if($isFavorite)
                            <span class="material-symbols-outlined">
                                heart_check
                            </span>
                        @else
                            <span class="material-symbols-outlined">
                                favorite
                            </span>
                        @endif
                    </button>
                    <x-tooltip>
                        @if($isFavorite)
                            お気に入り解除
                        @else
                            お気に入り登録
                        @endif
                    </x-tooltip>
                </div>
                <div class="relative group">
                    <button wire:click="togglePublic" class="px-2 py-1" wire:confirm="{{ $isPublic ? '貴方だけが閲覧できる設定に変更します。非公開にしますか？' : '全てのユーザーがこのメモを閲覧できる設定に変更します。本当に公開しますか？' }}">
                        @if($isPublic)
                            <span class="material-symbols-outlined">
                                public
                            </span>
                        @else
                            <span class="material-symbols-outlined">
                                public_off
                            </span>
                        @endif
                    </button>
                    <x-tooltip>
                        @if($isPublic)
                            非公開にする
                        @else
                            公開する
                        @endif
                    </x-tooltip>
                </div>
            </div>
            HTML;
        }
    }
}
