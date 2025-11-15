<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'IruyCode')</title>

    <!-- Tema Bootstrap 5 escuro -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/datatables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    <!-- DataTables Core CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js + plugins --}}
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" onload="
                    document.addEventListener('alpine:init', () => {
                        // Store global para controlar modais
                        Alpine.store('modal', {
                            current: null,
                            open(name) { this.current = name },
                            close() { this.current = null },
                            is(name) { return this.current === name }
                        });
                    });
                "></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">

    <!-- Header global -->
    @include('layout.partials.header')

    <!-- Conteúdo principal -->
    <main class="pt-20 min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Mensagens globais (sucesso, erro etc.) --}}
            @include('layout.partials.alerts')

            {{-- Conteúdo dinâmico --}}
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    @include('layout.partials.footer')

    {{-- Scripts adicionais empilhados por módulos --}}
    @stack('scripts')
</body>

</html>
