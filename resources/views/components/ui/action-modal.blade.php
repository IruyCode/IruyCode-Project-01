<div x-show="{{ $show ?? 'open' }}" x-transition.opacity x-cloak @click.self="{{ $show ?? 'open' }} = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">

    <div x-transition class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md overflow-hidden"
        @keydown.escape.window="{{ $show ?? 'open' }} = false">

        <!-- Cabeçalho -->
        <div
            class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700
            {{ $headerClass ?? 'bg-gradient-to-r from-red-600 to-red-500 text-white' }}">
            <h2 class="text-lg font-semibold">
                {{ $title ?? 'Confirmação' }}
            </h2>
            <button type="button" @click="{{ $show ?? 'open' }} = false" class="hover:opacity-80">✕</button>
        </div>

        <!-- Conteúdo dinâmico -->
        <div class="p-6">
            {{ $slot }}
        </div>

        <!-- Rodapé opcional -->
        @if (isset($footer))
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
