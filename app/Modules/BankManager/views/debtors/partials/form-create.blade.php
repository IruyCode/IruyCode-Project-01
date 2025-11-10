<form action="{{ route('bank-manager.debtors.store') }}" method="POST" class="space-y-5">
    @csrf
    <x-ui.input label="Nome do Devedor" name="name" required />
    <x-ui.textarea label="Descrição" name="description" />
    <x-ui.input label="Valor (€)" name="amount" type="number" step="0.01" required />
    <x-ui.input label="Data de Vencimento" name="due_date" type="date" required />

    <div class="flex justify-end space-x-3 pt-4">
        <button type="button" @click="showCreate = false"
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                   text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 
                   hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
            Cancelar
        </button>
        <button type="submit"
            class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg 
                   hover:from-blue-700 hover:to-blue-600 transition-colors shadow-md">
            Criar
        </button>
    </div>
</form>
