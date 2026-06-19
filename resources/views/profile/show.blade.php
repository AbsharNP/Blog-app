@extends('layouts.app')

@section('title', $user->name . ' - Profile')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Profile header with gradient cover --}}
    <div class="relative bg-white dark:bg-gray-900 rounded-3xl shadow-xl shadow-sky-500/5
                border border-gray-100 dark:border-gray-800 overflow-hidden mb-8">

        {{-- Cover --}}
        <div class="h-32 sm:h-40 bg-brand relative">
            <div class="absolute inset-0 opacity-25
                        bg-[radial-gradient(circle_at_30%_30%,white,transparent_45%),radial-gradient(circle_at_75%_60%,white,transparent_40%)]"></div>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-end gap-4 sm:gap-6 -mt-12 sm:-mt-14">
                {{-- Avatar --}}
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-brand p-1 shadow-2xl shadow-sky-500/30">
                    <div class="w-full h-full rounded-[20px] bg-white dark:bg-gray-900 flex items-center justify-center
                                text-4xl sm:text-5xl font-extrabold text-gradient">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>

                <div class="flex-1 pb-1">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">
                        {{ $user->name }}
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $user->email }}</p>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mt-6">
                @php
                    $cards = [
                        ['label' => 'Posts',    'value' => $stats['total_posts'],    'grad' => 'from-sky-500 to-sky-600', 'icon' => '📝'],
                        ['label' => 'Likes',    'value' => $stats['total_likes'],    'grad' => 'from-cyan-500 to-blue-600',     'icon' => '❤️'],
                        ['label' => 'Comments', 'value' => $stats['total_comments'], 'grad' => 'from-blue-500 to-cyan-600',      'icon' => '💬'],
                        ['label' => 'Views',    'value' => $stats['total_views'],    'grad' => 'from-emerald-500 to-green-600',  'icon' => '👁'],
                    ];
                @endphp
                @foreach($cards as $c)
                    <div class="rounded-2xl bg-gradient-to-br {{ $c['grad'] }} p-4 text-white shadow-lg">
                        <div class="text-2xl mb-1">{{ $c['icon'] }}</div>
                        <div class="text-2xl font-extrabold">{{ $c['value'] }}</div>
                        <div class="text-xs font-medium text-white/80">{{ $c['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Posts --}}
    <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white mb-5">
        Posts by {{ $user->name }}
    </h2>

    @if($posts->isEmpty())
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-12 text-center">
            <div class="text-5xl mb-3">🗒️</div>
            <p class="text-gray-500 dark:text-gray-400 font-medium">No posts yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($posts as $post)
                <article class="card-hover group bg-white dark:bg-gray-900 rounded-2xl
                                border border-gray-100 dark:border-gray-800
                                shadow-sm hover:shadow-2xl hover:shadow-sky-500/10 overflow-hidden flex flex-col">
                    <a href="{{ route('posts.show', $post) }}" class="block overflow-hidden">
                        <img src="{{ $post->image ? asset('storage/posts/'.$post->image) : 'https://picsum.photos/seed/'.$post->id.'/800/500' }}"
                             alt="{{ $post->title }}"
                             class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500">
                    </a>
                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="font-bold text-gray-900 dark:text-white line-clamp-2 mb-3 flex-1">
                            <a href="{{ route('posts.show', $post) }}" class="hover:text-sky-600 dark:hover:text-sky-400">
                                {{ $post->title }}
                            </a>
                        </h3>
                        <div class="flex items-center justify-between text-xs text-gray-400">
                            <span>{{ $post->created_at->format('M d, Y') }}</span>
                            <div class="flex items-center gap-2 font-medium">
                                <span>👁 {{ $post->views_count }}</span>
                                <span class="text-red-500">❤️ {{ $post->likes->count() }}</span>
                                <span class="text-blue-500">💬 {{ $post->comments->count() }}</span>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
