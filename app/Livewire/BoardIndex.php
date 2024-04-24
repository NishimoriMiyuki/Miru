<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\Board;

class BoardIndex extends Component
{
    #[Validate('required', message: 'ボード名は必須です。')]
    #[Validate('max:255', message: 'ボード名は最大255文字です。')]  
    public $name;
    
    public $boards;
    
    public $isOpen = false;
    
    public function mount()
    {
        $this->boards = auth()->user()->getOrderBoards();
    }
    
    public function updateBoardOrder($order)
    {
        $cases = [];
        foreach ($order as $item) {
            $cases[] = "WHEN id = {$item['value']} THEN {$item['order']}";
        }
    
        $casesString = implode(' ', $cases);
    
        \DB::update("
            UPDATE boards
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
        
        $this->boards = $this->boards->sortBy(function ($board) use ($orderWithIdAsKey) {
            return $orderWithIdAsKey[$board->id];
        });
    }
    
    public function toggleIsOpen($value)
    {
        $this->resetValidation(['name']);
        $this->reset(['name']);
        $this->isOpen = $value;
    }
    
    public function create()
    {
        if(empty($this->name))
        {
            $this->toggleIsOpen(false);
            return;
        }
        
        $validated = $this->validate();
        $board = auth()->user()->boards()->create($validated);
        $this->boards->prepend($board);
        $this->toggleIsOpen(false);
    }
    
    public function render()
    {
        return view('livewire.board-index');
    }
}
