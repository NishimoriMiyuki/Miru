<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $content = $request->input('content');
        $boardRowId = $request->input('board_row_id');

        $comment = new Comment;
        $comment->content = $content;
        $comment->board_row_id = $boardRowId;

        $comment->save();
    
        $boardRow = $comment->boardRow;
        $comments = $boardRow->comments;

        return response()->json(['success' => $comments], 200);
    }
    
    public function destroy(Request $request)
    {
        $commentId = $request->input('comment_id');
        
        $comment = Comment::find($commentId);
        if ($comment === null) {
            return response()->json(['error' => 'Question not found'], 404);
        }
        
        $comment->delete();
        
        return response()->json(['success' => 'Question deleted successfully']);
    }
}
