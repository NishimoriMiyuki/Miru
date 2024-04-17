<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function boards()
    {
        return $this->hasMany(Board::class);
    }
    
    public function pages()
    {
        return $this->hasMany(Page::class);
    }
    
    public function getPublicPages()
    {
        return $this->pages()->isPublic(true)->updatedOrder()->paginate(10);
    }
    
    public function getPrivatePages()
    {
        return $this->pages()->isPublic(false)->updatedOrder()->paginate(10);
    }
    
    public function getFavoritePages()
    {
        return $this->pages()->isFavorite()->updatedOrder()->paginate(10);
    }
    
    public function getAllPages()
    {
        return $this->pages()->updatedOrder()->paginate(10);
    }
    
    public function getTrashedPages()
    {
        return $this->pages()->onlyTrashed()->paginate(10);
    }
}
