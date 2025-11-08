<div class="overflow-x-auto bg-white dark:bg-gray-700 rounded-lg shadow" x-data="debtManager()">
    <table class="w-full min-w-max">
        <thead class="bg-gray-100 dark:bg-gray-600">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-700 dark:text-gray-300">Nome</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-700 dark:text-gray-300">Descrição
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-700 dark:text-gray-300">Valor</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-700 dark:text-gray-300">Vencimento
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-700 dark:text-gray-300">Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-700 dark:text-gray-300">Ações</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @foreach ($debtors as $debtor)
                @include('bankmanager::debtors._partials.row', ['debtor' => $debtor])
            @endforeach
        </tbody>
    </table>
</div>
