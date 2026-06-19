{{-- Reusable add/edit post modal.
     Expects: $id, $formId, $action, $method, $heading, $prefix, $submit, $showHint --}}
<div id="{{ $id }}" class="fixed inset-0 z-50 hidden items-end sm:items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="$('#{{ $id }}').removeClass('flex').addClass('hidden')"></div>

    <div class="relative w-full sm:max-w-2xl
                bg-white dark:bg-gray-900
                rounded-t-3xl sm:rounded-3xl shadow-2xl
                max-h-[92vh] overflow-y-auto mx-0 sm:mx-4">

        {{-- Header --}}
        <div class="sticky top-0 z-10 bg-white/90 dark:bg-gray-900/90 backdrop-blur
                    flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-6 py-4">
            <h2 class="text-lg font-extrabold text-gradient">{{ $heading }}</h2>
            <button type="button"
                    onclick="$('#{{ $id }}').removeClass('flex').addClass('hidden')"
                    class="w-9 h-9 flex items-center justify-center rounded-full
                           text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-700 dark:hover:text-white transition">
                ✕
            </button>
        </div>

        <form id="{{ $formId }}" method="POST" action="{{ $action }}" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Title</label>
                <input type="text" name="title" id="{{ $prefix }}-title" required minlength="3" maxlength="255"
                       placeholder="Give your post a catchy title"
                       class="w-full rounded-2xl px-4 py-3 border border-gray-200 dark:border-gray-700
                              dark:bg-gray-800 dark:text-white text-sm
                              focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                <span id="{{ $prefix }}-title-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden block"></span>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Content</label>
                <textarea name="content" id="{{ $prefix }}-content" rows="5" required minlength="10" maxlength="5000"
                          placeholder="Write something worth reading..."
                          class="w-full rounded-2xl px-4 py-3 border border-gray-200 dark:border-gray-700
                                 dark:bg-gray-800 dark:text-white text-sm
                                 focus:ring-2 focus:ring-sky-500 focus:border-transparent transition resize-none"></textarea>
                <span id="{{ $prefix }}-content-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden block"></span>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                    Featured Image
                    @if($showHint)<span class="text-xs font-normal text-gray-400">(leave empty to keep current)</span>@endif
                </label>
                <label for="{{ $prefix }}-image"
                       class="flex flex-col items-center justify-center w-full h-44
                              border-2 border-dashed rounded-2xl cursor-pointer
                              border-gray-300 dark:border-gray-700
                              bg-gray-50 dark:bg-gray-800
                              hover:bg-sky-50 dark:hover:bg-gray-700 hover:border-sky-400 transition overflow-hidden">
                    <img id="{{ $prefix }}-image-preview" class="hidden w-full h-full object-cover" alt="Preview">
                    <div id="{{ $prefix }}-image-placeholder" class="flex flex-col items-center text-gray-400 pointer-events-none">
                        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm font-medium">Click to upload image</p>
                        <p class="text-xs text-gray-400">PNG, JPG, WEBP · max 2MB</p>
                    </div>
                    <input id="{{ $prefix }}-image" type="file" name="image" accept="image/*" class="hidden"
                           onchange="previewImage(event, '{{ $prefix }}-image-preview', '{{ $prefix }}-image-placeholder')">
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-1">
                <button type="button"
                        onclick="$('#{{ $id }}').removeClass('flex').addClass('hidden')"
                        class="px-5 py-2.5 rounded-full text-sm font-semibold
                               bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700
                               dark:text-white transition">
                    Cancel
                </button>
                <button type="submit" id="{{ $prefix }}-submit-btn"
                        class="px-6 py-2.5 rounded-full text-sm font-semibold text-white
                               bg-brand shadow-lg shadow-sky-500/30 hover:scale-[1.03] active:scale-95 transition
                               disabled:opacity-50 disabled:cursor-not-allowed">
                    {{ $submit }}
                </button>
            </div>
        </form>
    </div>
</div>
