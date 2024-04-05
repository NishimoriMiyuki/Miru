<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\BoardRow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function store(Request $request)
    {
        $tag = DB::transaction(function () use ($request) {
            $tagName = $request->input('name');
            $boardRowId = $request->input('board_row_id');
    
            $boardRow = BoardRow::findOrFail($boardRowId);
            $this->authorize('view', $boardRow);
            
            $boardId = $boardRow->board->id;
    
            $request->validate([
                'name' => [
                    'required',
                    'max:50',
                    Rule::unique('tags')->where(function ($query) use ($tagName, $boardId) {
                        return $query->where('name', $tagName)->where('board_id', $boardId);
                    }),
                ],
            ]);
    
            $tag = Tag::create(['name' => $tagName, 'board_id' => $boardId]);
    
            $boardRow->tags()->attach($tag);
    
            return $tag;
        });
    
        return response()->json(['message' => 'Tag saved successfully', 'tag' => $tag]);
    }
    
    public function attachTagToBoardRow(Request $request)
    {
        $tagId = $request->input('tag_id');
        $boardRowId = $request->input('board_row_id');
        
        $boardRow = BoardRow::findOrFail($boardRowId);
        $tag = Tag::findOrFail($tagId);
        
        $this->authorize('view', $boardRow);
        $this->authorize('view', $tag);
        
        $boardRow->tags()->syncWithoutDetaching([$tag->id]);

        $attached = count($result['attached']) > 0;

        return response()->json([
            'message' => $attached ? 'Tag attached successfully' : 'Tag was already attached',
            'attached' => $attached,
        ]);
    }
    
    public function detachTagFromBoardRow(Request $request)
    {
        $tagId = $request->input('tag_id');
        $boardRowId = $request->input('board_row_id');
    
        $boardRow = BoardRow::findOrFail($boardRowId);
        $tag = Tag::findOrFail($tagId);
        
        $this->authorize('view', $boardRow);
        $this->authorize('view', $tag);
    
        $boardRow->tags()->detach($tag->id);
    
        return response()->json([
            'message' => 'Tag detached successfully',
        ]);
    }
    
    public function delete(Request $request)
    {
        $tagId = $request->input('tag_id');

        $tag = Tag::findOrFail($tagId);
        
        $this->authorize('delete', $tag);
        
        $tag->delete();
        
        return response()->json([
            'message' => 'Tag deleted successfully',
        ]);
    }
}
