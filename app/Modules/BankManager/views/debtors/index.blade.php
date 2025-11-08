<h1>teste</h1>@extends('layout.app')

@section('content')
    <div class="w-full px-4 py-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <div class="container mx-auto">

            <!-- Cabeçalho -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                        {{-- <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20" />
                        </svg> --}}
                        Gestão de Devedores
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300">Lista de pessoas que devem valores</p>
                </div>

                {{-- @include('bankmanager::debtors._partials.add-modal') --}}
            </div>

            <!-- Tabela -->
            {{-- @include('bankmanager::debtors._partials.debtors-table') --}}

            <!-- Caso não haja registros -->
            @if ($debtors->isEmpty())
                <div class="text-center py-12">
                    {{-- <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg> --}}
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Nenhum devedor encontrado</h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">Adicione um novo devedor clicando no botão acima.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

{{-- @push('scripts')
    @include('bankmanager::debtors._scripts')
@endpush --}}
