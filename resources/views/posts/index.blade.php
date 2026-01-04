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

    <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">
        Latest Posts
    </h1>

    <div class="flex flex-col sm:flex-row gap-3">
        @auth
        <a href="{{ route('profile.my-posts') }}"
           class="inline-flex items-center justify-center gap-2
                  rounded-lg bg-gray-100 dark:bg-gray-800
                  px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300
                  hover:bg-gray-200 dark:hover:bg-gray-700 transition
                  w-full sm:w-auto">
            My Posts
        </a>
        @endauth
        
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
</div>

<!-- Filters Dropdown -->
<div class="mb-6">
    <div class="relative inline-block">
        <button id="filter-toggle"
                class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filters
            @if(request('user') || request('date'))
                <span class="ml-1 px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full text-xs">
                    {{ (request('user') ? 1 : 0) + (request('date') ? 1 : 0) }}
                </span>
            @endif
        </button>

        <div id="filter-dropdown"
             class="hidden absolute right-0 mt-2 w-64 sm:w-80 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50 p-4">
            <form method="GET" action="{{ route('posts.index') }}" id="filter-form">
                <!-- Author Filter -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Filter by Author
                    </label>
                    <select name="user" 
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Authors</option>
                        @foreach(\App\Models\User::has('posts')->get() as $user)
                            <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Filter by Date
                    </label>
                    <select name="date" 
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Dates</option>
                        <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ request('date') == 'year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                        Apply Filters
                    </button>
                    @if(request('user') || request('date'))
                    <a href="{{ route('posts.index') }}" 
                       class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition text-sm font-medium">
                        Clear
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
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
                    {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 150) }}
                </p>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        <span>
                            By <a href="{{ route('profile.show', $post->author ?? 1) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline">
                                {{ $post->author->name ?? 'Admin' }}
                            </a>
                        </span>
                        <span>•</span>
                        <span>{{ $post->created_at->format('M d, Y') }}</span>
                        <span>•</span>
                        <span class="flex items-center gap-1">
                            👁️ {{ $post->views_count ?? 0 }}
                        </span>
                        <span>•</span>
                        <span class="flex items-center gap-1">
                            ❤️ {{ $post->likes->count() ?? 0 }}
                        </span>
                        <span>•</span>
                        <span class="flex items-center gap-1">
                            💬 {{ $post->comments->count() ?? 0 }}
                        </span>
                    </div>

                    <a href="{{ route('posts.show', $post) }}"
                       class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
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
                <label for="post-title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Title
                </label>
                <input type="text"
                       id="post-title"
                       name="title"
                       required
                       minlength="3"
                       maxlength="255"
                       placeholder="Enter post title"
                       class="mt-1 w-full rounded-lg
                              px-3 sm:px-4 py-2.5 sm:py-3
                              text-sm sm:text-base
                              border border-gray-300 dark:border-gray-700
                              dark:bg-gray-800 dark:text-white
                              focus:ring-indigo-500 focus:border-indigo-500 transition
                              error:border-red-500">
                <span id="title-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden block"></span>
            </div>

            <!-- Content -->
            <div>
                <label for="post-content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Content
                </label>
                <textarea id="post-content"
                          name="content"
                          rows="4"
                          required
                          minlength="10"
                          maxlength="5000"
                          placeholder="Write your post content..."
                          class="mt-1 w-full rounded-lg
                                 px-3 sm:px-4 py-2.5 sm:py-3
                                 text-sm sm:text-base
                                 border border-gray-300 dark:border-gray-700
                                 dark:bg-gray-800 dark:text-white
                                 focus:ring-indigo-500 focus:border-indigo-500 transition
                                 error:border-red-500 resize-none"></textarea>
                <span id="content-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden block"></span>
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
                <span id="image-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden block"></span>
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

        // Filter dropdown toggle
        $(document).ready(function() {
            $('#filter-toggle').on('click', function(e) {
                e.stopPropagation();
                $('#filter-dropdown').toggleClass('hidden');
            });

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#filter-toggle, #filter-dropdown').length) {
                    $('#filter-dropdown').addClass('hidden');
                }
            });

            // Prevent dropdown from closing when clicking inside
            $('#filter-dropdown').on('click', function(e) {
                e.stopPropagation();
            });
        });
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

                const form = $(this);
                const formData = new FormData(this);
                const submitBtn = form.find('button[type="submit"]');

                // Clear previous errors
                $('[id$="-error"]').addClass('hidden').text('');
                form.find('input, textarea').removeClass('border-red-500');

                // Frontend validation
                let isValid = true;
                const title = $('#post-title').val().trim();
                const content = $('#post-content').val().trim();

                if (!title) {
                    $('#title-error').removeClass('hidden').text('Title is required');
                    $('#post-title').addClass('border-red-500');
                    isValid = false;
                } else if (title.length < 3) {
                    $('#title-error').removeClass('hidden').text('Title must be at least 3 characters');
                    $('#post-title').addClass('border-red-500');
                    isValid = false;
                } else if (title.length > 255) {
                    $('#title-error').removeClass('hidden').text('Title must not exceed 255 characters');
                    $('#post-title').addClass('border-red-500');
                    isValid = false;
                }

                if (!content) {
                    $('#content-error').removeClass('hidden').text('Content is required');
                    $('#post-content').addClass('border-red-500');
                    isValid = false;
                } else if (content.length < 10) {
                    $('#content-error').removeClass('hidden').text('Content must be at least 10 characters');
                    $('#post-content').addClass('border-red-500');
                    isValid = false;
                } else if (content.length > 5000) {
                    $('#content-error').removeClass('hidden').text('Content must not exceed 5000 characters');
                    $('#post-content').addClass('border-red-500');
                    isValid = false;
                }

                if (!isValid) {
                    return;
                }

                submitBtn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#successAlert')
                                .text(response.message)
                                .removeClass('hidden')
                                .slideDown()
                                .delay(2000)
                                .slideUp();

                            // Close modal
                            $('#post_model_add')
                                .removeClass('flex')
                                .addClass('hidden');

                            // Reset form
                            form[0].reset();
                            $('#imagePreview').addClass('hidden');
                            $('#imagePlaceholder').removeClass('hidden');
                            $('[id$="-error"]').addClass('hidden').text('');
                            form.find('input, textarea').removeClass('border-red-500');

                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).text('Save Post');

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors || {};
                            
                            // Display validation errors
                            Object.keys(errors).forEach(field => {
                                const errorSpan = $(`#${field}-error`);
                                const input = $(`[name="${field}"]`);
                                if (errorSpan.length) {
                                    errorSpan.removeClass('hidden').text(errors[field][0]);
                                    input.addClass('border-red-500');
                                }
                            });
                        } else {
                            alert('Something went wrong. Please try again.');
                        }
                    }
                });
            });

            // Clear errors on input
            $('#postForm').find('input, textarea').on('input', function() {
                const fieldName = $(this).attr('name');
                $(`#${fieldName}-error`).addClass('hidden').text('');
                $(this).removeClass('border-red-500');
            });

        });
    </script>
@endpush
@endsection



