@extends('layouts.app')

@section('title', $post->title)

@section('content')
<article class="max-w-3xl mx-auto bg-white dark:bg-gray-900 p-6 sm:p-8 rounded-xl shadow">
    {{-- Title --}}
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">
        {{ $post->title }}
    </h1>

    {{-- Meta --}}
    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <span>
            By <a href="{{ route('profile.show', $post->author ?? 1) }}" 
                  class="hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline font-medium">
                {{ $post->author->name ?? 'Admin' }}
            </a>
        </span>
        <span>•</span>
        <span>{{ $post->created_at->format('M d, Y') }}</span>
        <span>•</span>
        <span class="flex items-center gap-1">
            👁️ {{ $post->views_count ?? 0 }} views
        </span>
    </div>

    {{-- Image --}}
    <img src="{{ $post->image ? asset('storage/posts/'.$post->image) : 'https://picsum.photos/800/400' }}"
         alt="{{ $post->title }}"
         class="rounded-xl mb-8 w-full h-auto object-cover">

    {{-- Content --}}
    <div class="prose dark:prose-invert max-w-none mb-8 text-gray-800 dark:text-gray-200">
        {!! nl2br(e($post->content)) !!}
    </div>

    {{-- Actions --}}
    <div class="mt-8 border-t dark:border-gray-700 pt-6">
        @php
            $liked = $post->userLiked();
        @endphp

        <div class="flex flex-wrap items-center gap-6">
            {{-- Like --}}
            @auth
            <button id="like-btn"
                    data-liked="{{ $liked ? '1' : '0' }}"
                    class="flex items-center gap-2 cursor-pointer
                           {{ $liked ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400' }}
                           dark:hover:text-red-400 hover:text-red-600 transition">
                <span class="text-xl">❤️</span>
                <span id="like-count" class="font-medium">{{ $post->likes->count() }}</span>
            </button>
            @else
            <a href="{{ route('login') }}"
               class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                <span class="text-xl">❤️</span>
                <span class="font-medium">{{ $post->likes->count() }}</span>
            </a>
            @endauth
        </div>
    </div>

    {{-- Comments --}}
    <div class="mt-8">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-4">
            Comments (<span id="comments-count">{{ $post->comments->count() }}</span>)
        </h3>

        {{-- Comment List --}}
        <div id="comments-list" class="space-y-4 mb-6">
            @forelse($post->comments as $comment)
                <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-800">
                    <p class="text-sm sm:text-base text-gray-800 dark:text-gray-200 mb-2">
                        {{ $comment->comment }}
                    </p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            — <span class="font-medium">{{ $comment->user->name }}</span>,
                            <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm">No comments yet. Be the first to comment!</p>
            @endforelse
        </div>

        {{-- Add Comment --}}
        @auth
        <div class="mt-6 border-t dark:border-gray-700 pt-6">
            <textarea id="comment-text"
                      rows="3"
                      class="w-full rounded-lg border border-gray-300 dark:border-gray-700 p-3 text-sm sm:text-base
                             dark:bg-gray-800 dark:text-white
                             focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                             resize-none transition
                             error:border-red-500"
                      placeholder="Write a comment..."></textarea>
            <span id="comment-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden block"></span>

            <button id="comment-btn"
                    class="mt-3 bg-indigo-600 hover:bg-indigo-700 text-white
                           px-4 py-2 rounded-lg cursor-pointer
                           transition font-medium text-sm sm:text-base
                           disabled:opacity-50 disabled:cursor-not-allowed">
                Post Comment
            </button>
        </div>
        @else
        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                Please <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">login</a> to comment
            </p>
        </div>
        @endauth
    </div>
</article>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>

<script>
$(function () {
    // Like / Unlike
    $('#like-btn').on('click', function () {
        let btn = $(this);
        let isLiked = btn.data('liked') == '1';

        $.ajax({
            url: "{{ route('posts.like', $post) }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function () {
                btn.prop('disabled', true);
            },
            success: function (res) {
                $('#like-count').text(res.likes);

                if (res.status === 'liked') {
                    btn.removeClass('text-gray-600 dark:text-gray-400')
                       .addClass('text-red-600 dark:text-red-400');
                    btn.data('liked', 1);
                } else {
                    btn.removeClass('text-red-600 dark:text-red-400')
                       .addClass('text-gray-600 dark:text-gray-400');
                    btn.data('liked', 0);
                }
            },
            error: function() {
                alert('Something went wrong. Please try again.');
            },
            complete: function () {
                btn.prop('disabled', false);
            }
        });
    });

    // Submit Comment
    $('#comment-btn').on('click', function () {
        let commentText = $('#comment-text').val().trim();
        let btn = $(this);
        const errorSpan = $('#comment-error');

        // Clear previous error
        errorSpan.addClass('hidden').text('');
        $('#comment-text').removeClass('border-red-500');

        // Frontend validation
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
            data: {
                _token: "{{ csrf_token() }}",
                comment: commentText
            },
            beforeSend: function () {
                btn.prop('disabled', true).text('Posting...');
            },
            success: function (res) {
                $('#comment-text').val('');

                // Update comments count
                let currentCount = parseInt($('#comments-count').text());
                $('#comments-count').text(currentCount + 1);

                // Add new comment to list
                $('#comments-list').prepend(`
                    <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-800">
                        <p class="text-sm sm:text-base text-gray-800 dark:text-gray-200 mb-2">
                            ${$('<div>').text(res.comment.comment).html()}
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                — <span class="font-medium">${res.comment.user_name}</span>,
                                <span class="comment-time">${res.comment.created_at}</span>
                            </span>
                        </div>
                    </div>
                `);

                // Scroll to top of comments
                $('html, body').animate({
                    scrollTop: $('#comments-list').offset().top - 100
                }, 300);
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
            complete: function () {
                btn.prop('disabled', false).text('Post Comment');
            }
        });
    });

    // Allow Enter to submit (Ctrl+Enter or Cmd+Enter)
    $('#comment-text').on('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            $('#comment-btn').click();
        }
    });
});
</script>
@endpush