<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    public function index()
    {
        return view('posts.index', [
            'posts' => Post::latest()->paginate(9)
        ]);
        return view('posts.index');
    }

    public function show(Post $post)
    {
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
            // 'created_by' => auth()->id()
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Post created successfully!',
        ]);
    }

    public function ajaxList()
    {
        $posts = Post::latest()
            ->get()
            ->map(function ($post) {
                return [
                    'title'   => $post->title,
                    'slug'    => $post->slug,
                    'image'   => $post->image
                        ? asset('storage/posts/' . $post->image)
                        : 'https://picsum.photos/600/400',
                    'excerpt' => Str::limit($post->content, 120),
                    'date'    => $post->created_at->format('M d, Y'),
                ];
            });

        return response()->json([
            'data' => $posts
        ]);
    }
}

