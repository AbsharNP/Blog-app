<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostAction;
use Illuminate\Http\Request;

class PostActionController extends Controller
{
    public function like(Post $post)
    {
        $existingLike = PostAction::where('post_id', $post->id)
            ->where('user_id', auth()->id())
            ->where('type', 'like')
            ->first();

        if ($existingLike) {
            // Unlike - remove the like
            $existingLike->delete();
            $liked = false;
        } else {
            // Like - create new like
            PostAction::create([
                'post_id' => $post->id,
                'user_id' => auth()->id(),
                'type' => 'like'
            ]);
            $liked = true;
        }

        return response()->json([
            'likes' => $post->likes()->count(),
            'status' => $liked ? 'liked' : 'unliked'
        ]);
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required|min:2|max:1000'
        ]);

        $comment = PostAction::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'type' => 'comment',
            'comment' => $request->comment
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'user_name' => $comment->user->name,
                'created_at' => $comment->created_at->diffForHumans(),
                'created_at_full' => $comment->created_at->format('M d, Y h:i A')
            ]
        ]);
    }
}
