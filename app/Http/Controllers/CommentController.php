<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\BoardRow;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|max:255',
            'board_row_id' => 'required|exists:board_rows,id',
        ]);
        
        $boardRow = BoardRow::findOrFail($validatedData['board_row_id']);
        $this->authorize('view', $boardRow);
        
        $comment = Comment::create([
            'content' => $validatedData['content'],
            'board_row_id' => $validatedData['board_row_id'],
        ]);
    
        $boardRow = $comment->boardRow;
        $comments = $boardRow->comments;

        return response()->json(['success' => $comments], 200);
    }
    
    public function destroy(Request $request)
    {
        $commentId = $request->input('comment_id');
        
        $comment = Comment::findOrFail($commentId);
        
        $this->authorize('delete', $comment);
        
        $comment->delete();
        
        return response()->json(['success' => 'Question deleted successfully']);
    }
}
