<!-- Modal de Edição -->
<div x-show="activeEditDebtId === {{ $debt->id }}" x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none" x-cloak>
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Editar Dívida</h2>
            <button @click="closeEditDebtModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Formulário de Edição -->
        <form method="POST" action="{{ route('bank-manager.debts.edit', $debt->id) }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">Nome da
                    Dívida</label>
                <input type="text" name="name" value="{{ $debt->name }}"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">Descrição</label>
                <input type="text" name="description" value="{{ $debt->description }}"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>


            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">Valor
                    Total</label>
                <input type="number" name="total_amount" step="0.01" min="0.01"
                    value="{{ $debt->total_amount }}"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">

                <label class="block text-gray-700 dark:text-gray-300">
                    Número de Parcelas <span class="text-red-500">*</span>
                </label>
                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                    (Atual: <span class="font-semibold">{{ $debt->installmentsList->count() }}</span>)
                </span>

                <div class="relative">
                    <input type="number" name="installments" id="installments" required min="1" max="36"
                        value="{{ $debt->installmentsList->count() }}"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500"
                        x-model="installmentsCount" @change="validateInstallments()">
                </div>

            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" @click="closeEditDebtModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
