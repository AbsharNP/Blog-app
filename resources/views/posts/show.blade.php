@extends('layouts.app')

@section('title', $post->title)

@section('content')
<article class="max-w-3xl mx-auto bg-white dark:bg-gray-900 p-8 rounded-xl shadow">

    {{-- Title --}}
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
        {{ $post->title }}
    </h1>

    {{-- Meta --}}
    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-6">
        <span>{{ $post->author->name ?? 'Admin' }}</span>
        <span class="mx-2">•</span>
        <span>{{ $post->created_at->format('M d, Y') }}</span>
    </div>

    {{-- Image --}}
    <img src="{{ $post->image ? asset('storage/posts/'.$post->image) : 'https://picsum.photos/800/400' }}"
         alt="{{ $post->title }}"
         class="rounded-xl mb-8 w-full object-cover">

    {{-- Content --}}
    <div class="prose dark:prose-invert max-w-none">
        {!! $post->content !!}
    </div>

    {{-- Actions --}}
    <div class="mt-8 border-t pt-6">

        @php
            $liked = auth()->check() &&
                     $post->likes->where('user_id', auth()->id())->isNotEmpty();
        @endphp

        <div class="flex items-center gap-6">

            {{-- Like --}}
            @auth
            <button id="like-btn"
                    data-liked="{{ $liked ? '1' : '0' }}"
                    class="flex items-center gap-2 cursor-pointer
                           {{ $liked ? 'text-red-600' : 'text-gray-600' }}
                           hover:text-red-600 transition">

                <span class="text-xl">❤️</span>
                <span id="like-count">{{ $post->likes->count() }}</span>
            </button>
            @endauth

            {{-- Guest --}}
            @guest
            <a href="{{ route('login') }}"
               class="text-indigo-600 hover:underline">
                Login to comment
            </a>
            @endguest
        </div>
    </div>

    {{-- Comments --}}
    <div class="mt-8">

        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Comments ({{ $post->comments->count() }})
        </h3>

        {{-- Comment List --}}
        <div id="comments-list" class="space-y-4">
            @foreach($post->comments as $comment)
                <div class="border rounded-lg p-4 dark:border-gray-700">
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                        {{ $comment->comment }}
                    </p>
                    <span class="text-xs text-gray-500">
                        — {{ $comment->user->name }},
                        {{ $comment->created_at->diffForHumans() }}
                    </span>
                </div>
            @endforeach
        </div>

        {{-- Add Comment --}}
        @auth
        <div class="mt-6">
            <textarea id="comment-text"
                      class="w-full rounded-lg border p-3 text-sm
                             dark:bg-gray-800 dark:text-white"
                      placeholder="Write a comment..."></textarea>

            <button id="comment-btn"
                    class="mt-2 bg-indigo-600 text-white
                           px-4 py-2 rounded-lg cursor-pointer
                           hover:bg-indigo-700 transition">
                Post Comment
            </button>
        </div>
        @endauth

    </div>

</article>
@endsection

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>

<script>
$(function () {

    // Like / Unlike
    $('#like-btn').on('click', function () {
        let btn = $(this);

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
                    btn.removeClass('text-gray-600').addClass('text-red-600');
                    btn.data('liked', 1);
                } else {
                    btn.removeClass('text-red-600').addClass('text-gray-600');
                    btn.data('liked', 0);
                }
            },
            complete: function () {
                btn.prop('disabled', false);
            }
        });
    });

    // Submit Comment
    $('#comment-btn').on('click', function () {
        let comment = $('#comment-text').val().trim();

        if (!comment) return alert('Comment cannot be empty');

        $.ajax({
            url: "{{ route('posts.comment', $post) }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                comment: comment
            },
            success: function () {
                $('#comment-text').val('');

                $('#comments-list').prepend(`
                    <div class="border rounded-lg p-4 dark:border-gray-700">
                        <p class="text-sm text-gray-800 dark:text-gray-200">
                            ${comment}
                        </p>
                        <span class="text-xs text-gray-500">
                            — You, just now
                        </span>
                    </div>
                `);
            }
        });
    });

});
</script>
