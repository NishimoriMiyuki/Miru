<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Board;

class BoardTrashed extends Component
{
    public $boards;
    public $selectBoard;
    
    public function mount()
    {
        $this->boards = auth()->user()->getTrashedBoards();
    }
    
    public function forceDelete($id)
    {
        $board = $this->boards->firstWhere('id', $id);
        
        if(!$board)
        {
            $this->selectBoard = null;
            return;
        }
        
        $this->authorize('forceDelete', $board);
        $board->forceDelete();
        $this->boards = $this->boards->except($id);
        
        $this->selectBoard = null;
        
        $this->dispatch('toaster', [ 'message' => '削除しました' ]);
    }
    
    public function restore($id)
    {
        $board = $this->boards->firstWhere('id', $id);
        
        if(!$board)
        {
            $this->selectBoard = null;
            return;
        }
        
        $this->authorize('restore', $board);
        $board->restore();
        $this->boards = $this->boards->except($id);
        
        $this->selectBoard = null;
        
        $this->dispatch('toaster', [ 'message' => '復元しました' ]);
    }
    
    public function render()
    {
        return view('livewire.board-trashed');
    }
}
