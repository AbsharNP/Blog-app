<nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            {{-- Logo --}}
            <a href="{{ route('home') }}"
               class="text-xl font-semibold text-indigo-600 dark:text-indigo-400">
                BlogApp
            </a>

            {{-- Nav --}}
            <div class="flex items-center space-x-6">

                <a href="{{ route('posts.index') }}"
                   class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                    Posts
                </a>

                {{-- Dark Mode Toggle --}}
                <button onclick="toggleTheme()"
                        class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                    🌙
                </button>

                @auth
                    <a href="/dashboard"
                       class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                        Dashboard
                    </a>
                @else
                    <a href="/login"
                       class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                        Login
                    </a>
                @endauth

            </div>
        </div>
    </div>
</nav>

<script>
    function toggleTheme() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.theme = 'light';
        } else {
            document.documentElement.classList.add('dark');
            localStorage.theme = 'dark';
        }
    }
</script>
