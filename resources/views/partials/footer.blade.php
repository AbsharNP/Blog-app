<div class="border-t border-gray-200/60 dark:border-gray-800/60
            bg-white/60 dark:bg-gray-900/60 backdrop-blur-xl mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8
                flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-lg bg-brand flex items-center justify-center
                         text-white font-black text-sm shadow">B</span>
            <span class="font-extrabold text-gradient">BlogApp</span>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
            © {{ date('Y') }} BlogApp. Crafted with <span class="text-cyan-500">♥</span> for storytellers.
        </p>
        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('posts.index') }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition">Explore</a>
            <span class="text-gray-300 dark:text-gray-700">·</span>
            <a href="#" class="hover:text-sky-600 dark:hover:text-sky-400 transition">Privacy</a>
            <span class="text-gray-300 dark:text-gray-700">·</span>
            <a href="#" class="hover:text-sky-600 dark:hover:text-sky-400 transition">Terms</a>
        </div>
    </div>
</div>
