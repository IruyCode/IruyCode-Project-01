<!-- Footer de navegação estilo app (só mobile) -->
<div class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-md flex items-center h-16 z-50 md:hidden px-0">
    <a href="#" @click.prevent="bloco = 'metas'"
        :class="bloco === 'metas' ? 'text-blue-600 font-bold' : 'text-gray-700'"
        class="flex flex-col items-center justify-center flex-1 text-xs py-1">
        <!-- Ícone de alvo/metas -->
        <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
            <circle cx="12" cy="12" r="6" stroke="currentColor" stroke-width="2" fill="none" />
            <circle cx="12" cy="12" r="2" stroke="currentColor" stroke-width="2" fill="none" />
        </svg>
        Metas
    </a>
    <a href="#" @click.prevent="bloco = 'dividas'"
        :class="bloco === 'dividas' ? 'text-blue-600 font-bold' : 'text-gray-700'"
        class="flex flex-col items-center justify-center flex-1 text-xs py-1">
        <!-- Ícone de dívidas (cifrão) -->
        <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3" />
            <path d="M12 2v20" />
        </svg>
        Dívidas
    </a>
    <!-- Botão central Dashboard -->
    <div class="relative flex-1 flex flex-col items-center justify-center">
        <a href="{{ route('bank-manager.index') }}" @click.prevent="bloco = 'dashboard'"
            :class="bloco === 'dashboard' ? 'text-blue-600 font-bold' : 'text-gray-700'"
            class="flex flex-col items-center justify-center flex-1 text-xs py-1">
            <div :class="bloco === 'dashboard' ?
                'bg-white rounded-full w-14 h-14 flex items-center justify-center border-4 border-blue-600 shadow-lg -mt-4' :
                'mb-0.5'"
                class="flex items-center justify-center">
                <svg :class="bloco === 'dashboard' ? 'text-blue-600 w-8 h-8' : 'w-6 h-6'" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="4" y="4" width="16" height="16" rx="4" stroke="currentColor"
                        stroke-width="2" fill="none" />
                </svg>
            </div>
            <span :class="bloco === 'dashboard' ? 'mt-1' : ''">Dashboard</span>
        </a>
    </div>
    <a href="#" @click.prevent="bloco = 'devedores'"
        :class="bloco === 'devedores' ? 'text-blue-600 font-bold' : 'text-gray-700'"
        class="flex flex-col items-center justify-center flex-1 text-xs py-1">
        <!-- Ícone de devedores (pessoas) -->
        <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="9" cy="7" r="4" />
            <path d="M17 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
            <path d="M2 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
            <path d="M17 21v-2a4 4 0 0 0-3-3.87" />
        </svg>
        Devedores
    </a>
    <a href="#" @click.prevent="bloco = 'investimentos'"
        :class="bloco === 'investimentos' ? 'text-blue-600 font-bold' : 'text-gray-700'"
        class="flex flex-col items-center justify-center flex-1 text-xs py-1">
        <!-- Ícone de investimentos (engrenagem) -->
        <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="3" />
            <path
                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09A1.65 1.65 0 0 0 9 3.09V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h.09a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
        </svg>
        Investimentos
    </a>
</div>
<!-- ./Footer de navegação -->
