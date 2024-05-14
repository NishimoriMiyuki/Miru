<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Board;
use App\Models\BoardRow;
use App\Models\Status;
use App\Models\Tag;
use App\Models\Question;
use App\Models\Comment;

class BoardEditor extends Component
{
    public $board;
    public $statuses;
    public $boardRowTags;
    public $boardTags;
    public $boardRows;
    public $questions;
    public $comments;
    public $answerList;
    public $isEditOpen = false;
    public $selectedAnswers = [];
    
    public $selectBoardRow;
    
    #[Validate('required', message: 'タイトルは必須です。')]
    #[Validate('max:255', message: 'タイトルは最大255文字です')]
    public $title;
    
    public $quiz_content;
    public $quiz_answer;
    public $difficulty_level_id;
    public $status_id;
    
    public $newTag;
    
    #[Validate('max:255', message: '質問例は最大255文字です')]
    public $newQuestion;
    
    public $newComment;
    
    public function mount(Board $board)
    {
        $this->authorize('view', $board);
    
        $this->board = $board;
        $this->statuses = Status::all();
        $boardRows = BoardRow::where('board_id', $this->board->id)->get();
        $this->boardTags = $board->tags;
        
        $this->answerList = [
            ['label' => 'はい', 'number' => 1],
            ['label' => 'いいえ', 'number' => 2],
            ['label' => 'どちらとも言えない', 'number' => 3],
            ['label' => 'どちらでも成立する', 'number' => 4],
            ['label' => '関係ない', 'number' => 5],
        ];
        
        foreach ($this->statuses as $status) 
        {
            $this->boardRows[$status->id] = $boardRows->where('status_id', $status->id)->sortBy('order')->values()->all();
        }
    }
    
