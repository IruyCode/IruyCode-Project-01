<template x-if="showCreate">
    <x-ui.modal title="Adicionar Devedor" :show="'showCreate'">

        <form method="POST" action="{{ route('bank-manager.debtors.store') }}" class="space-y-6">
            @csrf

            <!-- Nome -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nome do Devedor <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                           dark:bg-gray-700 dark:text-white transition-all"
                    placeholder="Nome completo do devedor">
                @error('name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descrição -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Descrição (opcional)
                </label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                           dark:bg-gray-700 dark:text-white transition-all"
                    placeholder="Detalhes da dívida ou observações...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Valor -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Valor (€) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400">€</span>
                    </div>
                    <input type="number" name="amount" id="amount" min="0.01" step="0.01" required
                        value="{{ old('amount') }}"
                        class="w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                               dark:bg-gray-700 dark:text-white transition-all"
                        placeholder="0,00">
                </div>
                @error('amount')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Data de Vencimento -->
            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Data de Vencimento <span class="text-red-500">*</span>
                </label>
                <input type="date" name="due_date" id="due_date" required value="{{ old('due_date') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                           dark:bg-gray-700 dark:text-white transition-all">
                @error('due_date')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rodapé -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700 mt-6">
                <button type="button" @click="showCreate = false"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                           text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 
                           hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Cancelar
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg 
                           hover:from-blue-700 hover:to-blue-600 transition-colors shadow-md">
                    Criar Devedor
                </button>
            </div>
        </form>

    </x-ui.modal>
</template>
