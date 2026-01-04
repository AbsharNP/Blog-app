@extends('layouts.app')

@section('title', $user->name . ' - Profile')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Profile Header -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 sm:p-8 mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
            <!-- Avatar -->
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-indigo-600 flex items-center justify-center text-white text-2xl sm:text-3xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <!-- User Info -->
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ $user->name }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    {{ $user->email }}
                </p>

                <!-- Stats -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="text-center sm:text-left">
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            {{ $stats['total_posts'] }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Posts</div>
                    </div>
                    <div class="text-center sm:text-left">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                            {{ $stats['total_likes'] }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Likes</div>
                    </div>
                    <div class="text-center sm:text-left">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            {{ $stats['total_comments'] }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Comments</div>
                    </div>
                    <div class="text-center sm:text-left">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ $stats['total_views'] }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Views</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Posts Section -->
    <div>
        <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white mb-4">
            Posts by {{ $user->name }}
        </h2>

        @if($posts->isEmpty())
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-8 text-center">
                <p class="text-gray-500 dark:text-gray-400">
                    No posts yet.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($posts as $post)
                    <article class="bg-white dark:bg-gray-900 rounded-xl shadow-sm hover:shadow-md transition overflow-hidden">
                        <!-- Thumbnail -->
                        <a href="{{ route('posts.show', $post) }}">
                            <img src="{{ $post->image ? asset('storage/posts/'.$post->image) : 'https://picsum.photos/600/400' }}"
                                 alt="{{ $post->title }}"
                                 class="w-full h-40 sm:h-48 object-cover">
                        </a>

                        <div class="p-4 sm:p-6 space-y-3">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white line-clamp-2">
                                <a href="{{ route('posts.show', $post) }}"
                                   class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            <div class="flex items-center justify-between text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ $post->created_at->format('M d, Y') }}</span>
                                <div class="flex items-center gap-3">
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
</div>
@endsection
