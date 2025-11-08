<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'IruyCode')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">

    {{-- Header --}}
    @include('layout.partials.header')

    {{-- Conteúdo principal --}}
    <main class="pt-20 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Cada bloco de conteúdo ocupará uma célula da grid --}}
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    @include('layout.partials.footer')

    {{-- Scripts adicionais --}}
    @stack('scripts')
</body>

</html>
