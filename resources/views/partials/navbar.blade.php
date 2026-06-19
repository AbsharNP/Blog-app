<nav class="sticky top-0 z-50
            bg-white/70 dark:bg-gray-900/70
            backdrop-blur-xl
            border-b border-gray-200/60 dark:border-gray-800/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center gap-4">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0 group">
                <span class="w-9 h-9 rounded-xl bg-brand flex items-center justify-center
                             text-white font-black text-lg shadow-lg shadow-sky-500/30
                             group-hover:scale-105 group-hover:rotate-3 transition-transform">
                    B
                </span>
                <span class="text-xl font-extrabold text-gradient hidden sm:inline">BlogApp</span>
            </a>

            {{-- Right side --}}
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('posts.index') }}"
                   class="px-3 py-2 rounded-lg text-sm font-semibold
                          text-gray-600 dark:text-gray-300
                          hover:text-sky-600 dark:hover:text-sky-400
                          hover:bg-sky-50 dark:hover:bg-sky-900/20 transition">
                    Posts
                </a>

                {{-- Dark Mode Toggle --}}
                <button onclick="toggleTheme()"
                        id="theme-toggle"
                        class="w-9 h-9 flex items-center justify-center rounded-lg
                               text-gray-600 dark:text-gray-300
                               hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                        aria-label="Toggle theme">
                    <span id="theme-icon">🌙</span>
                </button>

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full
                              bg-gray-100/80 dark:bg-gray-800/80
                              hover:bg-gray-200 dark:hover:bg-gray-700
                              transition group">
                        <span class="w-8 h-8 rounded-full bg-brand p-[2px] shadow">
                            <span class="w-full h-full rounded-full bg-white dark:bg-gray-900
                                         flex items-center justify-center text-xs font-bold
                                         text-sky-600 dark:text-sky-400">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </span>
                        <span class="hidden sm:inline text-sm font-semibold text-gray-700 dark:text-gray-200">
                            Dashboard
                        </span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-3 py-2 rounded-lg text-sm font-semibold
                                       text-gray-500 dark:text-gray-400
                                       hover:text-red-600 dark:hover:text-red-400
                                       hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="px-3 py-2 rounded-lg text-sm font-semibold
                              text-gray-600 dark:text-gray-300
                              hover:text-sky-600 dark:hover:text-sky-400
                              hover:bg-sky-50 dark:hover:bg-sky-900/20 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-brand text-white px-4 py-2 rounded-full text-sm font-semibold
                              shadow-lg shadow-sky-500/30
                              hover:shadow-xl hover:shadow-cyan-500/40
                              hover:scale-[1.03] active:scale-95 transition">
                        Get Started
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
window.toggleTheme = function() {
    const html = document.documentElement;
    const isDark = html.classList.contains('dark');
    const icons = document.querySelectorAll('#theme-icon');
    if (isDark) {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        icons.forEach(icon => { if (icon) icon.textContent = '🌙'; });
    } else {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        icons.forEach(icon => { if (icon) icon.textContent = '☀️'; });
    }
};
(function() {
    const theme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const isDark = theme === 'dark' || (!theme && prefersDark);
    const icons = document.querySelectorAll('#theme-icon');
    icons.forEach(icon => { if (icon) icon.textContent = isDark ? '☀️' : '🌙'; });
})();
</script>
