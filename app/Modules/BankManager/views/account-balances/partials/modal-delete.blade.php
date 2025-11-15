<!-- Modal Delete -->
<div x-show="activeDeleteAccountId === {{ $account->id }}" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
    @click.self="closeDeleteAccount()">

    <div x-show="activeDeleteAccountId === {{ $account->id }}" x-transition
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">

        <!-- Header -->
        <div class="px-6 py-4 border-b dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                Confirmar Eliminação
            </h2>
        </div>

        <div class="px-6 py-4 space-y-3 text-gray-700 dark:text-gray-300">
            <p>Tem certeza que deseja eliminar a conta:</p>
            <p class="font-semibold text-gray-900 dark:text-white">
                {{ $account->account_name }}
            </p>
            <p>Esta ação é irreversível e removerá todos os dados associados.</p>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 flex justify-end gap-3 border-t dark:border-gray-700">
            <button type="button" @click="closeDeleteAccount()"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg text-gray-800 dark:text-gray-100">
                Cancelar
            </button>

            <form action="{{ route('bank-manager.account-balances.delete', $account->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 shadow">
                    Eliminar
                </button>
            </form>
        </div>

    </div>
</div>
