<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Renderless;
use App\Models\Board;
use App\Models\BoardRow;
use App\Models\Status;

class BoardEditor extends Component
{
    public $board;
    public $statuses;
    public $boardRows;
    public $isEditOpen = false;
    
    public $selectBoardRow;
    
    #[Validate('required', message: 'タイトルは必須です。')]
    #[Validate('max:255', message: 'タイトルは最大255文字です')]
    public $title;
    
    public $quizContent;
    public $quizAnswer;
    public $difficultyLevel;
    public $status;
    
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
                $this->boardRows[$status['value']][] = $updatedTask;
            }
        }
        
        // 配列のキーを順番にするために再インデックス
        foreach ($this->boardRows as $statusId => $tasks) 
        {
            usort($tasks, function ($a, $b) {
                return $a->order - $b->order;
            });
            $this->boardRows[$statusId] = array_values($tasks);
        }
    }
    
    public function render()
    {
        $this->dispatch('resize-textarea');
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
    
    public function editOpen($boardRowId)
    {
        $boardRow = $this->board->boardRows()->findOrFail($boardRowId);
        $this->authorize('update', $boardRow);
        $this->selectBoardRow = $boardRow;
        
        $this->title = $boardRow->title;
        $this->quizContent = $boardRow->quiz_content;
        $this->quizAnswer = $boardRow->quiz_answer;
        $this->difficultyLevel = $boardRow->difficulty_level_id;
        $this->status = $boardRow->status_id;
        
        $this->isEditOpen = true;
    }
    
    public function editClose()
    {
        $this->isEditOpen = false;
    }
    
    public function saveBoardRow()
    {
        $this->authorize('update', $this->selectBoardRow);
        $this->validateOnly('title, quizContent');
        
        $this->selectBoardRow->update([
            'title' => $this->title,
            'quiz_content' => $this->quizContent,
        ]);
        
        $this->dispatch('toaster', [ 'message' => '保存されました' ]);
    }
}
