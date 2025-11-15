<nav id="main-navbar"
    class="fixed w-full z-50 bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-700 dark:to-purple-800 shadow-md transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="/public" class="text-xl font-bold text-white">IruyCode</a>

            <!-- Menu Desktop -->
            <div class="hidden md:flex space-x-6">
                <a href="#" class="text-white hover:text-gray-200 transition">Início</a>
                <a href="#" class="text-white hover:text-gray-200 transition">Projetos</a>
                <a href="#" class="text-white hover:text-gray-200 transition">Sobre</a>
                <a href="#" class="text-white hover:text-gray-200 transition">Contato</a>
            </div>

            <!-- Controles -->
            <div class="flex items-center space-x-3">
                <!-- Modo Claro/Escuro -->
                <button id="theme-toggle" class="text-white focus:outline-none">
                    <svg id="icon-sun" class="hidden w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="5" />
                        <path
                            d="M12 1v2m0 18v2M21 12h2M1 12H3m16.95 6.95l-1.41-1.41M6.46 6.46L5.05 5.05m13.9 0l-1.41 1.41M6.46 17.54l-1.41 1.41" />
                    </svg>
                    <svg id="icon-moon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
                    </svg>
                </button>

                <!-- Menu Mobile -->
                <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Mobile -->
    <div id="mobile-menu" class="hidden bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-4 space-y-3 md:hidden">
        <a href="#" class="block text-white hover:text-gray-200 transition">Início</a>
        <a href="#" class="block text-white hover:text-gray-200 transition">Projetos</a>
        <a href="#" class="block text-white hover:text-gray-200 transition">Sobre</a>
        <a href="#" class="block text-white hover:text-gray-200 transition">Contato</a>
    </div>
</nav>
