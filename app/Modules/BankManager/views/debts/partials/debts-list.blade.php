<!-- Lista de Dívidas -->
<div class="space-y-6" x-data="debtManager()">

    @forelse ($debts as $debt)
        @php
            $paidInstallments = $debt->installmentsList->whereNotNull('paid_at');
            $pendingInstallments = $debt->installmentsList->whereNull('paid_at');
            $amountPaid = $paidInstallments->sum('amount');
            $progress = $debt->total_amount > 0 ? ($amountPaid / $debt->total_amount) * 100 : 0;
            $nextInstallment = $pendingInstallments->first();
        @endphp

        <!-- Card da Dívida -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-200 hover:shadow-lg"
            x-data="{
                activeEditDebtId: null,
                activeDeleteDebtId: null,
                openEditDebtModal(id) { this.activeEditDebtId = id },
                closeEditDebtModal() { this.activeEditDebtId = null },
                openDeleteDebtModal(id) { this.activeDeleteDebtId = id },
                closeDeleteDebtModal() { this.activeDeleteDebtId = null }
            }">

            <!-- Cabeçalho -->
            <div class="px-6 py-4 border-b dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <!-- Identificação -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white truncate">
                                {{ $debt->name }}
                            </h3>

                            <span
                                class="text-xs px-2 py-1 rounded-full 
                                {{ $progress >= 100
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                    : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                {{ $progress >= 100 ? 'Quitado' : 'Em andamento' }}
                            </span>
                        </div>

                        @if ($debt->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 truncate">
                                {{ $debt->description }}
                            </p>
                        @endif
                    </div>

                    <!-- Valor e Ações -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">

                        <!-- Total -->
                        <div class="text-right">
                            <span class="text-lg font-bold text-gray-800 dark:text-white">
                                € {{ number_format($debt->total_amount, 2) }}
                            </span>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $paidInstallments->count() }}/{{ $debt->installmentsList->count() }} parcelas
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center gap-2">

                            <!-- Editar -->
                            <button @click="openEditDebtModal({{ $debt->id }})"
                                class="p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 
                                       rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
                                title="Editar dívida">
                                <x-icon name="edit" />
                            </button>

                            <!-- Excluir -->
                            <button @click="openDeleteDebtModal({{ $debt->id }})"
                                class="p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 
                                       rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                title="Excluir dívida">
                                <x-icon name="trash" />
                            </button>

                            <!-- Expandir -->
                            <button @click="toggleInstallments({{ $debt->id }})"
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 
                                       p-2 rounded-full hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200"
                                title="Ver parcelas">
                                <svg x-show="!isExpanded({{ $debt->id }})" class="w-5 h-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                                <svg x-show="isExpanded({{ $debt->id }})" class="w-5 h-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Barra de Progresso -->
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                        <span>Progresso</span>
                        <span>{{ number_format($progress, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="mt-1 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>€ {{ number_format($amountPaid, 2) }} pago</span>
                        <span>€ {{ number_format($debt->total_amount - $amountPaid, 2) }} restante</span>
                    </div>
                </div>
            </div>

            <!-- Modais -->
            @include('bankmanager::debts.partials.modals.modal-edit', ['debt' => $debt])
            @include('bankmanager::debts.partials.modals.modal-delete', ['debt' => $debt])

            <!-- Detalhes das Parcelas -->
            @include('bankmanager::debts.partials.installment-details', [
                'debt' => $debt,
                'nextInstallment' => $nextInstallment,
                'pendingInstallments' => $pendingInstallments,
            ])

        </div>
    @empty
        <!-- Nenhum registro -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">
                Nenhuma dívida registrada
            </h3>
            <p class="mt-1 text-gray-500 dark:text-gray-400">Comece adicionando sua primeira dívida.</p>
        </div>
    @endforelse

</div>
