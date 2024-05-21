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
        // content属性の先頭にある空白文字を削除
        $content = ltrim($this->content);
        
        // content内で最初に改行文字が現れる位置を取得
        $breakPosition = mb_strpos($content, "\n");
        $maxLength = 10;
    
        // 改行文字が見つからない場合
        if ($breakPosition === false) {
            // contentの先頭から最大30文字を取得して返す
            return mb_substr($content, 0, $maxLength);
        } else {
            // 改行文字が見つかった場合、改行文字の位置と最大30文字のうち短い方の位置までの文字列を取得して返す
            return mb_substr($content, 0, min($breakPosition, $maxLength));
        }
    }
    
    public function getRestOfContentAttribute()
    {
        // content属性の先頭にある空白文字を削除
        $content = ltrim($this->content);
        
        // content内で最初に改行文字が現れる位置を取得
        $breakPosition = mb_strpos($content, "\n");
        $maxLength = 20;
    
        // 改行文字が見つからない場合
        if ($breakPosition === false) {
            // 残りのコンテンツは空文字列を返す
            return '';
        } else {
            // 改行文字の次の位置を計算
            $restOfContentStartPosition = min($breakPosition + 1, mb_strlen($content));
            
            // 残りのコンテンツを取得して返す
            return mb_substr($content, $restOfContentStartPosition, $maxLength);
        }
    }
}
