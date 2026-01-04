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
        'created_by'
    ];
    use SoftDeletes;

    public function likes()
    {
        return $this->hasMany(PostAction::class)->where('type', 'like');
    }

    public function comments()
    {
        return $this->hasMany(PostAction::class)->where('type', 'comment');
    }
}
