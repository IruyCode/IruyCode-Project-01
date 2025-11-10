<div class="overflow-x-auto bg-white dark:bg-gray-700 rounded-lg shadow" x-data="debtManager()">
    <table class="w-full min-w-max">
        <thead class="bg-gray-100 dark:bg-gray-600">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-700 dark:text-gray-300">Nome</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-700 dark:text-gray-300">Descrição
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-700 dark:text-gray-300">Valor</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-700 dark:text-gray-300">Vencimento
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-700 dark:text-gray-300">Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-700 dark:text-gray-300">Ações</th>
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
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>

                                <!-- Botão Editar -->
                                <button @click="openEditDebtorsModal({{ $debtor->id }})"
                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            @endif

                            <!-- Botão Excluir (sempre disponível, mesmo se estiver pago) -->
                            <button @click="openDeleteDebtorsModal({{ $debtor->id }})"
                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>

                            @if (!$debtor->is_paid)
                                <!-- Botão Expandir -->
                                <button @click="toggleInstallments({{ $debtor->id }})"
                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 p-2 rounded-full hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <svg x-show="!isExpanded({{ $debtor->id }})" class="w-5 h-5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <svg x-show="isExpanded({{ $debtor->id }})" class="w-5 h-5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 15l7-7 7 7" />
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


{{-- <!-- Modais -->
<x-core::form-modal id="editDebtorModal" title="Editar Devedor"
    action="{{ route('bank-manager.debtors.update', $debtor->id) }}">
    @include('bankmanager::debtors.partials.form-fields', ['debtor' => $debtor])
</x-core::form-modal>

<x-core::confirm-modal id="deleteDebtorModal" title="Excluir Devedor"
    message="Tem certeza que deseja excluir o devedor {{ $debtor->name }}?"
    action="{{ route('bank-manager.debtors.destroy', $debtor->id) }}" method="DELETE" />

<x-core::confirm-modal id="finishDebtorModal" title="Concluir Dívida" message="Deseja marcar esta dívida como paga?"
    action="{{ route('bank-manager.debtors.conclude', $debtor->id) }}" color="green" /> --}}
