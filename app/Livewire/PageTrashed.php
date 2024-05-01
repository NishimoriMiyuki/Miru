<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Page;

class PageTrashed extends Component
{
    public $pages;
    public $selectPage;
    public $isShow = false;
    
    public function mount()
    {
        $this->pages = auth()->user()->getTrashedPages();
    }
    
    public function forceDelete($pageId)
    {
        $page = Page::onlyTrashed()->where('id', $pageId)->firstOrFail();
        
        $this->authorize('forceDelete', $page);
        $page->forceDelete();
    
        $this->pages = $this->pages->filter(function ($p) use ($pageId) {
            return $p->id !== $pageId;
        });
        
        $this->isShow = false;
        $this->selectPage = null;
        
        $this->dispatch('toaster', [ 'message' => '削除しました' ]);
    }
    
    public function restore($pageId)
    {
        $page = Page::onlyTrashed()->where('id', $pageId)->firstOrFail();
        
        $this->authorize('restore', $page);
        $page->restore();
    
        $this->pages = $this->pages->filter(function ($p) use ($pageId) {
            return $p->id !== $pageId;
        });
        
        $this->isShow = false;
        $this->selectPage = null;
        
        $this->dispatch('toaster', [ 'message' => '復元しました' ]);
    }
    
    public function show($pageId)
    {
        $page = Page::onlyTrashed()->where('id', $pageId)->firstOrFail();
        $this->authorize('view', $page);
        $this->selectPage = $page;
        $this->isShow = true;
    }
    
    public function render()
    {
        return view('livewire.page-trashed');
    }
}
