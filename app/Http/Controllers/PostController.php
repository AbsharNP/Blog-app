<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['author', 'likes', 'comments.user'])->latest();

        // Filter by user if requested
        if ($request->has('user') && $request->user) {
            $query->where('created_by', $request->user);
        }

        // Filter by date if requested
        if ($request->has('date') && $request->date) {
            $now = now();
            switch ($request->date) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'week':
                    $startOfWeek = $now->copy()->startOfWeek();
                    $endOfWeek = $now->copy()->endOfWeek();
                    $query->whereBetween('created_at', [
                        $startOfWeek->toDateTimeString(),
                        $endOfWeek->toDateTimeString()
                    ]);
                    break;
                case 'month':
                    $query->whereYear('created_at', $now->year)
                          ->whereMonth('created_at', $now->month);
                    break;
                case 'year':
                    $query->whereYear('created_at', $now->year);
                    break;
            }
        }

        $posts = $query->paginate(9)->withQueryString();

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        // Increment views
        $post->incrementViews();
        
        // Load relationships
        $post->load(['author', 'likes.user', 'comments.user']);

        return view('posts.show', compact('post'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'title' => 'required|string|max:255|min:3',
            'content' => 'required|string|max:5000|min:10',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        

        if ($request->hasFile('image')) {

            $image      = $request->file('image');
            $extension  = $image->getClientOriginalExtension();

            // Create unique image name
            $imageName = Str::slug($validated['title'])
                        . '-' . time()
                        . '-' . Str::random(6)
                        . '.' . $extension;

            // Store image
            $image->storeAs('posts', $imageName, 'public');

            $validated['image'] = $imageName;
        }
        Post::create([
            'title'   => $validated['title'],
            'content' => $validated['content'],
            'image'   => $validated['image'] ?? null,
            'slug'    => Str::slug($validated['title']),
            'created_by' => auth()->id()
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Post created successfully!',
        ]);
    }

    public function ajaxList()
    {
        $posts = Post::with('author')
            ->latest()
            ->get()
            ->map(function ($post) {
                return [
                    'title'   => $post->title,
                    'slug'    => $post->slug,
                    'image'   => $post->image
                        ? asset('storage/posts/' . $post->image)
                        : 'https://picsum.photos/600/400',
                    'excerpt' => Str::limit(strip_tags($post->content), 120),
                    'date'    => $post->created_at->format('M d, Y'),
                    'author'  => $post->author->name ?? 'Admin',
                ];
            });

        return response()->json([
            'data' => $posts
        ]);
    }
}

