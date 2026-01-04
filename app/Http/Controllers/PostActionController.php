<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostAction;
use Illuminate\Http\Request;

class PostActionController extends Controller
{
    public function like(Post $post)
    {
        $action = PostAction::firstOrCreate([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'type' => 'like'
        ]);

        return response()->json([
            'likes' => $post->likes()->count()
        ]);
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required|min:2'
        ]);

        PostAction::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'type' => 'comment',
            'comment' => $request->comment
        ]);

        return response()->json(['success' => true]);
    }
}
