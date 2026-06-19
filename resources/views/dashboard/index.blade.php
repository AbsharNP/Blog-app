@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Flash alerts --}}
<div id="successAlert"
     class="fixed top-20 left-1/2 -translate-x-1/2 z-[60] hidden max-w-sm w-[90%]
            bg-emerald-500 text-white px-6 py-3 rounded-2xl shadow-2xl text-center text-sm font-semibold"></div>
<div id="errorAlert"
     class="fixed top-20 left-1/2 -translate-x-1/2 z-[60] hidden max-w-sm w-[90%]
            bg-red-500 text-white px-6 py-3 rounded-2xl shadow-2xl text-center text-sm font-semibold"></div>

<div class="flex gap-6">

    {{-- =================== SIDEBAR =================== --}}
    <aside class="w-72 flex-shrink-0 hidden lg:block self-start sticky top-24 space-y-5">

        {{-- User Card --}}
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl shadow-sky-500/5
                    border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div class="h-20 bg-brand relative">
                <div class="absolute inset-0 opacity-25
                            bg-[radial-gradient(circle_at_30%_40%,white,transparent_50%)]"></div>
            </div>
            <div class="px-5 pb-5 -mt-10 text-center">
                <div class="w-20 h-20 mx-auto rounded-2xl bg-brand p-1 shadow-xl shadow-sky-500/30">
                    <div class="w-full h-full rounded-[14px] bg-white dark:bg-gray-900 flex items-center justify-center
                                text-3xl font-extrabold text-gradient">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
                <h2 class="font-extrabold text-gray-900 dark:text-white mt-3">{{ auth()->user()->name }}</h2>
                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>

                <div class="grid grid-cols-2 gap-2 mt-4 text-left">
                    <div class="rounded-2xl bg-gradient-to-br from-sky-500 to-sky-600 p-3 text-white shadow">
                        <p class="text-lg font-extrabold leading-none">{{ $stats['total_posts'] }}</p>
                        <p class="text-[11px] text-white/80 mt-1">Posts</p>
                    </div>
                    <div class="rounded-2xl bg-gradient-to-br from-cyan-400 to-cyan-600 p-3 text-white shadow">
                        <p class="text-lg font-extrabold leading-none">{{ $stats['total_likes'] }}</p>
                        <p class="text-[11px] text-white/80 mt-1">Likes</p>
                    </div>
                    <div class="rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-3 text-white shadow">
                        <p class="text-lg font-extrabold leading-none">{{ $stats['total_comments'] }}</p>
                        <p class="text-[11px] text-white/80 mt-1">Comments</p>
                    </div>
                    <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 p-3 text-white shadow">
                        <p class="text-lg font-extrabold leading-none">{{ $stats['total_views'] }}</p>
                        <p class="text-[11px] text-white/80 mt-1">Views</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Nav --}}
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl shadow-sky-500/5
                    border border-gray-100 dark:border-gray-800 p-3 space-y-1">
            <button data-tab="feed"
                    class="tab-trigger w-full text-left flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition">
                <span class="text-lg">🌐</span> All Posts
            </button>
            <button data-tab="myposts"
                    class="tab-trigger w-full text-left flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition">
                <span class="text-lg">📝</span> My Posts
            </button>
            <button onclick="openAddModal()"
                    class="w-full text-left flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold
                           text-white bg-brand shadow-lg shadow-sky-500/30 hover:scale-[1.02] active:scale-95 transition">
                <span class="text-lg">➕</span> Create Post
            </button>
        </div>
    </aside>

    {{-- =================== MAIN CONTENT =================== --}}
    <div class="flex-1 min-w-0 space-y-5">

        {{-- Composer / Action Bar --}}
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl shadow-sky-500/5
                    border border-gray-100 dark:border-gray-800 p-4">
            <div class="flex items-center gap-3 mb-4">
                <span class="w-11 h-11 flex-shrink-0 rounded-full bg-brand flex items-center justify-center text-white font-bold shadow-lg shadow-sky-500/30">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
                <button onclick="openAddModal()"
                        class="flex-1 text-left px-5 py-3 rounded-full
                               bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-sm
                               hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    What's on your mind, {{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}?
                </button>
                <button onclick="openAddModal()"
                        class="hidden sm:inline-flex items-center gap-2 bg-brand text-white px-4 py-2.5 rounded-full
                               text-sm font-semibold shadow-lg shadow-sky-500/30 hover:scale-[1.03] active:scale-95 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Post
                </button>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-2 border-t border-gray-100 dark:border-gray-800 pt-3">
                <button data-tab="feed"
                        class="tab-trigger flex-1 sm:flex-none px-5 py-2 rounded-full text-sm font-semibold transition">
                    🌐 All Posts
                </button>
                <button data-tab="myposts"
                        class="tab-trigger flex-1 sm:flex-none px-5 py-2 rounded-full text-sm font-semibold transition">
                    📝 My Posts
                </button>
            </div>
        </div>

        {{-- ===== ALL POSTS FEED ===== --}}
        <div id="tab-feed" class="space-y-5">
            @forelse($allPosts as $post)
                @include('dashboard.partials.feed-card', ['post' => $post, 'owner' => false])
            @empty
                <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 p-12 text-center">
                    <div class="text-5xl mb-3">📭</div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">No posts yet. Be the first to share!</p>
                </div>
            @endforelse
            <div>{{ $allPosts->appends(['tab' => 'feed'])->links() }}</div>
        </div>

        {{-- ===== MY POSTS ===== --}}
        <div id="tab-myposts" class="hidden space-y-5">
            @forelse($myPosts as $post)
                @include('dashboard.partials.feed-card', ['post' => $post, 'owner' => true])
            @empty
                <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 p-12 text-center">
                    <div class="text-5xl mb-3">✍️</div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium mb-4">You haven't posted anything yet.</p>
                    <button onclick="openAddModal()"
                            class="inline-flex items-center gap-2 bg-brand text-white px-5 py-2.5 rounded-full text-sm font-semibold
                                   shadow-lg shadow-sky-500/30 hover:scale-[1.03] active:scale-95 transition">
                        + Create Your First Post
                    </button>
                </div>
            @endforelse
            @if($myPosts->isNotEmpty())
                <div>{{ $myPosts->appends(['tab' => 'myposts'])->links() }}</div>
            @endif
        </div>

    </div>{{-- /main --}}
