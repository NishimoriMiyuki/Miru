<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\BoardRow;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|max:255',
            'board_row_id' => 'required|exists:board_rows,id',
        ]);
        
        $boardRow = BoardRow::findOrFail($validatedData['board_row_id']);
        
        $this->authorize('view', $boardRow);

        $question = Question::create([
            'content' => $validatedData['content'],
            'board_row_id' => $validatedData['board_row_id'],
        ]);
    
        $boardRow = $question->boardRow;
        $questions = $boardRow->questions;

        return response()->json(['success' => $questions], 200);
    }
    
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'choice' => 'required|in:yes,no',
        ]);
    
        $questionId = $validatedData['question_id'];
        $choice = $validatedData['choice'];
    
        $question = Question::findOrFail($questionId);
        
        $this->authorize('update', $question);
    
        $question->answer = ($choice === 'yes');
        $question->save();
        
        return response()->json(['success' => 'Choice updated successfully.']);
    }
    
    public function destroy(Request $request)
    {
        $questionId = $request->input('question_id');
        
        $question = Question::findOrFail($questionId);
        
        $this->authorize('delete', $question);
        
        $question->delete();
        
        return response()->json(['success' => 'Question deleted successfully']);
    }
}
