@extends('layout.app')

@section('content')
    <div x-data="{ showCreate: false }" class="w-full px-4 py-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">

        <div class="container mx-auto">
            <!-- Cabeçalho -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                        Gestão de Devedoressss
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300">Lista de pessoas que devem valores</p>
                </div>

                <!-- Botão abre modal -->
                <button @click="showCreate = true"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Novo Devedor
                </button>
            </div>

            <!-- Moda-create -->
            @include('bankmanager::debtors.partials.modal-create')

            <!-- Tabela de Devedores -->
            @include('bankmanager::debtors.partials.debtors-table')

        </div>
    </div>
@endsection
