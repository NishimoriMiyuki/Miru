<?php

namespace App\Http\Controllers;

use App\Models\BoardRow;
use Illuminate\Http\Request;

class BoardRowController extends Controller
{
    public function store(Request $request)
    {
        $boardId = $request->input('board_id');
        
        $boardRow = new BoardRow;
        $boardRow->board_id = $boardId;
        $boardRow->title = $request->title;
        $boardRow->quiz_content = $request->quiz_content;
        $boardRow->quiz_answer = $request->quiz_answer;
        $boardRow->save();
        
        return redirect()->route('boards.show', $boardId);
    }
    
    public function update(Request $request, BoardRow $boardRow)
    {
        if ($boardRow->board->user_id !== auth()->id()) 
        {
            abort(403);
        }
        
        $data = $request->only(['title', 'quiz_content', 'quiz_answer']);
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
