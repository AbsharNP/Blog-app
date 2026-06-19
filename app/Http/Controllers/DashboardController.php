<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostAction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tab  = $request->get('tab', 'feed');
        $user = auth()->user();

        $allPosts = Post::with(['author', 'likes', 'comments'])
            ->latest()
            ->paginate(10, ['*'], 'all_page');

        $myPosts = $user->posts()
            ->with(['likes', 'comments'])
            ->latest()
            ->paginate(10, ['*'], 'my_page');

        $postIds = $user->posts()->pluck('id');

        $stats = [
            'total_posts'    => $user->posts()->count(),
            'total_likes'    => PostAction::whereIn('post_id', $postIds)->where('type', 'like')->count(),
            'total_comments' => PostAction::whereIn('post_id', $postIds)->where('type', 'comment')->count(),
            'total_views'    => $user->posts()->sum('views_count'),
        ];

        return view('dashboard.index', compact('allPosts', 'myPosts', 'stats', 'tab'));
    }
}
