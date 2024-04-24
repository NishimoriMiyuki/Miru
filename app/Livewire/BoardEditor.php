<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Board;
use App\Models\BoardRow;
use App\Models\Status;

class BoardEditor extends Component
{
    public $board;
    public $statuses;
    public $boardRows;
    
    public function mount(Board $board)
    {
        $this->authorize('view', $board);
    
        $this->board = $board;
        $this->statuses = Status::all();
        $boardRows = BoardRow::where('board_id', $this->board->id)->get();
        
        foreach ($this->statuses as $status) 
        {
            $this->boardRows[$status->id] = $boardRows->where('status_id', $status->id)->sortBy('order')->values()->all();
        }
    }
    
    public function updateTaskOrder($tasksOrder)
    {
        foreach ($tasksOrder as $status) {
            foreach ($status['items'] as $task) 
            {
                $updatedTask = BoardRow::find($task['value']);
    
                // 現在のステータスからタスクを削除
                foreach ($this->boardRows as $statusId => $tasks) 
                {
                    foreach ($tasks as $index => $existingTask) 
                    {
                        if ($existingTask->id == $updatedTask->id) 
                        {
                            unset($this->boardRows[$statusId][$index]);
                        }
                    }
                }
    
                // タスクを更新
                $updatedTask->update([
                    'order' => $task['order'],
                    'status_id' => $status['value']
                ]);
    
                // 更新されたタスクを新しいステータスに追加
                $this->boardRows[$status['value']][$task['order'] - 1] = $updatedTask;
            }
        }
    
        // 配列のキーを順番にするために再インデックス
        foreach ($this->boardRows as $statusId => $tasks) 
        {
            $this->boardRows[$statusId] = array_values($tasks);
        }
    }
    
    public function render()
    {
        return view('livewire.board-editor');
    }
    
    public function createBoardRow($statusId)
    {
        $this->authorize('create', $this->board);
    
        $boardRow = new BoardRow();
        $boardRow->board_id = $this->board->id;
        $boardRow->title = "無題";
        $boardRow->status_id = $statusId;
        $boardRow->save();
        
        $this->boardRows[$boardRow->status_id][] = $boardRow;
    }
}
