<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
            crossorigin="anonymous"></script>

    <title>@yield('title', 'Blog')</title>

    @vite('resources/css/app.css')

    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = theme === 'dark' || (!theme && prefersDark);
            document.documentElement.classList.toggle('dark', isDark);
        })();
    </script>
</head>

<body class="relative min-h-screen flex flex-col
             bg-gray-50 text-gray-800
             dark:bg-gray-950 dark:text-gray-100
             transition-colors overflow-x-hidden">

    <!-- Decorative gradient blobs -->
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-32 -left-24 w-96 h-96 rounded-full
                    bg-sky-400/30 dark:bg-sky-600/20 blur-3xl animate-blob"></div>
        <div class="absolute top-1/3 -right-24 w-96 h-96 rounded-full
                    bg-cyan-400/30 dark:bg-cyan-600/20 blur-3xl animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 rounded-full
                    bg-cyan-400/25 dark:bg-cyan-600/20 blur-3xl animate-blob animation-delay-4000"></div>
    </div>

    <!-- NAVBAR -->
    @include('partials.navbar')

    <!-- MAIN CONTENT -->
    <main class="flex-1 w-full max-w-7xl mx-auto
                 px-4 sm:px-6 lg:px-8
                 py-6 sm:py-8 lg:py-10">
        <div class="w-full min-w-0">
            @yield('content')
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="mt-auto w-full">
        @include('partials.footer')
    </footer>

    @stack('scripts')

    <script>
    // Logout form AJAX handling
    $(document).ready(function() {
        $('#logout-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function() { window.location.href = '/'; },
                error: function() { form.off('submit').submit(); }
            });
        });
    });
    </script>
</body>
</html>
