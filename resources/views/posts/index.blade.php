@extends('layouts.app')

@section('title', 'Latest Posts')

@section('content')
<!-- Success Alert -->
<div id="successAlert"
     class="fixed top-4 left-1/2 -translate-x-1/2 z-50 hidden
            max-w-sm w-[90%] sm:w-auto
            bg-green-600 text-white px-6 py-3
            rounded-lg shadow-lg text-center">
</div>

<!-- Header / Action Bar -->
<div class="flex flex-col sm:flex-row
            sm:items-center sm:justify-between
            gap-4 mb-6">

    <h1 class="text-xl sm:text-2xl font-semibold">
        Latest Posts
    </h1>

    <a href="javascript:void(0)"
       id="openPostModal"
       class="inline-flex items-center justify-center gap-2
              rounded-lg bg-indigo-600
              px-4 py-2.5 text-sm font-medium text-white
              hover:bg-indigo-700 transition
              focus:outline-none focus:ring-2 focus:ring-indigo-500
              w-full sm:w-auto">
        <x-heroicon-o-plus class="w-5 h-5" />
        Add Post
    </a>
</div>

<!-- Posts List -->
<div class="space-y-4">

    @if($posts->isEmpty())
        <p class="text-center text-gray-500 dark:text-gray-400">
            No posts available.
        </p>
    @endif

    @foreach ($posts as $post)
        <article
            class="bg-white dark:bg-gray-900
                   rounded-xl shadow-sm
                   hover:shadow-md transition
                   overflow-hidden">

            <!-- Thumbnail -->
            <a href="{{ route('posts.show', $post) }}">
            <img src="{{ $post->image ? asset('storage/posts/'.$post->image) : 'https://picsum.photos/600/400' }}"
                 alt="{{ $post->title }}"
                 class="w-full h-40 sm:h-48 object-cover">
            </a>

            <div class="p-4 sm:p-6 space-y-3">

                <h2 class="text-base sm:text-lg font-semibold
                           text-gray-900 dark:text-white">
                    <a href="{{ route('posts.show', $post) }}"
                       class="hover:text-indigo-600">
                        {{ $post->title }}
                    </a>
                </h2>

                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3">
                    {{ $post->excerpt }}
                </p>

                <div class="flex items-center justify-between
                            text-xs sm:text-sm text-gray-500">
                    <span>
                        {{ $post->created_at->format('M d, Y') }}
                    </span>

                    <a href="{{ route('posts.show', $post) }}"
                       class="text-indigo-600 hover:underline">
                        Read more →
                    </a>
                </div>

            </div>
        </article>
    @endforeach

</div>

<div class="mt-10">
    {{ $posts->links() }}
</div>

<div id="login_alert_modal"
     class="fixed inset-0 z-50 hidden items-center justify-center">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50"></div>

    <!-- Modal Box -->
    <div class="relative w-full max-w-md rounded-xl bg-white dark:bg-gray-900 shadow-lg">

        <!-- Header -->
        <div class="flex items-center justify-between border-b px-6 py-4 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Please Login to Continue
            </h2>
            <button type="button"
                    class="closeLoginModal text-gray-500 hover:text-gray-700 dark:hover:text-white">
                ✕
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 text-gray-600 dark:text-gray-300">
            You must be logged in to add a new post.
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 px-6 pb-6">
            <a href="{{ route('login') }}"
               class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                Login
            </a>

            <button type="button"
                    class="closeLoginModal px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 cursor-pointer">
                Cancel
            </button>
        </div>

    </div>
</div>


