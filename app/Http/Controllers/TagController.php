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
        try 
        {
            $tag = DB::transaction(function () use ($request) {
                $tagName = $request->input('name');
                $boardRowId = $request->input('board_row_id');

                $boardRow = BoardRow::find($boardRowId);
                $boardId = $boardRow->board->id;

                $request->validate([
                    'name' => [
                        'required',
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
        catch (\Exception $e) 
        {
            return response()->json(['message' => 'Failed to save tag', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function attachTagToBoardRow(Request $request)
    {
        try 
        {
            $result = DB::transaction(function () use ($request) {
                $tagId = $request->input('tag_id');
                $boardRowId = $request->input('board_row_id');
    
                $boardRow = BoardRow::find($boardRowId);
                $tag = Tag::find($tagId);
    
                if (!$boardRow || !$tag) {
                    return response()->json(['message' => 'Invalid board row or tag id'], 400);
                }
    
                $syncResult = $boardRow->tags()->syncWithoutDetaching([$tag->id]);
    
                return $syncResult;
            });
    
            $attached = count($result['attached']) > 0;
    
            return response()->json([
                'message' => $attached ? 'Tag attached successfully' : 'Tag was already attached',
                'attached' => $attached,
            ]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(['message' => 'Failed to attach tag', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function detachTagFromBoardRow(Request $request)
    {
        try 
        {
            $result = DB::transaction(function () use ($request) {
                $tagId = $request->input('tag_id');
                $boardRowId = $request->input('board_row_id');
    
                $boardRow = BoardRow::find($boardRowId);
                $tag = Tag::find($tagId);
    
                if (!$boardRow || !$tag) {
                    return response()->json(['message' => 'Invalid board row or tag id'], 400);
                }
    
                $boardRow->tags()->detach($tag->id);
    
                return true;
            });
    
            return response()->json([
                'message' => 'Tag detached successfully',
            ]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(['message' => 'Failed to detach tag', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function delete(Request $request)
    {
        try 
        {
            $result = DB::transaction(function () use ($request) {
                $tagId = $request->input('tag_id');
    
                $tag = Tag::find($tagId);
    
                if (!$tag) {
                    return response()->json(['message' => 'Invalid tag id'], 400);
                }
    
                $tag->delete();
    
                return true;
            });
    
            return response()->json([
                'message' => 'Tag deleted successfully',
            ]);
        }
        catch (\Exception $e) 
        {
            return response()->json(['message' => 'Failed to delete tag', 'error' => $e->getMessage()], 500);
        }
    }
}
