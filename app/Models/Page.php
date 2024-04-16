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
}
