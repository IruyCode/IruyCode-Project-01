@extends('layout.app')

@section('content')

    <!-- Bloco Devedores -->
    <div class="w-full px-4 py-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg" x-data="devedores">
        <div class="container mx-auto">
            <!-- Cabeçalho -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                        <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20" />
                        </svg>
                        Gestão de Devedores
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300">Lista de pessoas que devem valores</p>
                </div>

                <!-- Botão + modal Adicionar Devedor -->
                <div class=" flex items-center justify-center py-4" x-data="{ showAddDebtorModal: false }">
                    <button @click="showAddDebtorModal = true"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Adicionar Devedor
                    </button>

                    <!-- Modal: Adicionar Devedor -->
                    <div x-show="showAddDebtorModal" x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity"
                        @click.self="showAddDebtorModal = false">

                        <!-- Container Principal -->
                        <div x-show="showAddDebtorModal" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden"
                            @click.stop>

                            <!-- Cabeçalho -->
                            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 2v20" />
                                        </svg>
                                        <h2 class="text-xl font-bold text-white">Adicionar Devedor</h2>
                                    </div>
                                    <button @click="showAddDebtorModal = false"
                                        class="text-white hover:text-blue-100 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Corpo do Formulário -->
                            <div class="p-6">
                                <form action="{{ route('bank-manager.debtors.store') }}" method="POST" class="space-y-5">
                                    @csrf

                                    <!-- Nome -->
                                    <div>
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Nome do Devedor <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="name" id="name" required
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                dark:bg-gray-700 dark:text-white transition-all"
                                            placeholder="Nome do devedor">
                                    </div>

                                    <!-- Descrição -->
                                    <div>
                                        <label for="description"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Descrição
                                        </label>
                                        <textarea name="description" id="description" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                dark:bg-gray-700 dark:text-white transition-all"
                                            placeholder="Descrição da dívida (opcional)"></textarea>
                                    </div>

                                    <!-- Valor -->
                                    <div>
                                        <label for="amount"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Valor (€) <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 dark:text-gray-400">€</span>
                                            </div>
                                            <input type="number" name="amount" id="amount" min="0.01"
                                                step="0.01" required
                                                class="w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                    dark:bg-gray-700 dark:text-white transition-all"
                                                placeholder="0,00">
                                        </div>
                                    </div>

                                    <!-- Data de Vencimento -->
                                    <div>
                                        <label for="due_date"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Data de Vencimento <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" name="due_date" id="due_date" required
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                dark:bg-gray-700 dark:text-white transition-all">
                                    </div>

                                    <!-- Rodapé -->
                                    <div class="flex justify-end space-x-3 pt-4">
                                        <button type="button" @click="showAddDebtorModal = false"
                                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                                                text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 
                                                hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg 
                                                hover:from-blue-700 hover:to-blue-600 transition-colors shadow-md">
                                            Criar Devedor
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Tabela responsiva -->
            <div class="overflow-x-auto bg-white dark:bg-gray-700 rounded-lg shadow" x-data="{
                activeEditDebtorsId: null,
                activeDeleteDebtorsId: null,
                activeFinishDebtorsId: null,
                activeAdjustValueDebtorsId: null,
            
                openEditDebtorsModal(id) {
                    this.activeEditDebtorsId = id;
                },
                closeEditModal() {
                    this.activeEditDebtorsId = null;
                },
            
                openDeleteDebtorsModal(id) {
                    this.activeDeleteDebtorsId = id;
                },
                closeDeleteModal() {
                    this.activeDeleteDebtorsId = null;
                }, // <-- Esta vírgula estava faltando
            
                openFinishDebtorsModal(id) {
                    this.activeFinishDebtorsId = id;
                },
                closeFinishModal() {
                    this.activeFinishDebtorsId = null;
                },
            
                openAdjustValueDebtorsModal(id) {
                    this.activeAdjustValueDebtorsId = id;
                },
                closeAdjustValueModal() {
                    this.activeAdjustValueDebtorsId = null;
                }
                }">

                <div x-data="debtManager()">
                    <table class="w-full min-w-max">
                        <thead class="bg-gray-100 dark:bg-gray-600">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Nome</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Descrição</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Valor</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Vencimento</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach ($debtors as $debtor)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-500 flex items-center justify-center">
                                                <span
                                                    class="text-gray-700 dark:text-white font-medium">{{ strtoupper(substr($debtor->name, 0, 1)) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $debtor->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate">
                                            {{ $debtor->description }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $debtor->amount > 500 ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                            € {{ number_format($debtor->amount, 2, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($debtor->due_date)->format('d/m/Y') }}
                                        @if (\Carbon\Carbon::parse($debtor->due_date)->isPast() && !$debtor->is_paid)
                                            <span class="ml-2 text-xs text-red-500">(Atrasado)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($debtor->is_paid)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Pago
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Pendente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                        <div class="flex space-x-2">
                                            @if (!$debtor->is_paid)
                                                <!-- Botão Concluir -->
                                                <button @click="openFinishDebtorsModal({{ $debtor->id }})"
                                                    class="p-1.5 sm:p-1 text-green-600 hover:text-green-800 dark:text-green-100 dark:hover:text-green-300 rounded-full hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors"
                                                    title="Concluir devedor">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>

                                                <!-- Botão Editar -->
                                                <button @click="openEditDebtorsModal({{ $debtor->id }})"
                                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                            @endif

                                            <!-- Botão Excluir (sempre disponível, mesmo se estiver pago) -->
                                            <button @click="openDeleteDebtorsModal({{ $debtor->id }})"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>

                                            @if (!$debtor->is_paid)
                                                <!-- Botão Expandir -->
                                                <button @click="toggleInstallments({{ $debtor->id }})"
                                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 p-2 rounded-full hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                    <svg x-show="!isExpanded({{ $debtor->id }})" class="w-5 h-5"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                    <svg x-show="isExpanded({{ $debtor->id }})" class="w-5 h-5"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>

                                    </td>

                                </tr>

                                <tr>
                                    <td colspan="6" class="px-0 py-0">
                                        <!-- Seção expansível de histórico -->
                                        <div x-show="isExpanded({{ $debtor->id }})" x-collapse.duration.300ms
                                            class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                                                Histórico de Alterações
                                            </h4>

                                            @if ($debtor->edits->count() > 0)
                                                <div class="space-y-3 mb-4">
                                                    @foreach ($debtor->edits as $edit)
                                                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                                                            <div class="flex justify-between items-start mb-2">
                                                                <p class="text-sm text-gray-700 dark:text-gray-200">
                                                                    <strong>Motivo:</strong> {{ $edit->reason }}
                                                                </p>
                                                                <span
                                                                    class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap ml-2">
                                                                    {{ $edit->created_at->format('d/m/Y H:i') }}
                                                                </span>
                                                            </div>

                                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                                Valor: <span
                                                                    class="font-semibold text-red-500">€{{ number_format($edit->old_amount, 2, ',', '.') }}</span>
                                                                → <span
                                                                    class="font-semibold text-green-500">€{{ number_format($edit->new_amount, 2, ',', '.') }}</span>
                                                            </p>

                                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                                Vencimento:
                                                                <span>{{ \Carbon\Carbon::parse($edit->old_due_date)->format('d/m/Y') }}</span>
                                                                →
                                                                <span>{{ \Carbon\Carbon::parse($edit->new_due_date)->format('d/m/Y') }}</span>
                                                            </p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                                    Nenhuma alteração registrada.
                                                </p>
                                            @endif

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>

                @foreach ($debtors as $debtor)
                    <!-- Modal Editar Devedor -->
                    <div x-show="activeEditDebtorsId === {{ $debtor->id }}" x-transition
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-xl">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Editar Devedor</h2>
                                <button @click="closeEditModal()"
                                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <form method="POST" action="{{ route('bank-manager.debtors.edit', $debtor->id) }}">
                                @csrf

                                <div class="space-y-4">
                                    <!-- Nome -->
                                    <div>
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Nome
                                        </label>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name', $debtor->name) }}"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
                               focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                               dark:bg-gray-700 dark:text-white"
                                            required>
                                    </div>

                                    <!-- Descrição -->
                                    <div>
                                        <label for="description"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Descrição
                                        </label>
                                        <textarea id="description" name="description" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
                               focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                               dark:bg-gray-700 dark:text-white">{{ old('description', $debtor->description) }}</textarea>
                                    </div>

                                    <!-- Valor -->
                                    <div>
                                        <label for="amount"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Valor (€)
                                        </label>
                                        <input type="number" id="amount" name="amount" step="0.01"
                                            min="0" value="{{ old('amount', $debtor->amount) }}"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
                               focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                               dark:bg-gray-700 dark:text-white"
                                            required>
                                    </div>

                                    <!-- Data de Vencimento -->
                                    <div>
                                        <label for="due_date"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Data de Vencimento
                                        </label>
                                        <input type="date" id="due_date" name="due_date"
                                            value="{{ old('due_date', $debtor->due_date->format('Y-m-d')) }}"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
                               focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                               dark:bg-gray-700 dark:text-white"
                                            required>
                                    </div>

                                    <!-- Motivo da alteração -->
                                    <div>
                                        <label for="reason"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Motivo da Alteração <span class="text-gray-500 text-xs">(obrigatório apenas se
                                                valor ou data forem alterados)</span>
                                        </label>
                                        <textarea id="reason" name="reason" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
                               focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                               dark:bg-gray-700 dark:text-white"
                                            placeholder="Descreva o motivo da alteração (ex: correção de valor, prorrogação de prazo)">{{ old('reason') }}</textarea>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" @click="closeEditModal()"
                                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
                           text-sm font-medium text-gray-700 dark:text-gray-300 
                           bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 rounded-md shadow-sm text-sm font-medium text-white 
                           bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Salvar Alterações
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- Modal de Confirmação de Exclusão -->
                    <div x-show="activeDeleteDebtorsId === {{ $debtor->id }}" x-transition
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                        style="display: none" x-cloak>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-md">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                Tem certeza que deseja excluir este devedor?
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                                Esta ação não poderá ser desfeita. O devedor
                                <strong>{{ $debtor->name }}</strong> será permanentemente removido.
                            </p>

                            <div class="flex justify-end space-x-3">
                                <button @click="closeDeleteModal()"
                                    class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">
                                    Cancelar
                                </button>

                                <form method="POST" action="{{ route('bank-manager.debtors.destroy', $debtor->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 transition">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Concluir Meta -->
                    <div x-show="activeFinishDebtorsId === {{ $debtor->id }}" x-transition
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-md">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                                    Concluir Meta de {{ $debtor->name }}
                                </h2>
                                <button @click="closeFinishModal()"
                                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                                Tem certeza que deseja concluir a meta deste devedor? Esta ação marcará a dívida como
                                paga e não poderá ser desfeita.
                            </p>

                            <div class="flex justify-end space-x-3">
                                <button @click="closeFinishModal()"
                                    class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">
                                    Cancelar
                                </button>
                                <form method="POST" action="{{ route('bank-manager.debtors.conclude', $debtor->id) }}">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 transition">
                                        Confirmar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Sem registros -->
            @if ($debtors->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Nenhum devedor
                        encontrado</h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">Adicione um novo devedor clicando no botão
                        acima.</p>
                </div>
            @endif

        </div>
    </div>
    
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('debtManager', () => ({
                expandedDebts: [],
                installmentsToPay: 1,

                init() {},

                toggleInstallments(debtorId) {
                    if (this.expandedDebts.includes(debtorId)) {
                        this.expandedDebts = this.expandedDebts.filter(id => id !== debtorId);
                    } else {
                        this.expandedDebts.push(debtorId);
                    }
                },

                isExpanded(debtorId) {
                    return this.expandedDebts.includes(debtorId);
                },
            }));
        });
    </script>
@endpush