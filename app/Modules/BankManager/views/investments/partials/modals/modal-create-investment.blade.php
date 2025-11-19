<div x-show="showAddInvestmentModal" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity"
    @click.self="showAddInvestmentModal = false">

    <!-- Container Principal -->
    <div x-show="showAddInvestmentModal" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden" @click.stop>

        <!-- Cabeçalho -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M3 14h18M12 4v16" />
                    </svg>
                    <h2 class="text-xl font-bold text-white">Adicionar Investimento</h2>
                </div>
                <button @click="showAddInvestmentModal = false"
                    class="text-white hover:text-blue-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Corpo do Formulário -->
        <div class="p-6">
            <form action="{{ route('bank-manager.investments.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Nome do Investimento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nome do Investimento <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                dark:bg-gray-700 dark:text-white transition-all"
                        placeholder="Ex: Tesouro Direto IPCA+ 2026">
                </div>

                <!-- Plataforma -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Plataforma <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="platform" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                dark:bg-gray-700 dark:text-white transition-all"
                        placeholder="Ex: XP Investimentos">
                </div>

                <!-- Tipo de Investimento -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tipo de Investimento <span class="text-red-500">*</span>
                    </label>
                    <select id="type" name="type" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                dark:bg-white dark:bg-gray-700 dark:text-white transition-all">
                        @php
                            $types = ['Renda Fixa', 'Ações', 'Fundos Imobiliários'];
                        @endphp
                        @foreach ($types as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                        <option value="#">Outro / Não especificado</option>
                    </select>
                </div>

                <!-- Valor Inicial -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Valor Inicial <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400">€</span>
                        </div>
                        <input type="number" step="0.01" min="0.01" name="initial_amount" required
                            class="w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                    dark:bg-gray-700 dark:text-white transition-all"
                            placeholder="0,00">
                    </div>
                </div>

                <!-- Conta onde será debitado o valor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Selecionar Conta <span class="text-red-500">*</span>
                    </label>

                    <select name="account_balance_id" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                        dark:bg-gray-700 dark:text-white transition">

                        @foreach ($accountBalance as $account)
                            <option value="{{ $account->id }}">
                                {{ $account->account_name }} ({{ $account->bank_name }})
                                – Saldo: €{{ number_format($account->current_balance, 2, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <!-- Rodapé -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" @click="showAddInvestmentModal = false"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                                                text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 
                                                hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg 
                                                hover:from-blue-700 hover:to-blue-600 transition-colors shadow-md">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
