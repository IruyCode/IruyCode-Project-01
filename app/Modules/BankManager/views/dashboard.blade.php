@extends('layout.app')

@section('content')

    <!-- Div principal -->
    <div class="min-h-screen flex flex-col">
       
        @include('bankmanager::partials.header-dashboard')   

        <!-- Conteúdo principal -->
        <div class="flex-1 flex flex-col min-h-screen px-[2%] mt-4">

            @yield('content-component')

        </div>
        <!-- Fim do conteúdo principal -->

        @include('bankmanager::partials.footer-dashboard')

    </div>
    <!-- Fim do Div principal -->
@endsection
