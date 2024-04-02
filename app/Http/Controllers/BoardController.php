<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\DifficultyLevel;
use App\Models\Status;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boards = auth()->user()->boards;
        return view('boards.index', compact('boards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $board = new Board;
        $board->name = '無題';
        $board->user_id = auth()->id();
        $board->save();
        
        return redirect()->route('boards.show', $board);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board)
    {
        if ($board->user_id !== auth()->id()) 
        {
            abort(403);
        }
        
        $difficultyLevels = DifficultyLevel::all();
        $statuses = Status::all();
        
        $boards = auth()->user()->boards;
        return view('boards.show', compact('board', 'boards', 'difficultyLevels', 'statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Board $board)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board)
    {
        if ($board->user_id !== auth()->id()) 
        {
            abort(403);
        }
        
        $data = $request->only(['name']);
        $board->update($data);
        return redirect()->route('boards.show', $board);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board)
    {
        if ($board->user_id !== auth()->id()) 
        {
            abort(403);
        }
        
        $board->delete();
        session()->flash('message', '削除しました');
        return redirect()->route('boards.index');
    }
    
    public function trashed()
    {
        $boards = auth()->user()->boards;
        
        $trashedBoards = auth()->user()->boards()->onlyTrashed()->paginate(5);
            
        return view('boards.trashed', compact('trashedBoards', 'boards'));
    }
    
    public function deleteAll()
    {
        auth()->user()->boards()->onlyTrashed()->forceDelete();
        
        session()->flash('message', 'ゴミ箱の中身を全て削除しました');
        return redirect()->route('boards.index');
    }
    
    public function deleteSelected(Request $request)
    {
        $selectedBoardsId = $request->input('selectedBoards');
        auth()->user()->boards()->onlyTrashed()->whereIn('id', $selectedBoardsId)->forceDelete();
        
        session()->flash('message', '選択されたボードを削除しました。');
        return redirect()->route('boards.trashed');
    }
    
    public function restoreSelected(Request $request)
    {
        $selectedBoardsId = $request->input('selectedBoards');

        auth()->user()->boards()->whereIn('id', $selectedBoardsId)->restore();

        session()->flash('message', '選択されたボードを復元しました。');
        return redirect()->route('boards.index');
    }
}
