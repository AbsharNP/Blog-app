@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Back link --}}
    <a href="{{ route('posts.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 dark:text-gray-400
              hover:text-sky-600 dark:hover:text-sky-400 transition mb-5">
        ← Back to posts
    </a>

    <article class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl shadow-sky-500/5
                    border border-gray-100 dark:border-gray-800 overflow-hidden">

        {{-- Hero image --}}
        <div class="relative">
            <img src="{{ $post->image ? asset('storage/posts/'.$post->image) : 'https://picsum.photos/seed/'.$post->id.'/1000/500' }}"
                 alt="{{ $post->title }}"
                 class="w-full h-56 sm:h-80 object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
            <h1 class="absolute bottom-0 left-0 right-0 p-6 sm:p-8
                       text-2xl sm:text-4xl font-extrabold text-white tracking-tight drop-shadow-lg">
                {{ $post->title }}
            </h1>
        </div>

        <div class="p-6 sm:p-8">
            {{-- Author meta --}}
            <div class="flex flex-wrap items-center gap-3 pb-6 mb-6 border-b border-gray-100 dark:border-gray-800">
                <span class="w-11 h-11 rounded-full bg-brand flex items-center justify-center text-white font-bold shadow-lg shadow-sky-500/30">
                    {{ strtoupper(substr($post->author->name ?? 'A', 0, 1)) }}
                </span>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('profile.show', $post->author ?? 1) }}"
                       class="block font-bold text-gray-900 dark:text-white hover:text-sky-600 dark:hover:text-sky-400">
                        {{ $post->author->name ?? 'Admin' }}
                    </a>
                    <span class="text-xs text-gray-400">
                        {{ $post->created_at->format('M d, Y') }} · 👁 {{ $post->views_count ?? 0 }} views
                    </span>
                </div>
            </div>

            {{-- Content --}}
            <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 leading-relaxed text-[15px] sm:text-base">
                {!! nl2br(e($post->content)) !!}
            </div>

            {{-- Like --}}
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800">
                @php $liked = $post->userLiked(); @endphp
                @auth
                <button id="like-btn"
                        data-liked="{{ $liked ? '1' : '0' }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full font-semibold text-sm
                               border-2 transition active:scale-95
                               {{ $liked
                                  ? 'bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-700 text-red-600 dark:text-red-400'
                                  : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:border-red-300 hover:text-red-600' }}">
                    <span id="like-icon" class="text-lg">❤️</span>
                    <span id="like-count">{{ $post->likes->count() }}</span>
                    <span class="hidden sm:inline">Likes</span>
                </button>
                @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full font-semibold text-sm
                          bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700
                          text-gray-600 dark:text-gray-300 hover:border-red-300 hover:text-red-600 transition">
                    <span class="text-lg">❤️</span>
                    <span>{{ $post->likes->count() }}</span>
                    <span class="hidden sm:inline">Log in to like</span>
                </a>
                @endauth
            </div>
        </div>
    </article>

    {{-- Comments --}}
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl shadow-sky-500/5
                border border-gray-100 dark:border-gray-800 mt-6 p-6 sm:p-8">
        <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 dark:text-white mb-5 flex items-center gap-2">
            💬 Comments
            <span class="px-2.5 py-0.5 rounded-full bg-brand text-white text-sm" id="comments-count">{{ $post->comments->count() }}</span>
        </h3>

        {{-- Add comment --}}
        @auth
        <div class="mb-6">
            <div class="flex gap-3">
                <span class="w-10 h-10 flex-shrink-0 rounded-full bg-brand flex items-center justify-center text-white font-bold shadow">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
                <div class="flex-1">
                    <textarea id="comment-text" rows="3"
                              class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 p-4 text-sm
                                     dark:bg-gray-800 dark:text-white
                                     focus:ring-2 focus:ring-sky-500 focus:border-transparent
                                     resize-none transition"
                              placeholder="Share your thoughts..."></textarea>
                    <span id="comment-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden block"></span>
                    <div class="flex justify-end mt-2">
                        <button id="comment-btn"
                                class="bg-brand text-white px-5 py-2 rounded-full text-sm font-semibold
                                       shadow-lg shadow-sky-500/30 hover:scale-[1.03] active:scale-95 transition
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                            Post Comment
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="mb-6 p-4 rounded-2xl bg-brand-soft border border-sky-100 dark:border-sky-900/40 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                <a href="{{ route('login') }}" class="font-bold text-sky-600 dark:text-sky-400 hover:underline">Log in</a>
                to join the conversation.
            </p>
        </div>
        @endauth

        {{-- Comment list --}}
        <div id="comments-list" class="space-y-3">
            @forelse($post->comments as $comment)
                <div class="flex gap-3">
                    <span class="w-9 h-9 flex-shrink-0 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                    </span>
                    <div class="flex-1 bg-gray-50 dark:bg-gray-800 rounded-2xl rounded-tl-sm px-4 py-3">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-sm text-gray-900 dark:text-white">{{ $comment->user->name }}</span>
                            <span class="text-xs text-gray-400 comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $comment->comment }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 dark:text-gray-500 text-sm text-center py-6">No comments yet. Be the first! 🎉</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    // Like / Unlike
    $('#like-btn').on('click', function () {
        let btn = $(this);
        $.ajax({
            url: "{{ route('posts.like', $post) }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            beforeSend: function () { btn.prop('disabled', true); },
            success: function (res) {
                $('#like-count').text(res.likes);
                $('#like-icon').addClass('animate-pop');
                setTimeout(() => $('#like-icon').removeClass('animate-pop'), 400);

                if (res.status === 'liked') {
                    btn.removeClass('bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300')
                       .addClass('bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-700 text-red-600 dark:text-red-400');
                    btn.data('liked', 1);
                } else {
                    btn.removeClass('bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-700 text-red-600 dark:text-red-400')
                       .addClass('bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300');
                    btn.data('liked', 0);
                }
            },
            error: function() { alert('Something went wrong. Please try again.'); },
            complete: function () { btn.prop('disabled', false); }
        });
    });

    // Submit Comment
    $('#comment-btn').on('click', function () {
        let commentText = $('#comment-text').val().trim();
        let btn = $(this);
        const errorSpan = $('#comment-error');

        errorSpan.addClass('hidden').text('');
        $('#comment-text').removeClass('border-red-500');

        if (!commentText) {
            errorSpan.removeClass('hidden').text('Comment cannot be empty');
            $('#comment-text').addClass('border-red-500');
            return;
        }
        if (commentText.length < 2) {
            errorSpan.removeClass('hidden').text('Comment must be at least 2 characters');
            $('#comment-text').addClass('border-red-500');
            return;
        }
        if (commentText.length > 1000) {
            errorSpan.removeClass('hidden').text('Comment must not exceed 1000 characters');
            $('#comment-text').addClass('border-red-500');
            return;
        }

        $.ajax({
            url: "{{ route('posts.comment', $post) }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", comment: commentText },
            beforeSend: function () { btn.prop('disabled', true).text('Posting...'); },
            success: function (res) {
                $('#comment-text').val('');
                let currentCount = parseInt($('#comments-count').text());
                $('#comments-count').text(currentCount + 1);

                // remove "no comments" placeholder if present
                $('#comments-list').find('p.text-center').remove();

                const initial = (res.comment.user_name || 'U').charAt(0).toUpperCase();
                $('#comments-list').prepend(`
                    <div class="flex gap-3 animate-card">
                        <span class="w-9 h-9 flex-shrink-0 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white text-xs font-bold">${initial}</span>
                        <div class="flex-1 bg-gray-50 dark:bg-gray-800 rounded-2xl rounded-tl-sm px-4 py-3">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-sm text-gray-900 dark:text-white">${$('<div>').text(res.comment.user_name).html()}</span>
                                <span class="text-xs text-gray-400 comment-time">${res.comment.created_at}</span>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">${$('<div>').text(res.comment.comment).html()}</p>
                        </div>
                    </div>
                `);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors || {};
                    const errorMessage = errors.comment ? errors.comment[0] : (xhr.responseJSON.message || 'Validation error');
                    errorSpan.removeClass('hidden').text(errorMessage);
                    $('#comment-text').addClass('border-red-500');
                } else {
                    errorSpan.removeClass('hidden').text('Something went wrong. Please try again.');
                    $('#comment-text').addClass('border-red-500');
                }
            },
            complete: function () { btn.prop('disabled', false).text('Post Comment'); }
        });
    });

    $('#comment-text').on('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') { $('#comment-btn').click(); }
    });
});
</script>
@endpush
