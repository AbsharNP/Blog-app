@extends('layouts.app')

@section('title', 'Latest Posts')

@section('content')

{{-- Hero --}}
<div class="relative overflow-hidden rounded-3xl bg-brand p-8 sm:p-12 mb-8 shadow-2xl shadow-sky-500/30">
    <div class="absolute inset-0 opacity-20
                bg-[radial-gradient(circle_at_20%_20%,white,transparent_40%),radial-gradient(circle_at_80%_80%,white,transparent_40%)]"></div>
    <div class="relative">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                     bg-white/20 text-white text-xs font-semibold backdrop-blur mb-4">
            ✨ Fresh stories from the community
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight mb-3">
            Discover great reads
        </h1>
        <p class="text-sky-100 text-base sm:text-lg max-w-xl">
            Explore the latest posts, follow your favorite authors, and join the conversation.
        </p>
        @auth
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 rounded-full
                  bg-white text-sky-600 font-semibold text-sm
                  shadow-lg hover:scale-[1.03] active:scale-95 transition">
            Go to your Dashboard →
        </a>
        @else
        <a href="{{ route('register') }}"
           class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 rounded-full
                  bg-white text-sky-600 font-semibold text-sm
                  shadow-lg hover:scale-[1.03] active:scale-95 transition">
            Join BlogApp — it's free →
        </a>
        @endauth
    </div>
</div>

{{-- Bar: title + filters --}}
<div class="flex items-center justify-between gap-4 mb-6">
    <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white">
        Latest Posts
    </h2>

    <div class="relative inline-block">
        <button id="filter-toggle"
                class="flex items-center gap-2 px-4 py-2 rounded-full
                       bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800
                       text-sm font-semibold text-gray-700 dark:text-gray-300
                       shadow-sm hover:shadow-md transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filters
            @if(request('user') || request('date'))
                <span class="ml-0.5 px-2 py-0.5 bg-brand text-white rounded-full text-xs">
                    {{ (request('user') ? 1 : 0) + (request('date') ? 1 : 0) }}
                </span>
            @endif
        </button>

        <div id="filter-dropdown"
             class="hidden absolute right-0 mt-2 w-72 sm:w-80
                    bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800
                    rounded-2xl shadow-2xl z-50 p-5">
            <form method="GET" action="{{ route('posts.index') }}">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Author</label>
                    <select name="user"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="">All Authors</option>
                        @foreach(\App\Models\User::has('posts')->get() as $u)
                            <option value="{{ $u->id }}" {{ request('user') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Date</label>
                    <select name="date"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="">All Dates</option>
                        <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week"  {{ request('date') == 'week'  ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year"  {{ request('date') == 'year'  ? 'selected' : '' }}>This Year</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-brand text-white rounded-xl text-sm font-semibold shadow-lg shadow-sky-500/30 hover:scale-[1.02] transition">
                        Apply
                    </button>
                    @if(request('user') || request('date'))
                    <a href="{{ route('posts.index') }}"
                       class="px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        Clear
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Posts grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($posts as $post)
        <article class="card-hover group bg-white dark:bg-gray-900 rounded-2xl
                        border border-gray-100 dark:border-gray-800
                        shadow-sm hover:shadow-2xl hover:shadow-sky-500/10
                        overflow-hidden flex flex-col animate-card"
                 style="animation-delay: {{ $loop->index * 60 }}ms">

            <a href="{{ route('posts.show', $post) }}" class="relative block overflow-hidden">
                <img src="{{ $post->image ? asset('storage/posts/'.$post->image) : 'https://picsum.photos/seed/'.$post->id.'/800/500' }}"
                     alt="{{ $post->title }}"
                     class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
            </a>

            <div class="p-5 flex flex-col flex-1">
                {{-- author row --}}
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-7 h-7 rounded-full bg-brand flex items-center justify-center text-white text-[11px] font-bold">
                        {{ strtoupper(substr($post->author->name ?? 'A', 0, 1)) }}
                    </span>
                    <a href="{{ route('profile.show', $post->author ?? 1) }}"
                       class="text-xs font-semibold text-gray-700 dark:text-gray-300 hover:text-sky-600 dark:hover:text-sky-400">
                        {{ $post->author->name ?? 'Admin' }}
                    </a>
                    <span class="text-gray-300 dark:text-gray-600 text-xs">·</span>
                    <span class="text-xs text-gray-400">{{ $post->created_at->format('M d') }}</span>
                </div>

                <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-snug mb-2 line-clamp-2">
                    <a href="{{ route('posts.show', $post) }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition">
                        {{ $post->title }}
                    </a>
                </h3>

                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4 flex-1">
                    {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 110) }}
                </p>

                <div class="flex items-center gap-3 pt-3 border-t border-gray-100 dark:border-gray-800 text-xs font-medium">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">👁 {{ $post->views_count ?? 0 }}</span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-900/20 text-red-500">❤️ {{ $post->likes->count() }}</span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500">💬 {{ $post->comments->count() }}</span>
                </div>
            </div>
        </article>
    @empty
        <div class="col-span-full bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-12 text-center">
            <div class="text-5xl mb-3">📭</div>
            <p class="text-gray-500 dark:text-gray-400 font-medium">No posts available yet.</p>
        </div>
    @endforelse
</div>

<div class="mt-10">
    {{ $posts->links() }}
</div>

@push('scripts')
<script>
$(document).ready(function () {
    $('#filter-toggle').on('click', function (e) {
        e.stopPropagation();
        $('#filter-dropdown').toggleClass('hidden');
    });
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#filter-toggle, #filter-dropdown').length) {
            $('#filter-dropdown').addClass('hidden');
        }
    });
    $('#filter-dropdown').on('click', function (e) { e.stopPropagation(); });
});
</script>
@endpush
@endsection
