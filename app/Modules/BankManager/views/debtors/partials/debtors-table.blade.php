{{-- <table class="w-full min-w-max">
    <thead class="bg-gray-100 dark:bg-gray-600">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Nome</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Descrição</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Valor</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Vencimento
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Status</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Ações</th>
        </tr>
    </thead>

    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
        @foreach ($debtors as $debtor)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                <td class="px-6 py-4 text-gray-900 dark:text-white">{{ $debtor->name }}</td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $debtor->description }}</td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">€
                    {{ number_format($debtor->amount, 2, ',', '.') }}</td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                    {{ \Carbon\Carbon::parse($debtor->due_date)->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4">
                    @if ($debtor->is_paid)
                        <span
                            class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Pago</span>
                    @else
                        <span
                            class="px-2 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Pendente</span>
                    @endif
                </td>

                <td class="px-6 py-4 text-right space-x-2">
                    @if (!$debtor->is_paid)
                        <button @click="openModal('edit', {{ $debtor->id }})"
                            class="text-blue-600 hover:text-blue-800" title="Editar">
                            <x-icon name="edit" class="text-blue-600 hover:text-blue-800" />
                        </button>

                        <button @click="openModal('finish', {{ $debtor->id }})"
                            class="text-green-600 hover:text-green-800" title="Concluir">
                            <x-icon name="check" class="text-green-600 hover:text-green-800" />
                        </button>
                    @endif

                    <button @click="openModal('delete', {{ $debtor->id }})" class="text-red-600 hover:text-red-800"
                        title="Excluir">
                        <x-icon name="trash" class="text-red-600 hover:text-red-800" />
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table> --}}


<div x-data="debtManager()" class="overflow-x-auto bg-white dark:bg-gray-700 rounded-lg shadow">
    <table class="w-full min-w-max">
        <thead class="bg-gray-100 dark:bg-gray-600">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Nome</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Descrição
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Valor</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Vencimento
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Status
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Ações
                </th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @foreach ($debtors as $debtor)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <!-- Nome -->
                    <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">
                        {{ $debtor->name }}
                    </td>

                    <!-- Descrição -->
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300 max-w-xs truncate">
                        {{ $debtor->description ?? '—' }}
                    </td>

                    <!-- Valor -->
                    <td class="px-6 py-4">
                        <span
                            class="px-2 inline-flex text-xs font-semibold rounded-full
                            {{ $debtor->amount > 500
                                ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                            € {{ number_format($debtor->amount, 2, ',', '.') }}
                        </span>
                    </td>

                    <!-- Vencimento -->
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300 text-sm">
                        {{ \Carbon\Carbon::parse($debtor->due_date)->format('d/m/Y') }}
                        @if (\Carbon\Carbon::parse($debtor->due_date)->isPast() && !$debtor->is_paid)
                            <span class="ml-2 text-xs text-red-500">(Atrasado)</span>
                        @endif
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4">
                        @if ($debtor->is_paid)
                            <span
                                class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Pago
                            </span>
                        @else
                            <span
                                class="px-2 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                Pendente
                            </span>
                        @endif
                    </td>

                    <!-- Ações -->
                    <td class="px-6 py-4 text-right space-x-2">
                        @if (!$debtor->is_paid)
                            <button @click="openModal('edit', {{ $debtor->id }})"
                                class="text-blue-600 hover:text-blue-800" title="Editar">
                                <x-icon name="edit" class="text-blue-600 hover:text-blue-800" />
                            </button>

                            <button @click="openModal('finish', {{ $debtor->id }})"
                                class="text-green-600 hover:text-green-800" title="Concluir">
                                <x-icon name="check" class="text-green-600 hover:text-green-800" />
                            </button>
                        @endif

                        <button @click="openModal('delete', {{ $debtor->id }})"
                            class="text-red-600 hover:text-red-800" title="Excluir">
                            <x-icon name="trash" class="text-red-600 hover:text-red-800" />
                        </button>

                        <!-- Expandir histórico -->
                        <button @click="toggleInstallments({{ $debtor->id }})"
                            class="transition-transform duration-200 hover:rotate-180 text-blue-600 hover:text-blue-800">
                            <x-icon name="chevron-down" />
                        </button>


                    </td>
                </tr>

                <!-- Histórico expandido -->
                <tr>
                    <td colspan="6" class="px-0 py-0">
                        <div x-show="isExpanded({{ $debtor->id }})" x-collapse.duration.300ms
                            class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-t border-gray-200 dark:border-gray-600">

                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                                Histórico de Alterações
                            </h4>

                            @if ($debtor->edits->count() > 0)
                                <div class="space-y-3">
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
                                                Valor:
                                                <span
                                                    class="font-semibold text-red-500">€{{ number_format($edit->old_amount, 2, ',', '.') }}</span>
                                                →
                                                <span
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
                                <p class="text-sm text-gray-500 dark:text-gray-400">
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
