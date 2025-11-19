<!-- Modal para Ajustar Valor da Meta -->
<div x-show="activeAdjustValueGoalId === {{ $goal->id }}" x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none" x-cloak>
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Ajustar Valor da
                Meta
            </h2>
            <button @click="closeAdjustValueModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('bank-manager.goals.adjust', $goal->id) }}" x-data="{ adjustmentType: '', adjustmentAmount: 0 }">
            @csrf
            @method('PUT')

            <!-- Tipo de Ajuste -->
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">Tipo de Ajuste</label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" x-model="adjustmentType" value="add" class="form-radio text-blue-600">
                        <span class="ml-2 dark:text-gray-300">Adicionar</span>
                    </label>

                    <label class="inline-flex items-center">
                        <input type="radio" x-model="adjustmentType" value="remove" class="form-radio text-blue-600">
                        <span class="ml-2 dark:text-gray-300">Remover</span>
                    </label>
                </div>
            </div>

            <!-- Valor -->
            <div class="mb-4">
                <label for="amount" class="block text-gray-700 dark:text-gray-300 mb-2">Valor</label>
                <input type="number" id="amount" name="amount" x-model="adjustmentAmount"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    step="0.01" min="0" required>
                <input type="hidden" name="type" x-model="adjustmentType">
            </div>

            <!-- SELECT DA CONTA (sempre necessário quando tipo é escolhido) -->
            <div class="mb-4" x-show="adjustmentType !== ''" x-transition>
                <label class="block text-gray-700 dark:text-gray-300 mb-2">
                    Selecione a Conta
                </label>

                <select name="account_balance_id"
                    class="w-full px-4 py-2 border rounded-lg 
                dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    required>

                    @foreach ($accountBalance as $account)
                        <option value="{{ $account->id }}">
                            {{ $account->account_name }} ({{ $account->bank_name }}) — Saldo:
                            {{ number_format($account->current_balance, 2, ',', '.') }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- Informação de totais -->
            <div class="mb-4">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Valor atual: €{{ number_format($goal->current_amount, 2) }}
                </p>

                <p class="text-sm text-gray-600 dark:text-gray-300"
                    x-show="adjustmentAmount > 0 && adjustmentType !== ''">
                    Novo valor:
                    <span
                        x-text="'€' + ({{ $goal->current_amount }} + (adjustmentType === 'add' ? +adjustmentAmount : -adjustmentAmount)).toFixed(2)">
                    </span>
                </p>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-3">
                <button type="button" @click="closeAdjustValueModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 
                   dark:bg-gray-600 dark:hover:bg-gray-500">
                    Cancelar
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 
                   dark:bg-purple-500 dark:hover:bg-purple-600">
                    Aplicar Ajuste
                </button>
            </div>

        </form>

    </div>
</div>
