@extends('bankmanager::app')

@section('content-component')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

        <!-- Saldo Total Consolidado -->
        <a href="{{ route('bank-manager.account-balances.index') }}"
            class="p-6 rounded-xl shadow-md bg-gradient-to-r from-blue-600 to-blue-500 text-white hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-bold">
                        {{ number_format($totalBalance, 2, ',', '.') }} €
                    </h3>
                    <p class="text-blue-100 mt-1">Saldo Total Consolidado</p>
                </div>
                <div class="text-5xl opacity-40">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </a>

        <!-- Saldo Pessoal -->
        <a href="{{ route('bank-manager.account-balances.index') }}"
            class="p-6 rounded-xl shadow-md bg-gradient-to-r from-green-600 to-green-500 text-white hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-bold">
                        {{ number_format($personalBalance, 2, ',', '.') }} €
                    </h3>
                    <p class="text-green-100 mt-1">Saldo Pessoal</p>
                </div>
                <div class="text-5xl opacity-40">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </a>

        <!-- Saldo Empresarial -->
        <a href="{{ route('bank-manager.account-balances.index') }}"
            class="p-6 rounded-xl shadow-md bg-gradient-to-r from-yellow-500 to-yellow-400 text-white hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-bold">
                        {{ number_format($businessBalance, 2, ',', '.') }} €
                    </h3>
                    <p class="text-yellow-100 mt-1">Saldo Empresarial</p>
                </div>
                <div class="text-5xl opacity-40">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </a>

    </div>

    <!-- Contas Ativas -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow mb-10">
        <div class="px-6 py-4 border-b dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Contas Ativas</h3>

            <a href="{{ route('bank-manager.account-balances.index') }}"
                class="px-4 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white 
                   hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 
                   shadow transition">
                Gerir Contas
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-gray-600 dark:text-gray-300">#
                        </th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-600 dark:text-gray-300">Conta</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-600 dark:text-gray-300">Banco</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-600 dark:text-gray-300">Tipo</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-600 dark:text-gray-300">Saldo</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($accounts as $account)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $account->account_name }}
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $account->bank_name }}</td>

                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold 
                                {{ $account->account_type === 'personal'
                                    ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                    {{ ucfirst($account->account_type) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                € {{ number_format($account->current_balance, 2, ',', '.') }}
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                                Nenhuma conta ativa para exibir.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    @include('bankmanager::fixed-expenses.index')

    <br>
    @include('bankmanager::dashboard.partials.actions-categories')

    @include('bankmanager::dashboard.partials.dataTables')
@endsection
