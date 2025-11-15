@foreach ($debtors as $debtor)
    <template x-if="activeModal === 'delete' && activeId === {{ $debtor->id }}">
        <x-ui.action-modal title="Excluir Devedor" headerClass="bg-gradient-to-r from-red-600 to-red-500 text-white"
            :show="'activeModal'">

            <form method="POST" action="{{ route('bank-manager.debtors.destroy', $debtor->id) }}">
                @csrf
                @method('DELETE')

                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    Tem certeza que deseja excluir o devedor
                    <strong>{{ $debtor->name }}</strong>?
                    Esta ação não poderá ser desfeita.
                </p>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" @click="activeModal = null"
                        class="px-4 py-2 border rounded-lg dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Cancelar
                    </button>

                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Excluir
                    </button>
                </div>
            </form>

        </x-ui.action-modal>
    </template>
@endforeach
