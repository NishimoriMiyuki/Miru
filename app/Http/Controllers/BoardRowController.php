<?php

namespace App\Http\Controllers;

use App\Models\BoardRow;
use App\Models\DifficultyLevel;
use App\Models\Status;
use App\Models\Board;
use App\Http\Requests\BoardRowRequest;
use Illuminate\Http\Request;

class BoardRowController extends Controller
{
    public function store(Board $board)
    {
        $this->authorize('create', $board);
        
        $boardRow = new BoardRow();
        $boardRow->board_id = $board->id;
        $boardRow->title = "無題";
        $boardRow->status_id = 2;
        $boardRow->save();
        
        return redirect()->route('boards.edit', $board);
    }
    
    public function update(BoardRowRequest $request, BoardRow $boardRow)
    {
        $this->authorize('update', $boardRow);
        
        $boardRow->update($request->validated());
        $boardId = $boardRow->board->id;
        
        return redirect()->route('boards.edit', $boardId);
    }
    
    public function destroy(BoardRow $boardRow)
    {
        $this->authorize('delete', $boardRow);
        
        $boardRow->delete();
        $boardId = $boardRow->board->id;
        
        session()->flash('message', '削除しました');
        return redirect()->route('boards.edit', $boardId);
    }
    
    public function edit(BoardRow $boardRow)
    {
        $this->authorize('update', $boardRow);
        
        $boards = auth()->user()->boards;
        $difficultyLevels = DifficultyLevel::all();
        $statuses = Status::all();
        $board = $boardRow->board;
        $tags = $board->tags;
        
        return view('board_rows.edit', compact('boards', 'difficultyLevels', 'statuses', 'board', 'boardRow', 'tags'));
    }
}
