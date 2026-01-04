<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostAction;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $posts = $user->posts()
            ->with(['likes', 'comments'])
            ->latest()
            ->paginate(12);

        $stats = [
            'total_posts' => $user->posts()->count(),
            'total_likes' => PostAction::whereIn('post_id', $user->posts()->pluck('id'))
                ->where('type', 'like')
                ->count(),
            'total_comments' => PostAction::whereIn('post_id', $user->posts()->pluck('id'))
                ->where('type', 'comment')
                ->count(),
            'total_views' => $user->posts()->sum('views_count'),
        ];

        return view('profile.show', compact('user', 'posts', 'stats'));
    }

    public function myPosts()
    {
        $user = auth()->user();
        $posts = $user->posts()
            ->with(['likes', 'comments'])
            ->latest()
            ->paginate(12);

        return view('profile.my-posts', compact('posts'));
    }
}