<div x-show="activeEditGoalId === {{ $goal->id }}" x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none" x-cloak>
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Editar Meta
            </h2>
            <button @click="closeEditModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('bank-manager.goals.update', $goal->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-gray-700 dark:text-gray-300 mb-2">Nome
                    da
                    Meta</label>
                <input type="text" id="name" name="name" value="{{ $goal->name }}"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="target_amount" class="block text-gray-700 dark:text-gray-300 mb-2">Valor
                    Alvo</label>
                <input type="number" id="target_amount" name="target_amount" value="{{ $goal->target_amount }}"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="deadline" class="block text-gray-700 dark:text-gray-300 mb-2">Prazo</label>
                <input type="date" id="deadline" name="deadline" value="{{ $goal->deadline }}"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" @click="closeEditModal()"
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
