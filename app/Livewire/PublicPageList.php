<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Page;

class PublicPageList extends Component
{
    use WithPagination;
    
    public $searchTerm = '';
    
    public function updatingSearchTerm()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $pages = Page::where('is_public', true)
            ->where('content', 'like', '%' . $this->searchTerm . '%')
            ->paginate(10);

        return view('livewire.public-page-list', [
            'pages' => $pages,
        ]);
    }
}