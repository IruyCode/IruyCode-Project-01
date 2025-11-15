<!-- Modal Editar Conta -->
<div x-show="activeEditAccountId === {{ $account->id }}" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
    @click.self="closeEditAccount()">

    <div x-show="activeEditAccountId === {{ $account->id }}" x-transition
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg">

        <!-- Header -->
        <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                Editar Conta: {{ $account->account_name }}
            </h2>
            <button @click="closeEditAccount()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <x-icon name="close" />
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('bank-manager.account-balances.update', $account->id) }}" method="POST"
            class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <!-- Nome -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Nome da Conta</label>
                <input type="text" name="account_name" required value="{{ $account->account_name }}"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Banco -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Nome do Banco</label>
                <input type="text" name="bank_name" required value="{{ $account->bank_name }}"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Saldo -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Saldo Atual (€)</label>
                <input type="number" step="0.01" name="current_balance" required
                    value="{{ $account->current_balance }}"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Tipo -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Tipo de Conta</label>
                <select name="account_type"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                    <option value="personal" @selected($account->account_type === 'personal')>Pessoal</option>
                    <option value="business" @selected($account->account_type === 'business')>Empresarial</option>
                </select>
            </div>

            <!-- Ativa -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Conta Ativa</label>
                <select name="is_active"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                    <option value="1" @selected($account->is_active)>Sim</option>
                    <option value="0" @selected(!$account->is_active)>Não</option>
                </select>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <button type="button" @click="closeEditAccount()"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg text-gray-800 dark:text-gray-100">
                    Cancelar
                </button>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                    Guardar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
