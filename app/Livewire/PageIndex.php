<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Page;

class PageIndex extends Component
{
    public $pages;
    public $selectedPage;
    public $isEditOpen = false;
    
    public function mount()
    {
        $this->pages = auth()->user()->getOrderPages();
    }
    
    public function edit(Page $page)
    {
        $this->authorize('update', $page);
        $this->selectedPage = $page;
        $this->isEditOpen = true;
    }
    
    #[On('close-edit')]
    public function closeEdit()
    {
        $this->isEditOpen = false;
    }
    
    #[On('delete-page')]
    public function removePage($pageId)
    {
        $this->pages = $this->pages->reject(function ($page) use ($pageId) {
            return $page->id == $pageId;
        });
    }
    
    public function updatePageOrder($order)
    {
        $cases = [];
        foreach ($order as $item) {
            $cases[] = "WHEN id = {$item['value']} THEN {$item['order']}";
        }
    
        $casesString = implode(' ', $cases);
    
        \DB::update("
            UPDATE pages
            SET `order` = CASE
                {$casesString}
                ELSE `order`
            END
            WHERE id IN (" . implode(',', array_column($order, 'value')) . ")
        ");
    
        $orderWithIdAsKey = [];
        foreach ($order as $item) {
            $orderWithIdAsKey[$item['value']] = $item['order'];
        }
    
        $this->pages = $this->pages->sortBy(function ($page) use ($orderWithIdAsKey) {
            return $orderWithIdAsKey[$page->id];
        });
    }
    
    #[On('create-page')]
    public function addPage($page)
    {
        $this->pages->prepend(auth()->user()->pages()->find($page));
    }
    
    public function toggleFavorite(Page $page)
    {
        $this->authorize('update', $page);
        $page->is_favorite = !$page->is_favorite;
        $page->save();
    
        $this->pages = $this->pages->map(function ($p) use ($page) {
            return $p->id === $page->id ? $page->fresh() : $p;
        });
    }
    
    public function togglePublic(Page $page)
    {
        $this->authorize('update', $page);
        $page->is_public = !$page->is_public;
        $page->save();
        
        $this->pages = $this->pages->map(function ($p) use ($page) {
        return $p->id === $page->id ? $page : $p;
        });
    }
    
    public function delete(Page $page)
    {
        $this->authorize('delete', $page);
        $page->delete();
    
        $this->pages = $this->pages->filter(function ($p) use ($page) {
            return $p->id !== $page->id;
        });
    }
    
    public function render()
    {
        return view('livewire.page-index', ['pages' => $this->pages]);
    }
}
