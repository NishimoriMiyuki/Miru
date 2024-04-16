<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;

class PageToolButtonGroup extends Component
{
    public Page $page;
    public $isFavorite;
    
    public function mount(Page $page)
    {
        $this->page = $page;
        $this->isFavorite = $this->page->is_favorite;
    }
    
    public function toggleFavorite()
    {
        $this->isFavorite = !$this->isFavorite;
        
        $this->authorize('update', $this->page);
        $this->page->is_favorite = $this->isFavorite;
        $this->page->save();
    }
    
    public function delete(\Illuminate\Http\Request $request)
    {
        $this->authorize('delete', $this->page);
        $this->page->delete();
        
        $type = $request->session()->get('type', 'private');

        return redirect()->route('pages.index', ['type' => $type]);
    }
    
    public function render()
    {
        return <<<'HTML'
        <div class="flex bg-white pt-2">
            <div class="relative group">
                <button wire:click="delete" class="px-2 py-1">🗑</button>
                <div class="whitespace-nowrap rounded bg-black px-2 py-1 text-white absolute -top-12 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition pointer-events-none">
                    ゴミ箱に入れる
                </div>
            </div>
            <div class="relative group">
                <button wire:click="toggleFavorite" class="px-2 py-1">
                    @if($isFavorite)
                        ★
                    @else
                        ✫
                    @endif
                </button>
                <div class="whitespace-nowrap rounded bg-black px-2 py-1 text-white absolute -top-12 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition pointer-events-none">
                    @if($isFavorite)
                        お気に入り解除
                    @else
                        お気に入り登録
                    @endif
                </div>
            </div>
            <div class="relative group">
                <button class="px-2 py-1">🌎</button>
                <div class="whitespace-nowrap rounded bg-black px-2 py-1 text-white absolute -top-12 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition pointer-events-none">
                    公開設定
                </div>
            </div>
        </div>
        HTML;
    }
}