<!-- Add Post Modal -->
<div id="post_model_add"
     class="fixed inset-0 z-50 hidden
            items-end sm:items-center
            justify-center">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50"></div>

    <!-- Modal Box -->
    <div class="relative w-full sm:max-w-2xl
                bg-white dark:bg-gray-900
                rounded-t-2xl sm:rounded-xl
                shadow-lg
                max-h-[90vh] overflow-y-auto
                mx-2 sm:mx-4">

        <!-- Header -->
        <div class="flex items-center justify-between
                    border-b px-4 sm:px-6 py-3 sm:py-4
                    dark:border-gray-700">
            <h2 class="text-base sm:text-lg font-semibold
                       text-gray-800 dark:text-white">
                Add New Post
            </h2>

            <button type="button"
                    class="closeModal text-gray-500
                           hover:text-gray-700
                           dark:hover:text-white
                           text-xl">
                ✕
            </button>
        </div>

        <!-- Body -->
        <form id="postForm"
              method="POST"
              action="{{ route('posts.store') }}"
              enctype="multipart/form-data"
              class="p-4 sm:p-6 space-y-4">

            @csrf

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Title
                </label>
                <input type="text"
                       name="title"
                       required
                       placeholder="Enter post title"
                       class="mt-1 w-full rounded-lg
                              px-3 sm:px-4 py-2.5 sm:py-3
                              text-sm sm:text-base
                              border-gray-300 dark:border-gray-700
                              dark:bg-gray-800 dark:text-white
                              focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Content -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Content
                </label>
                <textarea name="content"
                          rows="4"
                          required
                          placeholder="Write your post content..."
                          class="mt-1 w-full rounded-lg
                                 px-3 sm:px-4 py-2.5 sm:py-3
                                 text-sm sm:text-base
                                 border-gray-300 dark:border-gray-700
                                 dark:bg-gray-800 dark:text-white
                                 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <!-- Image Upload -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Featured Image
                </label>

                <label for="imageUpload"
                       class="flex flex-col items-center justify-center
                              w-full h-40 sm:h-48
                              border-2 border-dashed rounded-lg
                              cursor-pointer
                              border-gray-300 dark:border-gray-700
                              bg-gray-50 dark:bg-gray-800
                              hover:bg-gray-100 dark:hover:bg-gray-700
                              transition">

                    <!-- Preview -->
                    <img id="imagePreview"
                         class="hidden w-full h-full object-cover rounded-lg"
                         alt="Image Preview">

                    <!-- Placeholder -->
                    <div id="imagePlaceholder"
                         class="flex flex-col items-center justify-center
                                text-gray-500 dark:text-gray-400">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 mb-2"
                             fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 7h18M3 7l3-3m-3 3l3 3M21 7l-3-3m3 3l-3 3M12 17v-6"/>
                        </svg>
                        <p class="text-xs sm:text-sm">Click to upload image</p>
                        <p class="text-[10px] sm:text-xs">PNG, JPG, WEBP</p>
                    </div>

                    <input id="imageUpload"
                           type="file"
                           name="image"
                           accept="image/*"
                           class="hidden"
                           onchange="previewImage(event)">
                </label>
            </div>

            <!-- Footer -->
            <div class="flex flex-col sm:flex-row
                        justify-end gap-3 pt-4">
                <button type="button"
                        class="closeModal
                               w-full sm:w-auto
                               rounded-lg px-4 py-2
                               text-sm font-medium
                               bg-gray-100 hover:bg-gray-200
                               dark:bg-gray-700 dark:hover:bg-gray-600
                               dark:text-white cursor-pointer">
                    Cancel
                </button>

                <button type="submit"
                        class="w-full sm:w-auto
                               rounded-lg px-4 py-2
                               text-sm font-medium text-white
                               bg-indigo-600 hover:bg-indigo-700 cursor-pointer">
                    Save Post
                </button>
            </div>

        </form>
    </div>
</div>





@push('scripts')
    <script>
        window.isAuthenticated = @json(auth()->check());
        // console.log('Posts page loaded');

        // function confirmDelete() {
        //     return confirm('Are you sure?');
        // }

        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('imagePlaceholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
// document.querySelectorAll('.closeModal').forEach(btn => {
//     btn.addEventListener('click', function (e) {
//         e.preventDefault();
//         document.getElementById('post_model_add').classList.add('hidden');
//     });
// });
        $(document).ready(function () {
    // Open modal
            $('#openPostModal').on('click', function () {
                if (window.isAuthenticated) {
                    $('#post_model_add')
                        .removeClass('hidden')
                        .addClass('flex');
                } else {
                    $('#login_alert_modal')
                        .removeClass('hidden')
                        .addClass('flex');
                }
            });

            $('.closeModal, #post_model_add .bg-black\\/50').on('click', function () {
                $('#post_model_add')
                    .removeClass('flex')
                    .addClass('hidden');
            });

            $('.closeLoginModal, #login_alert_modal .bg-black\\/50').on('click', function () {
                $('#login_alert_modal')
                    .removeClass('flex')
                    .addClass('hidden');
            });
            $('#postForm').on('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
    if (response.status === 'success') {
        $('#successAlert')
            .text(response.message)
            .slideDown()
            .delay(2000)
            .slideUp();

        // Close modal
        $('#post_model_add')
            .removeClass('flex')
            .addClass('hidden');

        // Reset form
        $('#postForm')[0].reset();
        $('#imagePreview').addClass('hidden');
        $('#imagePlaceholder').removeClass('hidden');

        location.reload();
    }
},
        error: function(xhr) {
            alert('Something went wrong');
        }
    });
});

        });
    </script>
@endpush
@endsection



