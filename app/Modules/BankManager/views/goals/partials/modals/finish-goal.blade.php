<!-- Modal de Confirmação para Concluir Meta -->
<div x-show="activeFinishGoalId === {{ $goal->id }}" x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none" x-cloak>
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-md">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
            Confirmar conclusão da meta
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
            Tem certeza que deseja marcar a meta <strong>{{ $goal->name }}</strong> como
            concluída?
            Esta ação não pode ser desfeita.
        </p>

        <div class="flex justify-end space-x-3">
            <button @click="closeFinishModal()"
                class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">
                Cancelar
            </button>

            <form method="POST" action="{{ route('bank-manager.goals.finish', $goal->id) }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 transition">
                    Confirmar Conclusão
                </button>
            </form>
        </div>
    </div>
</div>