    public function updatedStatusId()
    {
        $updatedBoardRow = $this->selectBoardRow;
    
        // 現在のステータスからボード行を削除
        foreach ($this->boardRows as $statusId => $boardRows) 
        {
            foreach ($boardRows as $index => $existingBoardRow) 
            {
                if ($existingBoardRow->id == $updatedBoardRow->id) 
                {
                    unset($this->boardRows[$statusId][$index]);
                }
            }
        }
    
        // 新しいorder値を計算
        $newOrder = count($this->boardRows[$this->status_id]);
    
        // 更新されたボード行のorderを更新
        $updatedBoardRow->order = $newOrder;
    
        // 更新されたボード行を新しいステータスに追加
        $this->boardRows[$this->status_id][] = $updatedBoardRow;
    
        // 配列のキーを順番にするために再インデックス
        foreach ($this->boardRows as $statusId => $boardRows) 
        {
            $this->boardRows[$statusId] = array_values($boardRows);
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
        $this->quiz_content = $boardRow->quiz_content;
        $this->quiz_answer = $boardRow->quiz_answer;
        $this->difficulty_level_id = $boardRow->difficulty_level_id;
        $this->status_id = $boardRow->status_id;
        $this->boardRowTags = $boardRow->tags;
        $this->questions = $boardRow->questions;
        $this->comments = $boardRow->comments;
        
        foreach ($this->questions as $question) 
        {
            $this->selectedAnswers[$question->id] = $question->answer;
        }
        
        $this->isEditOpen = true;
    }
    
    public function editClose()
    {
        $this->isEditOpen = false;
        $this->title = null;
        $this->quiz_content = null;
        $this->quiz_answer = null;
        $this->difficulty_level_id = null;
        $this->status_id = null;
        $this->boardRowTags = null;
        $this->questions = null;
        $this->comments = null;
    }
    
    public function saveBoardRow()
    {
        // 認可チェック
        $this->authorize('update', $this->selectBoardRow);
        foreach ($this->boardRowTags as $tag) {
            $this->authorize('update', $tag);
        }
        foreach ($this->selectedAnswers as $questionId => $selectedAnswer) {
            $question = Question::find($questionId);
            if ($question) {
                $this->authorize('update', $question);
            }
        }
        
        $this->validate();
        
        // boadrRow
        $this->selectBoardRow->update([
            'title' => $this->title,
            'quiz_content' => $this->quiz_content,
            'quiz_answer' => $this->quiz_answer,
            'difficulty_level_id' => $this->difficulty_level_id,
            'status_id' => $this->status_id,
        ]);
        
        // tag
        $tagIds = $this->boardRowTags->map(function ($tag) {
            return $tag->id;
        })->toArray();
        $this->selectBoardRow->tags()->sync($tagIds);
        
        // 回答
        foreach ($this->selectedAnswers as $questionId => $selectedAnswer) {
            $question = Question::find($questionId);
            if ($question) {
                $question->answer = $selectedAnswer;
                $question->save();
            }
        }
        
        $this->dispatch('toaster', [ 'message' => '保存されました' ]);
    }
    
    public function addTag($tagId)
    {
        if ($this->boardRowTags->contains('id', $tagId))
        {
            return;
        }
        
        $tag = Tag::findOrFail($tagId);
        $this->boardRowTags->push($tag);
    }
    
    public function createTag()
    {
        $tag = DB::transaction(function () {
            $tagName = $this->newTag;
            $boardId = $this->board->id;
            
            $this->validate([
                'newTag' => [
                    'required',
                    'max:255',
                    Rule::unique('tags', 'name')->where(function ($query) use ($tagName, $boardId) {
                        return $query->where('name', $tagName)->where('board_id', $boardId);
                    }),
                ],
            ]);
            
            $tag = Tag::create(['name' => $tagName, 'board_id' => $boardId]);
            
            return $tag;
        });
        
        $this->boardTags[] = $tag;
        $this->reset('newTag');
    }
    
    public function removeTag($id)
    {
        $this->boardRowTags = $this->boardRowTags->reject(function ($tag) use ($id) {
            return $tag->id == $id;
        })->values();
    }
    
    public function deleteTag($id)
    {
        $tag = Tag::findOrFail($id);
        $this->authorize('delete', $tag);
        $tag->delete();
        
        $this->boardTags = $this->boardTags->reject(function ($boardTag) use ($id) {
            return $boardTag->id == $id;
        });
        
        $this->removeTag($id);
    }
    
    public function createQuestion()
    {
        if(!trim($this->newQuestion))
        {
            $this->newQuestion = '';
            return;
        }
        
        $question = new Question();
        $question->content = $this->newQuestion;
        $question->board_row_id = $this->selectBoardRow->id;
        $question->save();
        
        $this->questions->push($question);
        
        $this->newQuestion = '';
    }
    
    public function deleteQuestion($id)
    {
        $question = Question::findOrFail($id);
        $this->authorize('delete', $question);
        $question->delete();
        
        $this->questions = $this->questions->reject(function ($question) use ($id) {
            return $question->id === $id;
        });
        
        if (isset($this->selectedAnswers[$id])) {
            unset($this->selectedAnswers[$id]);
        }
    }
    
    public function createComment()
    {
        if(!trim($this->newComment))
        {
            $this->newComment = '';
            return;
        }
        
        $comment = new Comment();
        $comment->content = $this->newComment;
        $comment->board_row_id = $this->selectBoardRow->id;
        $comment->save();
        
        $this->comments->push($comment);
        
        $this->newComment = '';
    }
    
    public function deleteComment($id)
    {
        $comment = Comment::findOrFail($id);
        $this->authorize('delete', $comment);
        $comment->delete();
        
        $this->comments = $this->comments->reject(function ($comment) use ($id) {
            return $comment->id === $id;
        });
    }
    
    public function deleteBoardRow($boardRowId)
    {
        $matchingBoardRow = null;
        $matchingStatusId = null;
        $matchingBoardRowKey = null;
    
        foreach ($this->boardRows as $statusId => $boardRows) {
            foreach ($boardRows as $key => $boardRow) {
                if ($boardRow['id'] == $boardRowId) {
                    $matchingBoardRow = $boardRow;
                    $matchingStatusId = $statusId;
                    $matchingBoardRowKey = $key;
                    break 2;
                }
            }
        }
        
        if($matchingBoardRow === null)
        {
            return;
        }
        
        $this->authorize('delete', $matchingBoardRow);
        $matchingBoardRow->delete();
        unset($this->boardRows[$matchingStatusId][$matchingBoardRowKey]);
    }
}
