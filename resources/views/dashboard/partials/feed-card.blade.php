{{-- Social feed card. Expects: $post, $owner (bool) --}}
@php
    $authorName = $owner ? auth()->user()->name : ($post->author->name ?? 'Admin');
@endphp
<article data-post-id="{{ $post->id }}"
         class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl shadow-sky-500/5
                border border-gray-100 dark:border-gray-800 overflow-hidden animate-card card-hover">

    {{-- Header --}}
    <div class="flex items-center gap-3 px-5 pt-5 pb-3">
        <span class="w-11 h-11 flex-shrink-0 rounded-full bg-brand flex items-center justify-center text-white font-bold shadow-lg shadow-sky-500/30">
            {{ strtoupper(substr($authorName, 0, 1)) }}
        </span>
        <div class="flex-1 min-w-0">
            <p class="font-bold text-gray-900 dark:text-white text-sm leading-tight">
                @if($owner)
                    {{ $authorName }}
                @else
                    <a href="{{ route('profile.show', $post->author ?? 1) }}" class="hover:text-sky-600 dark:hover:text-sky-400">
                        {{ $authorName }}
                    </a>
                @endif
            </p>
            <p class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
        </div>

        @if($owner)
        <div class="flex items-center gap-2 flex-shrink-0">
            <button class="edit-btn px-3 py-1.5 text-xs font-semibold rounded-full
                           bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400
                           hover:bg-sky-100 dark:hover:bg-sky-900/50 transition"
                    data-id="{{ $post->id }}"
                    data-title="{{ $post->title }}"
                    data-content="{{ $post->content }}"
                    data-image="{{ $post->image ? asset('storage/posts/'.$post->image) : '' }}">
                ✏️ Edit
            </button>
            <button class="delete-btn px-3 py-1.5 text-xs font-semibold rounded-full
                           bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400
                           hover:bg-red-100 dark:hover:bg-red-900/50 transition"
                    data-id="{{ $post->id }}"
                    data-title="{{ $post->title }}">
                🗑️ Delete
            </button>
        </div>
        @endif
    </div>

    {{-- Title + excerpt --}}
    <div class="px-5 pb-3">
        <h3 class="font-bold text-gray-900 dark:text-white mb-1">
            <a href="{{ route('posts.show', $post) }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition">
                {{ $post->title }}
            </a>
        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
            {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 160) }}
        </p>
    </div>

    {{-- Image --}}
    <a href="{{ route('posts.show', $post) }}" class="block overflow-hidden group">
        <img src="{{ $post->image ? asset('storage/posts/'.$post->image) : 'https://picsum.photos/seed/'.$post->id.'/900/500' }}"
             alt="{{ $post->title }}"
             class="w-full h-56 sm:h-64 object-cover group-hover:scale-105 transition-transform duration-500">
    </a>

    {{-- Footer --}}
    <div class="px-5 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2 text-xs font-medium">
            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">👁 {{ $post->views_count ?? 0 }}</span>
            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-red-50 dark:bg-red-900/20 text-red-500">❤️ {{ $post->likes->count() }}</span>
            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500">💬 {{ $post->comments->count() }}</span>
        </div>
        <a href="{{ route('posts.show', $post) }}"
           class="text-xs font-semibold text-sky-600 dark:text-sky-400 hover:underline">
            Read more →
        </a>
    </div>
</article>
