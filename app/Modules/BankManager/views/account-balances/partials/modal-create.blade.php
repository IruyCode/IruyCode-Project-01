<!-- Modal Criar Conta -->
<div x-show="openCreateAccount" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
    @click.self="openCreateAccount = false">

    <div x-show="openCreateAccount" x-transition class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg">

        <!-- Header -->
        <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                Adicionar Nova Conta Bancária
            </h2>
            <button @click="openCreateAccount = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <x-icon name="close" />
            </button>
        </div>

        <!-- Formulário -->
        <form action="{{ route('bank-manager.account-balances.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Nome da Conta -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Nome da Conta</label>
                <input type="text" name="account_name" required value="{{ old('account_name') }}"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Banco -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Nome do Banco</label>
                <input type="text" name="bank_name" required value="{{ old('bank_name') }}"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Saldo -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Saldo Inicial (€)</label>
                <input type="number" step="0.01" name="current_balance" required
                    value="{{ old('current_balance', 0.0) }}"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Tipo de Conta -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Tipo de Conta</label>
                <select name="account_type" required
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="personal">Pessoal</option>
                    <option value="business">Empresarial</option>
                </select>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <button type="button" @click="openCreateAccount = false"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg text-gray-800 dark:text-gray-100">
                    Cancelar
                </button>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                    Guardar Conta
                </button>
            </div>
        </form>
    </div>
</div>
