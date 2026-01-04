<nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            {{-- Logo --}}
            <a href="{{ route('home') }}"
               class="text-xl font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition">
                BlogApp
            </a>

            {{-- Nav --}}
            <div class="flex items-center space-x-4 sm:space-x-6">
                <a href="{{ route('posts.index') }}"
                   class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                    Posts
                </a>

                {{-- Dark Mode Toggle --}}
                <button onclick="toggleTheme()"
                        id="theme-toggle"
                        class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition p-1"
                        aria-label="Toggle theme">
                    <span id="theme-icon">🌙</span>
                </button>

                @auth
                    <a href="{{ route('profile.show', auth()->user()) }}"
                       class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="inline">
                        @csrf
                        <button type="submit"
                                class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition text-sm font-medium">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
// Global theme toggle function
window.toggleTheme = function() {
    const html = document.documentElement;
    const isDark = html.classList.contains('dark');
    const icons = document.querySelectorAll('#theme-icon');
    
    if (isDark) {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        icons.forEach(icon => {
            if (icon) icon.textContent = '🌙';
        });
    } else {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        icons.forEach(icon => {
            if (icon) icon.textContent = '☀️';
        });
    }
};

// Set initial theme and icon
(function() {
    const theme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const isDark = theme === 'dark' || (!theme && prefersDark);
    const icons = document.querySelectorAll('#theme-icon');
    
    if (isDark) {
        document.documentElement.classList.add('dark');
        icons.forEach(icon => {
            if (icon) icon.textContent = '☀️';
        });
    } else {
        document.documentElement.classList.remove('dark');
        icons.forEach(icon => {
            if (icon) icon.textContent = '🌙';
        });
    }
})();
</script>
