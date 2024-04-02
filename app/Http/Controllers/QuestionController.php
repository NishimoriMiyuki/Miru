<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $content = $request->input('content');
        $boardRowId = $request->input('board_row_id');

        $question = new Question;
        $question->content = $content;
        $question->board_row_id = $boardRowId;

        $question->save();
    
        $boardRow = $question->boardRow;
        $questions = $boardRow->questions;

        return response()->json(['success' => $questions], 200);
    }
    
    public function update(Request $request)
    {
        $questionId = $request->input('question_id');
        $choice = $request->input('choice');
        
        $question = Question::find($questionId);
        if ($question) 
        {
            $question->answer = ($choice === 'yes');
            
            $question->save();
            return response()->json(['success' => 'Choice updated successfully.']);
        }
        else
        {
            return response()->json(['error' => 'Question not found.'], 404);
        }
    }
    
    public function destroy(Request $request)
    {
        $questionId = $request->input('question_id');
        
        $question = Question::find($questionId);
        if ($question === null) {
            return response()->json(['error' => 'Question not found'], 404);
        }
        
        $question->delete();
        
        return response()->json(['success' => 'Question deleted successfully']);
    }
}
