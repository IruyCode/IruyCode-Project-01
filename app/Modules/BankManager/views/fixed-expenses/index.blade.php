   <div x-data="{ open: false }" class="bg-gray-800 p-4 rounded-lg shadow w-full">
       <!-- Botão para expandir -->
       <button @click="open = !open" class="w-full text-left text-white font-semibold">
           <span x-text="open ? '🔽 Ocultar Despesas Fixas' : '🔍 Consultar Despesas Fixas'"></span>
       </button>

       <!-- Conteúdo do filtro -->
       <div x-show="open" x-transition class="mt-4">

           <div class="container mx-auto px-4 py-8">
               <!-- Cabeçalho e Formulário de Adição -->
               <div class="mb-8">
                   <h1 class="text-2xl font-bold mb-4">Despesas Fixas Mensais</h1>

                   <!-- Formulário para nova despesa -->
                   <form method="POST" action="{{ route('bank-manager.fixed-expenses.createfixedExpense') }}"
                       class="bg-white p-6 rounded-lg shadow-md mb-6">
                       @csrf
                       <h2 class="text-xl font-semibold mb-4">Adicionar Nova Despesa Fixa</h2>

                       <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                           <div>
                               <label class="block text-sm font-medium text-gray-700">Nome</label>
                               <input type="text" name="name" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                           </div>

                           <div>
                               <label class="block text-sm font-medium text-gray-700">Valor (€)</label>
                               <input type="number" step="0.01" name="amount" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                           </div>

                           <div>
                               <label class="block text-sm font-medium text-gray-700">Dia do
                                   Vencimento</label>
                               <select name="due_day" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                   @for ($i = 1; $i <= 31; $i++)
                                       <option value="{{ $i }}">{{ $i }}</option>
                                   @endfor
                               </select>
                           </div>

                           <div class="flex items-end">
                               <button type="submit"
                                   class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                   Adicionar
                               </button>
                           </div>
                       </div>
                   </form>
               </div>

               <!-- Formulário para marcar pagamentos -->
               <form method="POST" action="{{ route('bank-manager.fixed-expenses.markAsPaidFixedExpense') }}">
                   @csrf
                   @method('PUT')

                   <!-- Lista organizada por dia de vencimento -->
                   <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                       @for ($day = 1; $day <= 31; $day++)
                           @php
                               $expensesForDay = $fixedExpenses->where('due_day', $day);
                           @endphp

                           @if ($expensesForDay->count() > 0)
                               <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                   <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
                                       <h2 class="font-semibold text-lg">Dia {{ $day }}</h2>
                                   </div>

                                   <ul class="divide-y divide-gray-200">
                                       @foreach ($expensesForDay as $expense)
                                           @php
                                               $status = $expense->getStatusForMonth(now()->year, now()->month);
                                           @endphp

                                           <li class="px-4 py-3 flex items-center justify-between">
                                               <div class="flex items-center">
                                                   @if ($status !== 'paga')
                                                       <!-- Checkbox aparece para "em aberto" e "atrasada" -->
                                                       <input type="checkbox" name="expenses[]"
                                                           value="{{ $expense->id }}" id="expense-{{ $expense->id }}"
                                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-3">
                                                   @endif

                                                   <label for="expense-{{ $expense->id }}" class="block">
                                                       <span class="font-medium">{{ $expense->name }}</span>
                                                       <span class="text-gray-600 block text-sm">
                                                           € {{ number_format($expense->amount, 2, ',', '.') }}
                                                       </span>
                                                   </label>
                                               </div>

                                               <div class="flex items-center space-x-2">
                                                   @if ($status === 'paga')
                                                       <span class="text-green-600 font-semibold">✅ Pago</span>
                                                   @elseif ($status === 'atrasada')
                                                       <span class="text-red-600 font-semibold">⚠️ Atrasado</span>
                                                   @endif

                                                   <!-- Botão excluir com JS -->
                                                   <button type="button" class="text-red-600 hover:text-red-900"
                                                       onclick="deleteExpense('{{ route('bank-manager.fixed-expenses.destroyExpense', $expense->id) }}')">
                                                       <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                           viewBox="0 0 20 20" fill="currentColor">
                                                           <path fill-rule="evenodd"
                                                               d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                               clip-rule="evenodd" />
                                                       </svg>
                                                   </button>
                                               </div>
                                           </li>
                                       @endforeach
                                   </ul>
                               </div>
                           @endif
                       @endfor
                   </div>

                   <div class="mt-4">
                       <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                           Selecionar Conta para débito
                       </label>

                       <select name="account_balance_id" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                           @foreach ($accountBalance as $account)
                               <option value="{{ $account->id }}">
                                   {{ $account->account_name }} ({{ $account->bank_name }}) –
                                   Saldo: €{{ number_format($account->current_balance, 2, ',', '.') }}
                               </option>
                           @endforeach
                       </select>
                   </div>

                   <!-- Botão para marcar como pago -->
                   <div class="mt-6">
                       <button type="submit"
                           class="w-full md:w-auto bg-green-600 text-white py-2 px-6 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                           💰 Marcar selecionados como pagos
                       </button>
                   </div>
               </form>

               <!-- Script de exclusão -->
               <script>
                   function deleteExpense(url) {
                       if (!confirm('Tem certeza que deseja excluir esta despesa fixa?')) return;

                       const form = document.createElement('form');
                       form.method = 'POST';
                       form.action = url;

                       const csrf = document.createElement('input');
                       csrf.type = 'hidden';
                       csrf.name = '_token';
                       csrf.value = '{{ csrf_token() }}';
                       form.appendChild(csrf);

                       const method = document.createElement('input');
                       method.type = 'hidden';
                       method.name = '_method';
                       method.value = 'DELETE';
                       form.appendChild(method);

                       document.body.appendChild(form);
                       form.submit();
                   }
               </script>
           </div>
       </div>
   </div>
