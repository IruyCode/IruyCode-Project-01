@foreach ($debtors as $debtor)
    <template x-if="activeModal === 'finish' && activeId === {{ $debtor->id }}">
        <x-ui.action-modal title="Concluir Dívida" headerClass="bg-gradient-to-r from-green-600 to-green-500 text-white"
            :show="'activeModal'">

            <form method="POST" action="{{ route('bank-manager.debtors.conclude', $debtor->id) }}">
                @csrf

                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    Deseja marcar a dívida de <strong>{{ $debtor->name }}</strong> como paga?
                </p>

                <select name="account_balance_id" id="account_balance_id">
                    @foreach ($accountBalance as $account)
                        <option value="{{ $account->id }}">
                            {{ $account->account_name }}({{$account->bank_name}}) - Saldo: {{ number_format($account->current_balance, 2, ',', '.') }}
                        </option>
                    @endforeach
                </select>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" @click="activeModal = null"
                        class="px-4 py-2 border rounded-lg dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Cancelar
                    </button>

                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Confirmar
                    </button>
                </div>
            </form>

        </x-ui.action-modal>
    </template>
@endforeach
