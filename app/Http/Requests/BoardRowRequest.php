<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BoardRowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'difficulty_level_id' => 'required|integer',
            'status_id' => 'required|integer',
            'quiz_content' => 'nullable|max:1000',
            'quiz_answer' => 'nullable|max:1000',
        ];
    }
    
    public function messages(): array
    {
        return [
            'title.required' => 'タイトルは必須です。',
            'title.max' => 'タイトルは最大255文字です。',
            'difficulty_level_id.required' => '難易度は必須です。',
            'difficulty_level_id.integer' => '難易度は必須です。',
            'status_id.required' => 'ステータスは必須です。',
            'status_id.integer' => 'ステータスは必須です。',
            'quiz_content.max' => 'クイズの内容は最大1000文字です。',
            'quiz_answer.max' => 'クイズの答えは最大1000文字です。',
        ];
    }
}
