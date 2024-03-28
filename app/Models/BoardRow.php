<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardRow extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'quiz_content',
        'quiz_answer',
    ];
    
    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
