@foreach ($debtors as $debtor)
    <template x-if="activeModal === 'edit' && activeId === {{ $debtor->id }}">
        <x-ui.action-modal title="Editar Devedor" headerClass="bg-gradient-to-r from-blue-600 to-blue-500 text-white"
            :show="'activeModal'">

            <form method="POST" action="{{ route('bank-manager.debtors.edit', $debtor->id) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                    <input type="text" name="name" value="{{ $debtor->name }}" required
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                    <textarea name="description" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">{{ $debtor->description }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valor (€)</label>
                    <input type="number" name="amount" step="0.01" value="{{ $debtor->amount }}" required
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Data de Vencimento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vencimento</label>
                    <input type="date" name="due_date" value="{{ $debtor->due_date->format('Y-m-d') }}" required
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Motivo da alteração -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Motivo da Alteração <span class="text-gray-500 text-xs">(obrigatório apenas se
                            valor ou data forem alterados)</span>
                    </label>
                    <textarea id="reason" name="reason" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
                                                focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                                                dark:bg-gray-700 dark:text-white"
                        placeholder="Descreva o motivo da alteração (ex: correção de valor, prorrogação de prazo)">{{ old('reason') }}</textarea>
                </div>


                <!-- Rodapé (botões de ação dentro do form) -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700 mt-6">
                    <button type="button" @click="activeModal = null"
                        class="px-4 py-2 border rounded-lg dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Cancelar
                    </button>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Salvar Alterações
                    </button>
                </div>
            </form>

        </x-ui.action-modal>
    </template>
@endforeach
