<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Status;
use App\Http\Requests\BoardRequest;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        $boards = auth()->user()->boards;
        return view('boards.index', compact('boards'));
    }

    public function create()
    {
        $boards = auth()->user()->boards;
        return view('boards.create', compact('boards'));
    }

    public function store(BoardRequest $request)
    {
        $board = Board::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
        ]);
        
        session()->flash('message', '新しいボードが作成されました');
        
        return redirect()->route('boards.edit', compact('board'));
    }

    public function show($board)
    {
        $board = Board::withTrashed()->findOrFail($board);
        
        $this->authorize('view', $board);
        
        $statuses = Status::all();
        $groupedRows = $board->boardRows->groupBy('status_id');
        
        $trashedBoards = auth()->user()->boards()->onlyTrashed()->get();
        
        return view('boards.show', compact('trashedBoards', 'board', 'statuses', 'groupedRows'));
    }

    public function edit(Board $board)
    {
        $this->authorize('update', $board);
        
        $statuses = Status::all();
        $groupedRows = $board->boardRows->groupBy('status_id');
        
        $boards = auth()->user()->boards;
        return view('boards.edit', compact('boards', 'board', 'statuses', 'groupedRows'));
    }

    public function update(BoardRequest $request, Board $board)
    {
        $this->authorize('update', $board);
        
        $board->update($request->validated());
        
        return redirect()->route('boards.edit', $board);
    }

    public function destroy(Board $board)
    {
        $this->authorize('delete', $board);
        
        $board->delete();
        
        session()->flash('message', '削除しました');
        return redirect(route('boards.index'));
    }
    
    public function forceDelete($board)
    {
        $board = Board::withTrashed()->findOrFail($board);
        
        $this->authorize('delete', $board);
        
        $board->forceDelete();
        
        return redirect(route('boards.trashed'));
    }
    
    public function restore($board)
    {
        $board = Board::withTrashed()->findOrFail($board);
        
        $this->authorize('delete', $board);
        
        $board->restore();
        
        return redirect(route('boards.trashed'));
    }
    
    public function trashed()
    {
        $trashedBoards = auth()->user()->boards()->onlyTrashed()->get();
            
        return view('boards.trashed', compact('trashedBoards'));
    }
}
