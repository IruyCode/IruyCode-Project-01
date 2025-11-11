<template x-if="openCreate">
    <x-ui.modal title="Adicionar Dívida Parcelada" :show="'openCreate'">

        <form method="POST" action="{{ route('bank-manager.debts.store') }}" class="space-y-6">
            @csrf

            <!-- Seção: Informações Básicas -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b pb-2">
                    Informações Básicas
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nome da Dívida -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nome da Dívida <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                   dark:bg-gray-700 dark:text-white transition-all"
                            placeholder="Ex: Compra de eletrodoméstico">
                        @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Descrição (opcional)
                        </label>
                        <input type="text" name="description" id="description" value="{{ old('description') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                   dark:bg-gray-700 dark:text-white transition-all"
                            placeholder="Ex: Geladeira 500L">
                        @error('description')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Seção: Valores e Parcelamento -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b pb-2">
                    Valores e Parcelamento
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Valor Total -->
                    <div>
                        <label for="total_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Valor Total (€) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">€</span>
                            </div>
                            <input type="number" name="total_amount" id="total_amount" min="0.01" step="0.01"
                                required value="{{ old('total_amount') }}"
                                class="w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                       dark:bg-gray-700 dark:text-white transition-all"
                                placeholder="0,00">
                        </div>
                        @error('total_amount')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Número de Parcelas -->
                    <div>
                        <label for="installments" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Número de Parcelas <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="installments" id="installments" min="1" required
                            value="{{ old('installments') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                   dark:bg-gray-700 dark:text-white transition-all"
                            placeholder="Ex: 6">
                        @error('installments')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Rodapé -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700 mt-6">
                <button type="button" @click="openCreate = false"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                           text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 
                           hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Cancelar
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg 
                           hover:from-blue-700 hover:to-blue-600 transition-colors shadow-md">
                    Criar Dívida
                </button>
            </div>
        </form>

    </x-ui.modal>
</template>
