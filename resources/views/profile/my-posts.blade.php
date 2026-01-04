@extends('layouts.app')

@section('title', 'My Posts')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">
            My Posts
        </h1>
        <a href="{{ route('posts.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-100 dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            ← Back to All Posts
        </a>
    </div>

    @if($posts->isEmpty())
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-8 text-center">
            <p class="text-gray-500 dark:text-gray-400 mb-4">
                You haven't posted anything yet.
            </p>
            <a href="{{ route('posts.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">
                Create Your First Post
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($posts as $post)
                <article class="bg-white dark:bg-gray-900 rounded-xl shadow-sm hover:shadow-md transition overflow-hidden">
                    <div class="flex flex-col sm:flex-row">
                        <!-- Thumbnail -->
                        <a href="{{ route('posts.show', $post) }}" class="sm:w-48 flex-shrink-0">
                            <img src="{{ $post->image ? asset('storage/posts/'.$post->image) : 'https://picsum.photos/600/400' }}"
                                 alt="{{ $post->title }}"
                                 class="w-full sm:w-48 h-48 sm:h-full object-cover">
                        </a>

                        <div class="flex-1 p-4 sm:p-6 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                    <a href="{{ route('posts.show', $post) }}"
                                       class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-4">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 150) }}
                                </p>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-4 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                                    <span class="flex items-center gap-1">
                                        👁️ {{ $post->views_count }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        ❤️ {{ $post->likes->count() }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        💬 {{ $post->comments->count() }}
                                    </span>
                                </div>

                                <a href="{{ route('posts.show', $post) }}"
                                   class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                    Read more →
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
