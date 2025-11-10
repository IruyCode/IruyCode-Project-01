<div x-show="{{ $show ?? 'open' }}" x-transition.opacity x-cloak @click.self="{{ $show ?? 'open' }} = false"
    @keydown.escape.window="{{ $show ?? 'open' }} = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">

    <div x-transition
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-{{ $width ?? '2xl' }} overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20" />
                    </svg>
                    <h2 class="text-xl font-bold text-white">
                        {{ $title ?? 'Modal' }}
                    </h2>
                </div>

                <button type="button" @click="{{ $show ?? 'open' }} = false"
                    class="text-white hover:text-blue-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Conteúdo -->
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>
