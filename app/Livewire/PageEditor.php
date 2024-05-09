<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Attributes\Validate; 
use Livewire\Component;

class PageEditor extends Component
{
    public $page;
    
    #[Validate('required', message: '内容は必須です')]
    #[Validate('max:2000', message: '内容は最大2000文字です')]
    public $content;
    
    public $isFavorite;
    public $isPublic;
    
    public function mount(Page $page)
    {
        $this->authorize('update', $this->page);
        
        $this->page = $page;
        $this->content = $this->page->content;
        $this->isFavorite = $this->page->is_favorite;
        $this->isPublic = $this->page->is_public;
    }
    
    public function updatedContent()
    {
        $this->authorize('update', $this->page);
        $this->validateOnly('content');
        $this->page->content = $this->content;
        $this->page->save();
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
    
    public function delete()
    {
        $this->authorize('delete', $this->page);
        $this->page->delete();
        
        $this->close();
        $this->dispatch('delete-page', pageId: $this->page->id);
    }
    
    public function forceDelete()
    {
        $this->authorize('forceDelete', $this->page);
        $this->page->forceDelete();
        
        $this->close();
    }
    
    public function restore()
    {
        $this->authorize('restore', $this->page);
        $this->page->restore();
        
        $this->close();
    }
    
    public function close()
    {
        $this->dispatch('close-edit');
    }
    
    public function render()
    {
        $this->dispatch('resize-textarea');
        return view('livewire.page-editor');
    }
}
