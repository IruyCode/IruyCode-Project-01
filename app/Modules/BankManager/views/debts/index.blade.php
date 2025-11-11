@extends('bankmanager::dashboard')

@section('content-component')
    <div x-data="debtManager()" class="w-full px-4 py-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <div class="container mx-auto">

            <!-- Cabeçalho e Estatísticas -->
            <header class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                        Gestão de Dívidas
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300">Controle suas dívidas e parcelas pendentes</p>
                </div>

                <div class="flex flex-wrap gap-4">
                    <div class="bg-blue-50 dark:bg-blue-900/30 px-4 py-2 rounded-lg">
                        <p class="text-sm text-blue-800 dark:text-blue-200">Total em Dívidas</p>
                        <p class="text-xl font-bold text-blue-600 dark:text-blue-300">€
                            {{ number_format($totalDebt, 2) }}</p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/30 px-4 py-2 rounded-lg">
                        <p class="text-sm text-green-800 dark:text-green-200">Pago</p>
                        <p class="text-xl font-bold text-green-600 dark:text-green-300">€
                            {{ number_format($paidAmount, 2) }}</p>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/30 px-4 py-2 rounded-lg">
                        <p class="text-sm text-red-800 dark:text-red-200">Pendente</p>
                        <p class="text-xl font-bold text-red-600 dark:text-red-300">€
                            {{ number_format($pendingAmount, 2) }}</p>
                    </div>
                </div>
            </header>

            <!-- Botão e Modal -->
            <div class="flex justify-end mb-6" x-data="{ openCreate: false }">
                <button @click="openCreate = true"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <x-icon name="plus" class="w-5 h-5 mr-2" />
                    Adicionar Dívida
                </button>

                <!-- Modal de criação -->
                @include('bankmanager::debts.partials.modals.modal-create')
            </div>

            @include('bankmanager::debts.partials.debts-list')

        </div>
    </div>

    @include('bankmanager::debts._scripts')
@endsection
