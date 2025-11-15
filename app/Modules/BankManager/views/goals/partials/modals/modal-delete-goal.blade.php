<div x-show="activeDeleteGoalId === {{ $goal->id }}" x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none" x-cloak>
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-md">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
            Tem certeza que deseja excluir esta meta?
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
            Esta ação não poderá ser desfeita. A meta
            <strong>{{ $goal->name }}</strong> será permanentemente removida.
        </p>

        <form method="POST" action="{{ route('bank-manager.goals.destroy', $goal->id) }}">
            @csrf
            @method('DELETE')

            <label for="resgate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                O que deseja fazer com o valor atual da meta?
            </label>

            <select name="resgate" id="resgate" required
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm mb-6 dark:bg-gray-700 dark:text-white">
                <option value="return">Devolver para a Conta Ordem</option>
                <option value="delete">Excluir com a meta</option>
            </select>

            <div class="flex justify-end space-x-3">
                <button type="button" @click="closeDeleteModal()"
                    class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancelar
                </button>

                <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 transition">
                    Excluir Meta
                </button>
            </div>
        </form>
    </div>
</div>
