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
            document.documentElement.classList.toggle('dark', theme === 'dark' || (!theme && prefersDark));
        })();
    </script>
</head>

<body class="relative min-h-screen flex flex-col overflow-hidden
             bg-gray-50 dark:bg-gray-950 transition-colors">

    <!-- Decorative gradient blobs -->
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-24 -left-24 w-[28rem] h-[28rem] rounded-full
                    bg-sky-400/40 dark:bg-sky-600/25 blur-3xl animate-blob"></div>
        <div class="absolute top-1/4 -right-24 w-[28rem] h-[28rem] rounded-full
                    bg-cyan-400/40 dark:bg-cyan-600/25 blur-3xl animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-24 left-1/4 w-[28rem] h-[28rem] rounded-full
                    bg-cyan-400/35 dark:bg-cyan-600/25 blur-3xl animate-blob animation-delay-4000"></div>
    </div>

    <!-- Dark Mode Toggle -->
    <div class="absolute top-4 right-4 z-20">
        <button onclick="toggleTheme()"
                id="theme-toggle"
                class="w-10 h-10 flex items-center justify-center rounded-xl
                       bg-white/70 dark:bg-gray-900/70 backdrop-blur
                       text-gray-600 dark:text-gray-300 shadow
                       hover:scale-105 transition"
                aria-label="Toggle theme">
            <span id="theme-icon">🌙</span>
        </button>
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-10">
        <div class="w-full max-w-md">

            <!-- Brand -->
            <div class="flex items-center justify-center gap-2 mb-6">
                <span class="w-11 h-11 rounded-2xl bg-brand flex items-center justify-center
                             text-white font-black text-xl shadow-lg shadow-sky-500/30">B</span>
                <span class="text-2xl font-extrabold text-gradient">BlogApp</span>
            </div>

            <!-- Auth Card -->
            <div class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl
                        rounded-3xl shadow-2xl shadow-sky-500/10
                        border border-white/60 dark:border-gray-800
                        overflow-hidden">

                <!-- Header -->
                <div class="bg-brand px-8 py-8 text-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-25
                                bg-[radial-gradient(circle_at_25%_25%,white,transparent_45%),radial-gradient(circle_at_75%_70%,white,transparent_40%)]"></div>
                    <div class="relative">
                        <h1 class="text-2xl font-extrabold text-white">
                            @yield('auth-title', 'Welcome Back')
                        </h1>
                        <p class="text-sky-100 mt-1.5 text-sm">
                            @yield('auth-subtitle', 'Sign in to continue')
                        </p>
                    </div>
                </div>

                <!-- Form -->
                <div class="px-7 sm:px-8 py-7">
                    @yield('content')
                </div>
            </div>

            <!-- Footer links -->
            <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                @yield('footer-links')
            </div>
        </div>
    </main>

    @stack('scripts')

    <script>
    if (typeof window.toggleTheme === 'undefined') {
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
    }
    (function() {
        const theme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const isDark = theme === 'dark' || (!theme && prefersDark);
        document.querySelectorAll('#theme-icon').forEach(icon => { if (icon) icon.textContent = isDark ? '☀️' : '🌙'; });
    })();
    </script>
</body>
</html>
