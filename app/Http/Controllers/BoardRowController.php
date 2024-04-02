<?php

namespace App\Http\Controllers;

use App\Models\BoardRow;
use App\Models\DifficultyLevel;
use App\Models\Status;
use Illuminate\Http\Request;

class BoardRowController extends Controller
{
    public function create($board)
    {
        $board = auth()->user()->boards()->findOrFail($board);
        $boards = auth()->user()->boards;
        $tags = $board->tags;
        $difficultyLevels = DifficultyLevel::all();
        $statuses = Status::all();
        
        $boardRow = new BoardRow();
        $boardRow->board_id = $board->id;
        $boardRow->save();
        
        return view('board_rows.create', compact('boards', 'difficultyLevels', 'statuses', 'board', 'boardRow', 'tags'));
    }
    
    public function update(Request $request, BoardRow $boardRow)
    {
        if ($boardRow->board->user_id !== auth()->id()) 
        {
            abort(403);
        }
        
        $data = $request->only(['title', 'quiz_content', 'quiz_answer', 'difficulty_level_id', 'status_id']);
        $boardRow->update($data);
        $boardId = $boardRow->board->id;
        
        return redirect()->route('boards.show', $boardId);
    }
    
    public function destroy(BoardRow $boardRow)
    {
        if ($boardRow->board->user_id !== auth()->id()) 
        {
            abort(403);
        }
        
        $boardRow->delete();
        $boardId = $boardRow->board->id;
        
        session()->flash('message', '削除しました');
        return redirect()->route('boards.show', $boardId);
    }
}
