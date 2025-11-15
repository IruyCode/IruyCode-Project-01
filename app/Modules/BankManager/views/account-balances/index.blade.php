@extends('bankmanager::app')

@section('content-component')
    <div x-data="accountManager()" class="w-full px-4 py-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                    </svg>
                    Gestão de Contas Bancárias
                </h1>
                <p class="text-gray-600 dark:text-gray-300">Gerencie suas contas, saldos e bancos associados</p>
            </div>

            <!-- Botão Criar -->
            <button @click="openCreateAccount = true"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Adicionar Conta
            </button>
        </div>

        <!-- Table Wrapper -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm uppercase">
                        <tr>
                            <th class="px-6 py-3">Nome da Conta</th>
                            <th class="px-6 py-3">Banco</th>
                            <th class="px-6 py-3">Tipo</th>
                            <th class="px-6 py-3">Saldo Atual</th>
                            <th class="px-6 py-3">Ativa</th>
                            <th class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($accounts as $account)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 text-gray-900 dark:text-white">
                                    {{ $account->account_name }}
                                </td>

                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                    {{ $account->bank_name }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 text-xs font-medium rounded-full
                                    {{ $account->account_type == 'personal'
                                        ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                        {{ ucfirst($account->account_type) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-gray-900 dark:text-white">
                                    € {{ number_format($account->current_balance, 2, ',', '.') }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 text-xs font-medium rounded-full
                                    {{ $account->is_active
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $account->is_active ? 'Sim' : 'Não' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right space-x-2">

                                    <!-- Edit -->
                                    <button @click="openEditAccount({{ $account->id }})"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        <x-icon name="edit" />
                                    </button>

                                    <!-- Delete -->
                                    <button @click="openDeleteAccount({{ $account->id }})"
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        <x-icon name="trash" />
                                    </button>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                                    Nenhuma conta bancária registrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <!-- Modals -->
        @include('bankmanager::account-balances.partials.modal-create')
        @foreach ($accounts as $account)
            @include('bankmanager::account-balances.partials.modal-edit', ['account' => $account])
            @include('bankmanager::account-balances.partials.modal-delete', ['account' => $account])
        @endforeach

        {{-- Alpine scripts para controlar as modais --}}
        @include('bankmanager::account-balances._scripts')

    </div>
@endsection

