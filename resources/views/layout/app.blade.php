<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IruyCode')</title>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">
    <!-- Header fixo -->
    @include('layout.partials.header')

    <!-- Espaço principal com padding top suficiente pro header fixo -->
    <main class="flex-grow pt-20 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Footer fixo no fim -->
    @include('layout.partials.footer')
</body>

</html>
