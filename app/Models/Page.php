<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'order',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function scopeUpdatedOrder($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }
    
    public function scopeIsPublic($query, $value)
    {
        return $query->where('is_public', $value);
    }
    
    public function scopeIsFavorite($query)
    {
        return $query->where('is_favorite', true);
    }
    
    public function getFirstLineAttribute()
    {
        $content = ltrim($this->content);
        $breakPosition = mb_strpos($content, "\n");
        $maxLength = 30;
    
        if ($breakPosition === false) {
            return mb_substr($content, 0, $maxLength);
        } else {
            return mb_substr($content, 0, min($breakPosition, $maxLength));
        }
    }
    
    public function getRestOfContentAttribute()
    {
        $content = ltrim($this->content);
        $breakPosition = mb_strpos($content, "\n");
        $maxLength = 100;
    
        if ($breakPosition === false) {
            return '';
        } else {
            $restOfContentStartPosition = min($breakPosition + 1, mb_strlen($content));
            return mb_substr($content, $restOfContentStartPosition, $maxLength);
        }
    }
}
