<!-- Modal de Exclusão -->
<div x-show="activeDeleteDebtId === {{ $debt->id }}" x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none" x-cloak>
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-md">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Confirmar
            exclusão</h2>

        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
            Tem certeza que deseja excluir a dívida
            <strong>{{ $debt->name }}</strong>?<br>
            Todas as parcelas associadas também serão removidas.
        </p>

        <form method="POST" action="{{ route('bank-manager.debts.destroy', $debt->id) }}">
            @csrf
            @method('DELETE')

            <!-- Opção de devolução -->
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                O que deseja fazer com os valores já pagos?
            </label>
            <div class="space-y-2 mb-6">
                <label class="flex items-center space-x-2">
                    <input type="radio" name="refund_option" value="keep" checked
                        class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="text-gray-700 dark:text-gray-300 text-sm">Manter como
                        gasto</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="radio" name="refund_option" value="refund"
                        class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="text-gray-700 dark:text-gray-300 text-sm">Devolver
                        valores pagos para a conta</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" @click="closeDeleteDebtModal()"
                    class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 transition">
                    Excluir Dívida
                </button>
            </div>
        </form>
    </div>
</div>
