<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = ['content', 'board_row_id'];
    
    public function boardRow()
    {
        return $this->belongsTo(BoardRow::class);
    }
}