</div>{{-- /flex --}}


{{-- =================== ADD POST MODAL =================== --}}
@include('dashboard.partials.post-modal', [
    'id'       => 'addPostModal',
    'formId'   => 'addPostForm',
    'action'   => route('posts.store'),
    'method'   => 'POST',
    'heading'  => 'Create New Post',
    'prefix'   => 'add',
    'submit'   => 'Publish Post',
    'showHint' => false,
])

{{-- =================== EDIT POST MODAL =================== --}}
@include('dashboard.partials.post-modal', [
    'id'       => 'editPostModal',
    'formId'   => 'editPostForm',
    'action'   => '',
    'method'   => 'POST',
    'heading'  => 'Edit Post',
    'prefix'   => 'edit',
    'submit'   => 'Update Post',
    'showHint' => true,
])


{{-- =================== DELETE CONFIRM MODAL =================== --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative w-full max-w-sm bg-white dark:bg-gray-900 rounded-3xl shadow-2xl">
        <div class="p-6">
            <div class="w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-2xl mb-4">
                🗑️
            </div>
            <h3 class="text-lg font-extrabold text-gray-900 dark:text-white mb-1">Delete this post?</h3>
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 line-clamp-2" id="delete-post-title"></p>
            <p class="text-xs text-red-500">This action cannot be undone.</p>
        </div>
        <div class="flex gap-3 px-6 pb-6">
            <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 rounded-full text-sm font-semibold
                           bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700
                           dark:text-white transition">
                Cancel
            </button>
            <button id="confirm-delete-btn" onclick="confirmDelete()"
                    class="flex-1 px-4 py-2.5 rounded-full text-sm font-semibold text-white
                           bg-red-600 hover:bg-red-700 shadow-lg shadow-red-500/30 transition
                           disabled:opacity-50 disabled:cursor-not-allowed">
                Delete
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
const ACTIVE_TAB = '{{ $tab }}';
let deletePostId = null;

const TAB_ACTIVE   = ['bg-brand', 'text-white', 'shadow-lg', 'shadow-sky-500/30'];
const TAB_INACTIVE = ['text-gray-600', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-800'];

// ─── Tab switching ──────────────────────────────────────────────────────────
function switchTab(tab) {
    document.getElementById('tab-feed').classList.toggle('hidden', tab !== 'feed');
    document.getElementById('tab-myposts').classList.toggle('hidden', tab !== 'myposts');

    document.querySelectorAll('.tab-trigger').forEach(btn => {
        const active = btn.dataset.tab === tab;
        btn.classList.remove(...TAB_ACTIVE, ...TAB_INACTIVE);
        btn.classList.add(...(active ? TAB_ACTIVE : TAB_INACTIVE));
    });
}

// ─── Shared image preview ───────────────────────────────────────────────────
function previewImage(event, previewId, placeholderId) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById(previewId).src = e.target.result;
        document.getElementById(previewId).classList.remove('hidden');
        document.getElementById(placeholderId).classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

// ─── Modals ─────────────────────────────────────────────────────────────────
function openAddModal()    { $('#addPostModal').removeClass('hidden').addClass('flex'); }
function closeAddModal()   { $('#addPostModal').removeClass('flex').addClass('hidden'); }
function closeEditModal()  { $('#editPostModal').removeClass('flex').addClass('hidden'); }
function closeDeleteModal() {
    $('#deleteModal').removeClass('flex').addClass('hidden');
    deletePostId = null;
    $('#confirm-delete-btn').prop('disabled', false).text('Delete');
}

function confirmDelete() {
    if (!deletePostId) return;
    const btn = $('#confirm-delete-btn');
    btn.prop('disabled', true).text('Deleting...');
    $.ajax({
        url: '/posts/' + deletePostId,
        method: 'POST',
        data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
        success: function (res) {
            closeDeleteModal();
            $('[data-post-id="' + deletePostId + '"]').fadeOut(300, function () { $(this).remove(); });
            showAlert('success', res.message || 'Post deleted.');
        },
        error: function () {
            btn.prop('disabled', false).text('Delete');
            showAlert('error', 'Failed to delete. Please try again.');
        }
    });
}

// ─── Alerts ─────────────────────────────────────────────────────────────────
function showAlert(type, msg) {
    const el = $('#' + (type === 'success' ? 'successAlert' : 'errorAlert'));
    el.text(msg).removeClass('hidden');
    setTimeout(() => el.addClass('hidden'), 3000);
}

// ─── Validation helpers ─────────────────────────────────────────────────────
function validateForm(prefix) {
    const title   = $(`#${prefix}-title`).val().trim();
    const content = $(`#${prefix}-content`).val().trim();
    let valid = true;

    $(`#${prefix}-title-error, #${prefix}-content-error`).addClass('hidden').text('');
    $(`#${prefix}-title, #${prefix}-content`).removeClass('border-red-500');

    if (!title || title.length < 3) {
        $(`#${prefix}-title-error`).removeClass('hidden').text(title ? 'Title must be at least 3 characters.' : 'Title is required.');
        $(`#${prefix}-title`).addClass('border-red-500');
        valid = false;
    }
    if (!content || content.length < 10) {
        $(`#${prefix}-content-error`).removeClass('hidden').text(content ? 'Content must be at least 10 characters.' : 'Content is required.');
        $(`#${prefix}-content`).addClass('border-red-500');
        valid = false;
    }
    return valid;
}

function handleValidationErrors(xhr, prefix) {
    if (xhr.status === 422) {
        const errors = xhr.responseJSON.errors || {};
        Object.keys(errors).forEach(field => {
            $(`#${prefix}-${field}-error`).removeClass('hidden').text(errors[field][0]);
            $(`#${prefix}-${field}`).addClass('border-red-500');
        });
    } else {
        showAlert('error', 'Something went wrong. Please try again.');
    }
}

// ─── DOM ready ──────────────────────────────────────────────────────────────
$(document).ready(function () {
    switchTab(ACTIVE_TAB);

    // Tab triggers
    $('.tab-trigger').on('click', function () { switchTab($(this).data('tab')); });

    // Edit button → populate edit modal
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        $('#edit-title').val($(this).data('title'));
        $('#edit-content').val($(this).data('content'));
        $('#editPostForm').attr('action', '/posts/' + id + '/update');

        const image = $(this).data('image');
        if (image) {
            $('#edit-image-preview').attr('src', image).removeClass('hidden');
            $('#edit-image-placeholder').addClass('hidden');
        } else {
            $('#edit-image-preview').attr('src', '').addClass('hidden');
            $('#edit-image-placeholder').removeClass('hidden');
        }
        $('#edit-image').val('');
        $('#edit-title-error, #edit-content-error').addClass('hidden').text('');
        $('#edit-title, #edit-content').removeClass('border-red-500');

        $('#editPostModal').removeClass('hidden').addClass('flex');
    });

    // Delete button → confirm modal
    $(document).on('click', '.delete-btn', function () {
        deletePostId = $(this).data('id');
        $('#delete-post-title').text('"' + $(this).data('title') + '"');
        $('#deleteModal').removeClass('hidden').addClass('flex');
    });

    // Add submit
    $('#addPostForm').on('submit', function (e) {
        e.preventDefault();
        if (!validateForm('add')) return;
        const btn = $('#add-submit-btn');
        btn.prop('disabled', true).text('Publishing...');
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.status === 'success') {
                    closeAddModal();
                    showAlert('success', res.message);
                    setTimeout(() => window.location.href = '{{ route("dashboard") }}?tab=myposts', 700);
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).text('Publish Post');
                handleValidationErrors(xhr, 'add');
            }
        });
    });

    // Edit submit
    $('#editPostForm').on('submit', function (e) {
        e.preventDefault();
        if (!validateForm('edit')) return;
        const btn = $('#edit-submit-btn');
        btn.prop('disabled', true).text('Updating...');
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.status === 'success') {
                    closeEditModal();
                    showAlert('success', res.message);
                    setTimeout(() => window.location.href = '{{ route("dashboard") }}?tab=myposts', 700);
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).text('Update Post');
                handleValidationErrors(xhr, 'edit');
            }
        });
    });

    // Clear field errors on input
    $('#addPostForm, #editPostForm').find('input, textarea').on('input', function () {
        const prefix = $(this).closest('form').attr('id') === 'addPostForm' ? 'add' : 'edit';
        $(`#${prefix}-${$(this).attr('name')}-error`).addClass('hidden').text('');
        $(this).removeClass('border-red-500');
    });
});
</script>
@endpush
@endsection
