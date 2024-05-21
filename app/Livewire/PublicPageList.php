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
        $query = Page::isPublic(true)->updatedOrder();

        if ($this->searchTerm) {
            // 全角スペースと半角スペースで分割
            $keywords = preg_split('/[\s　]+/u', $this->searchTerm, -1, PREG_SPLIT_NO_EMPTY);
            
            foreach ($keywords as $keyword) {
                $query->where('content', 'like', '%' . $keyword . '%');
            }
        }

        $pages = $query->paginate(10);

        return view('livewire.public-page-list', [
            'pages' => $pages,
        ]);
    }
}