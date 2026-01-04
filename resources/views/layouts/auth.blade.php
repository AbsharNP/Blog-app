<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentication') - Blog</title>

    @vite('resources/css/app.css')

    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = theme === 'dark' || (!theme && prefersDark);
            
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>

<body class="min-h-screen flex flex-col
             bg-gradient-to-br from-indigo-50 via-white to-purple-50
             dark:from-gray-900 dark:via-gray-950 dark:to-gray-900
             transition-colors">

    <!-- Background Pattern (hidden on very small screens) -->
    <div class="absolute inset-0 -z-10 overflow-hidden hidden sm:block">
        <div class="absolute inset-0
            bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),
                linear-gradient(to_bottom,#8080800a_1px,transparent_1px)]
            bg-[size:14px_24px]">
        </div>
    </div>

    <!-- Dark Mode Toggle -->
    <div class="absolute top-4 right-4">
        <button onclick="toggleTheme()"
                id="theme-toggle"
                class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                aria-label="Toggle theme">
            <span id="theme-icon">🌙</span>
        </button>
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-sm sm:max-w-md md:max-w-lg">

            <!-- Auth Card -->
            <div class="bg-white dark:bg-gray-900
                        rounded-xl sm:rounded-2xl
                        shadow-lg sm:shadow-xl
                        overflow-hidden">

                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600
                            px-6 sm:px-8 py-8 sm:py-10
                            text-center">
                    <div class="mx-auto w-14 h-14 sm:w-16 sm:h-16
                                flex items-center justify-center
                                bg-white/20 rounded-full mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white"
                             fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5
                                     S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18
                                     7.5 18s3.332.477 4.5 1.253m0-13
                                     C13.168 5.477 14.754 5 16.5 5
                                     c1.747 0 3.332.477 4.5 1.253v13
                                     C19.832 18.477 18.247 18 16.5 18
                                     c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>

                    <h1 class="text-xl sm:text-2xl font-bold text-white">
                        @yield('auth-title', 'Welcome Back')
                    </h1>

                    <p class="text-indigo-100 mt-2 text-xs sm:text-sm">
                        @yield('auth-subtitle', 'Sign in to continue')
                    </p>
                </div>

                <!-- Form -->
                <div class="px-6 sm:px-8 py-6 sm:py-8">
                    @yield('content')
                </div>
            </div>

            <!-- Small Auth Links -->
            <div class="mt-5 sm:mt-6 text-center
                        text-xs sm:text-sm
                        text-gray-600 dark:text-gray-400">
                @yield('footer-links')
            </div>

        </div>
    </main>

    <!-- FULL WIDTH FOOTER -->
    <footer class="w-full mt-auto
                   ">
        @include('partials.footer')
    </footer>

    @stack('scripts')
    
    <script>
    // Use global toggleTheme if available, otherwise define it
    if (typeof window.toggleTheme === 'undefined') {
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
    }

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
</body>
</html>
