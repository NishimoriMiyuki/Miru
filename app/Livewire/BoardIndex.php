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
            // 各ボードのIDと新しい順序に基づいてCASE文の条件を作成し、$cases配列に追加
            $cases[] = "WHEN id = {$item['value']} THEN {$item['order']}";
        }
        
        // $cases配列の要素をスペースで連結して、CASE文の条件部分の文字列を作成
        $casesString = implode(' ', $cases);
        
        // SQLのUPDATEを実行して、ボードの順序を更新
        // CASEを使用して、各ボードのIDに対応する新しい順序を設定
        // WHEREで、$order配列に含まれるボードのIDだけを対象
        \DB::update("
            UPDATE boards
            SET `order` = CASE
                {$casesString}
                ELSE `order`
            END
            WHERE id IN (" . implode(',', array_column($order, 'value')) . ")
        ");
    
        // ボードのIDをキーにして、新しい順序を値とする連想配列を作成
        $orderWithIdAsKey = [];
        foreach ($order as $item) {
            $orderWithIdAsKey[$item['value']] = $item['order'];
        }
        
        // $this->boardsコレクションを、新しい順序に基づいてソート
        // ソートの基準は、各ボードのIDに対応する新しい順序
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
    
    public function delete($id)
    {
        $board = $this->boards->firstWhere('id', $id);
        
        if(!$board)
        {
            return;
        }
        
        $board->delete();
        $this->boards = $this->boards->except($id);
    }
    
    public function render()
    {
        return view('livewire.board-index');
    }
}
