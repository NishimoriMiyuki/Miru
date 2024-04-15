<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Page;

class PageEditor extends Component
{
    public Page $page;
    
    #[Validate('required|max:255')] 
    public $title;
    
    #[Validate('required|max:2000')]
    public $content;
    
    public function mount(Page $page)
    {
        $this->page = $page;
        $this->title = $page->title;
        $this->content = $page->content;
    }
    
    public function updatedTitle()
    {
        $this->validate();
        
        $this->page->title = $this->title;
        $this->page->save();
    }
    
    public function updatedContent()
    {
        $this->validate();
        
        $this->page->content = $this->content;
        $this->page->save();
    }
    
    public function render()
    {
        return view('livewire.page-editor');
    }
}
