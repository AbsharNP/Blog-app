@if($posts->isEmpty())
    <p class="text-center text-gray-500 dark:text-gray-400">
        No posts available.
    </p>
@endif

@foreach ($posts as $post)
    <article class="bg-white dark:bg-gray-900 rounded-xl shadow-sm hover:shadow-md transition overflow-hidden">
        
        {{-- Thumbnail --}}
        <img src="{{ $post->thumbnail ?? 'https://picsum.photos/600/400' }}"
             alt="{{ $post->title }}"
             class="w-full h-48 object-cover">

        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                <a href="{{ route('posts.show', $post) }}" class="hover:text-indigo-600">
                    {{ $post->title }}
                </a>
            </h2>

            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ $post->excerpt }}
            </p>

            <div class="flex items-center justify-between text-sm text-gray-500">
                <span>{{ $post->created_at->format('M d, Y') }}</span>
                <a href="{{ route('posts.show', $post) }}" class="text-indigo-600 hover:underline">
                    Read more →
                </a>
            </div>
        </div>
    </article>
@endforeach