<!-- Modal -->
<div x-show="showAddGoalModal" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity"
    @click.self="showAddGoalModal = false">

    <!-- Container Principal -->
    <div x-show="showAddGoalModal" x-transition:enter="ease-out duration-300"
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
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"
                            fill="none" />
                        <circle cx="12" cy="12" r="6" stroke="currentColor" stroke-width="2"
                            fill="none" />
                        <circle cx="12" cy="12" r="2" stroke="currentColor" stroke-width="2"
                            fill="none" />
                    </svg>
                    <h2 class="text-xl font-bold text-white">Nova Meta Financeira</h2>
                </div>
                <button @click="showAddGoalModal = false" class="text-white hover:text-blue-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Corpo do Formulário -->
        <div class="p-6">
            <form action="{{ route('bank-manager.goals.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nome da Meta -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nome da Meta <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" placeholder="Ex: Reserva de emergência" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                dark:bg-gray-700 dark:text-white transition-all">
                </div>

                <!-- Descrição -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Descrição
                    </label>
                    <textarea name="description" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                    dark:bg-gray-700 dark:text-white transition-all"
                        placeholder="Descreva sua meta (opcional)"></textarea>
                </div>

                <!-- Valor Objetivo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Valor Objetivo (€) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400">€</span>
                        </div>
                        <input type="number" name="target_amount" step="0.01" min="0.01" placeholder="0,00"
                            required
                            class="w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                                                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                        dark:bg-gray-700 dark:text-white transition-all">
                    </div>
                </div>

                <!-- Data Limite -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Data Limite <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="deadline" min="{{ date('Y-m-d') }}" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                dark:bg-gray-700 dark:text-white transition-all">
                </div>

                <div x-data="{ currentAmount: '' }">

                    <!-- Valor Atual -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Valor Já Poupado (€) (Opcional)
                        </label>

                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">€</span>
                            </div>

                            <input type="number" name="current_amount" step="0.01" min="0" placeholder="0,00"
                                x-model="currentAmount"
                                class="w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                       dark:bg-gray-700 dark:text-white transition-all">
                        </div>
                    </div>

                    <!-- Select da Conta (só aparece se currentAmount tiver valor) -->
                    <div x-show="parseFloat(currentAmount) > 0" x-transition>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 mt-3">
                            Escolha a Conta para Devolver o Valor
                        </label>

                        <select name="account_balance_id" id="account_balance_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                            dark:bg-gray-700 dark:text-white">

                            @foreach ($accountBalance as $account)
                                <option value="{{ $account->id }}">
                                    {{ $account->account_name }} ({{ $account->bank_name }}) — Saldo:
                                    {{ number_format($account->current_balance, 2, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>




                <!-- Rodapé -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" @click="showAddGoalModal = false"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                            text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 
                            hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg 
                            hover:from-blue-700 hover:to-blue-600 transition-colors shadow-md">
                        Criar Meta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
