<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image',
        'slug',
        'created_by',
        'views_count'
    ];
    
    use SoftDeletes;

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function likes()
    {
        return $this->hasMany(PostAction::class)->where('type', 'like');
    }

    public function comments()
    {
        return $this->hasMany(PostAction::class)->where('type', 'comment')->latest();
    }

    public function userLiked()
    {
        if (!auth()->check()) {
            return false;
        }
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    public function incrementViews()
    {
        $this->increment('views_count');
        $this->refresh();
    }
}
