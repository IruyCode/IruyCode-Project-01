<div x-show="activeDeleteInvestmentsId === {{ $investment->id }}" x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none" x-cloak>
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-md">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
            Tem certeza que deseja excluir este investimento?
        </h2>

        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
            Esta ação não poderá ser desfeita. O investimento
            <strong>{{ $investment->name }}</strong> será permanentemente removido.
        </p>

        <form method="POST" action="{{ route('bank-manager.investments.destroy', $investment->id) }}"
            x-data="{ resgate: 'delete' }">

            @csrf
            @method('DELETE')

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                O que deseja fazer com o valor atual do investimento?
            </label>

            <select name="resgate" x-model="resgate" required
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm mb-6 dark:bg-gray-700 dark:text-white">
                <option value="return">Devolver para a Conta Ordem</option>
                <option value="delete">Excluir com o investimento</option>
            </select>

            <!-- MOSTRAR APENAS SE FOR RETURN -->
            <div x-show="resgate === 'return'" x-transition>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Escolha a Conta para devolver o valor:
                </label>

                <select name="account_balance_id"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm mb-6 dark:bg-gray-700 dark:text-white">

                    @foreach ($accountBalance as $account)
                        <option value="{{ $account->id }}">
                            {{ $account->account_name }} ({{ $account->bank_name }})
                            – Saldo: €{{ number_format($account->current_balance, 2, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" @click="closeDeleteModal()"
                    class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancelar
                </button>

                <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 transition">
                    Excluir Investimento
                </button>
            </div>
        </form>

    </div>
</div>
