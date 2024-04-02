<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardRow extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'board_id',
        'title',
        'quiz_content',
        'quiz_answer',
        'difficulty_level_id',
        'status_id',
    ];
    
    public function board()
    {
        return $this->belongsTo(Board::class);
    }
    
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function difficultyLevel()
    {
        return $this->belongsTo(DifficultyLevel::class);
    }
    
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
