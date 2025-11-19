@extends('bankmanager::app')

@section('content-component')
    <!-- Bloco Ações -->
    <div class="w-full p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg" x-data="investimentos">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                Gestão de Investimentos
            </h2>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">

                <div x-data="{ showAddInvestmentModal: false }">
                    <button @click="showAddInvestmentModal = true"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Novo Investimento
                    </button>

                    <!-- Modal de Adicionar Investimento -->
                    @include('bankmanager::investments.partials.modals.modal-create-investment')

                </div>

            </div>

            <!-- Tabela responsiva -->
            <div class="overflow-x-auto bg-white dark:bg-gray-700 rounded-lg shadow" x-data="{
                activeEditInvestmentsId: null,
                activeDeleteInvestmentsId: null,
            
                openEditInvestmentsModal(id) {
                    this.activeEditInvestmentsId = id;
                },
                closeEditModal() {
                    this.activeEditInvestmentsId = null;
                },
            
                openDeleteInvestmentsModal(id) {
                    this.activeDeleteInvestmentsId = id;
                },
                closeDeleteModal() {
                    this.activeDeleteInvestmentsId = null;
                }
            
            }">

                <div x-data="InvestmentManager()">
                    <table class="w-full min-w-max">
                        <thead class="bg-gray-100 dark:bg-gray-600">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Nome
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Plataforma
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Valor Atual
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Variação
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach ($investments as $investment)
                                @php
                                    // Determina a cor e ícone com base no tipo de investimento
                                    $iconClasses = [
                                        'Renda Fixa' => [
                                            'bg' => 'bg-blue-100 dark:bg-blue-900',
                                            'text' => 'text-blue-600 dark:text-blue-300',
                                        ],
                                        'Ações' => [
                                            'bg' => 'bg-green-100 dark:bg-green-900',
                                            'text' => 'text-green-600 dark:text-green-300',
                                        ],
                                        'Fundos Imobiliários' => [
                                            'bg' => 'bg-purple-100 dark:bg-purple-900',
                                            'text' => 'text-purple-600 dark:text-purple-300',
                                        ],
                                        'default' => [
                                            'bg' => 'bg-gray-100 dark:bg-gray-900',
                                            'text' => 'text-gray-600 dark:text-gray-300',
                                        ],
                                    ];

                                    $type = $investment->type ?? 'default';
                                    $classes = $iconClasses[$type] ?? $iconClasses['default'];

                                    // Calcula a variação (simplificado - você deve implementar sua lógica real)
                                    $variation = $investment->current_amount - $investment->initial_amount;
                                    $percentage = ($variation / $investment->initial_amount) * 100;
                                    $isPositive = $variation >= 0;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 rounded-full {{ $classes['bg'] }} flex items-center justify-center">
                                                <svg class="w-5 h-5 {{ $classes['text'] }}" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                </svg>
                                            </div>

                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $investment->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $investment->type }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate">
                                            {{ $investment->platform }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate">
                                            € {{ number_format($investment->current_amount, 2, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div
                                            class="col-span-2 flex items-center {{ $isPositive ? 'text-green-500' : 'text-red-500' }}">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ $isPositive ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                            </svg>
                                            {{ number_format(abs($percentage), 2) }}% (30d)
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="col-span-2 flex justify-end space-x-2">

                                            <!-- BOTÃO EDITAR -->
                                            <button @click="openEditInvestmentsModal({{ $investment->id }})"
                                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <!-- BOTÃO EXCLUIR -->
                                            <button @click="openDeleteInvestmentsModal({{ $investment->id }})"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>

                                            <!-- Botão Expandir -->
                                            <button @click="toggleInstallments({{ $investment->id }})"
                                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 p-2 rounded-full hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                <svg x-show="!isExpanded({{ $investment->id }})" class="w-5 h-5"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                                <svg x-show="isExpanded({{ $investment->id }})" class="w-5 h-5"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 15l7-7 7 7" />
                                                </svg>
                                            </button>

                                        </div>
                                    </td>

                                </tr>

                                <tr>
                                    <td colspan="6" class="px-0 py-0">
                                        <!-- Seção expansível -->
                                        <div x-show="isExpanded({{ $investment->id }})" x-collapse.duration.300ms
                                            class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-t border-gray-200 dark:border-gray-600">

                                            <!-- Aporte ou Retirada -->
                                            <div class="mb-6">
                                                <h5 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                    Aporte / Retirada
                                                </h5>

                                                <form method="POST"
                                                    action="{{ route('bank-manager.investments.applyCashflow', $investment->id) }}">
                                                    @csrf

                                                    <div class="grid grid-cols-2 gap-4">

                                                        <!-- Valor -->
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Valor (€)
                                                            </label>
                                                            <input type="number" step="0.01" min="0.01"
                                                                name="valor" required
                                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md 
                                                                dark:bg-gray-700 dark:text-white">
                                                        </div>

                                                        <!-- Tipo -->
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Tipo
                                                            </label>
                                                            <select name="tipo" required
                                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md 
                                                                dark:bg-gray-700 dark:text-white">
                                                                <option value="aporte">Aporte</option>
                                                                <option value="retirada">Retirada</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- SELECIONAR A CONTA -->
                                                    <div class="mt-4">
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                            Conta da Operação
                                                        </label>

                                                        <select name="account_balance_id" required
                                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md 
                                                            dark:bg-gray-700 dark:text-white">

                                                            @foreach ($accountBalance as $account)
                                                                <option value="{{ $account->id }}">
                                                                    {{ $account->account_name }}
                                                                    ({{ $account->bank_name }}) —
                                                                    Saldo:
                                                                    €{{ number_format($account->current_balance, 2, ',', '.') }}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    </div>

                                                    <div class="mt-4 flex justify-end">
                                                        <button type="submit"
                                                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                                            Salvar movimentação
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>

                                            <hr class="border-t border-gray-300 dark:border-gray-600 my-6">

                                            <!--Atualização de mercado -->
                                            <div>
                                                <h5 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                    Atualização de Valor de Mercado</h5>
                                                <form method="POST"
                                                    action="{{ route('bank-manager.investments.applyMarketUpdate', $investment->id) }}">
                                                    @csrf
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label for="valor_mercado"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Novo
                                                                Valor Atual (€)</label>
                                                            <input type="number" step="0.01" min="0"
                                                                name="valor_mercado"
                                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"
                                                                required>
                                                        </div>
                                                        <div>
                                                            <label for="reference_date"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data
                                                                de Referência</label>
                                                            <input type="date" name="reference_date"
                                                                value="{{ now()->format('Y-m-d') }}"
                                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 flex justify-end">
                                                        <button type="submit"
                                                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Atualizar
                                                            valor</button>
                                                    </div>
                                                </form>
                                            </div>

                                            @if ($investment->transactions->count())
                                                <div class="mt-6">
                                                    <h5
                                                        class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                        Últimas movimentações</h5>
                                                    <ul class="space-y-2 text-sm">
                                                        @foreach ($investment->transactions->sortByDesc('performed_at')->take(5) as $transaction)
                                                            <li
                                                                class="bg-white dark:bg-gray-800 p-3 rounded-md shadow-sm flex justify-between items-center">
                                                                <span>
                                                                    <strong
                                                                        class="capitalize text-blue-600 dark:text-blue-300">
                                                                        {{ $transaction->type }}
                                                                    </strong>
                                                                    de
                                                                    €{{ number_format($transaction->amount, 2, ',', '.') }}
                                                                    @if ($transaction->description)
                                                                        — {{ $transaction->description }}
                                                                    @endif
                                                                </span>
                                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                    {{ $transaction->performed_at->format('d/m/Y H:i') }}
                                                                </span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif


                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                @foreach ($investments as $investment)
                    <!-- Modal Editar Investimento -->
                    @include('bankmanager::investments.partials.modals.modal-edit-investment')

                    <!-- Modal de Confirmação de Exclusão -->
                    @include('bankmanager::investments.partials.modals.modal-delete-investment')
                @endforeach

            </div>

            <!-- Sem registros -->
            @if ($investments->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">
                        Nenhum investimento encontrado
                    </h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">
                        Adicione um novo investimento clicando no botão acima.
                    </p>
                </div>
            @endif


        </div>

    </div>

    @include('bankmanager::investments._scripts')
@endsection
