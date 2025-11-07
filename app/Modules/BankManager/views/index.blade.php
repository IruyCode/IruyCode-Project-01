<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bank Manager</title>
</head>

<body>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">💰 Bank Manager Dashboard</h1>

        <!-- Cards de Contas -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach ($accounts as $account)
                <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 hover:shadow-lg transition">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">
                        👤 {{ $account->user_name }}
                    </h2>
                    <p class="text-gray-500 text-sm mb-1">Conta #{{ $account->id }}</p>
                    <p class="text-2xl font-bold {{ $account->balance >= 0 ? 'text-green-600' : 'text-red-500' }}">
                        {{ number_format($account->balance, 2, ',', '.') }} €
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Atualizado em
                        {{ \Carbon\Carbon::parse($account->updated_at)->format('d/m/Y H:i') }}</p>
                </div>
            @endforeach
        </div>

        <!-- Últimas Transações -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">📊 Últimas Transações</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100 text-gray-600 text-sm uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">Categoria</th>
                            <th class="px-4 py-2 text-left">Valor</th>
                            <th class="px-4 py-2 text-left">Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($transactions as $t)
                            <tr>
                                <td class="px-4 py-2">{{ $t->category_name }}</td>
                                <td
                                    class="px-4 py-2 font-semibold {{ $t->amount > 0 ? 'text-green-600' : 'text-red-500' }}">
                                    {{ number_format($t->amount, 2, ',', '.') }} €
                                </td>
                                <td class="px-4 py-2 text-gray-500">
                                    {{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
