<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- jQuery (only if really needed) -->
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
            
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>

<body class="min-h-screen flex flex-col
             bg-gray-50 text-gray-800
             dark:bg-gray-950 dark:text-gray-200
             transition-colors">

    <!-- NAVBAR -->
    @include('partials.navbar')

    <!-- MAIN CONTENT -->
    <main class="flex-1 w-full
                 max-w-7xl mx-auto
                 px-4 sm:px-6 lg:px-8
                 py-6 sm:py-8 lg:py-10">

        <!-- Prevent layout break on small screens -->
        <div class="w-full min-w-0">
            @yield('content')
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="mt-auto w-full
                   border-t border-gray-200 dark:border-gray-800
                   bg-white/80 dark:bg-gray-900/80 backdrop-blur">
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
                success: function() {
                    window.location.href = '/';
                },
                error: function() {
                    // Fallback to normal form submission
                    form.off('submit').submit();
                }
            });
        });
    });
    </script>
</body>
</html>
