 <div class="w-full py-6 flex-1">
     <div class="bg-black rounded-xl shadow p-2 md:p-4">
         <h2 class="text-lg font-semibold mb-4 text-white text-center md:text-left">
             Transaçõesssss
         </h2>

         <!-- Filtros -->
         <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">

             <!-- Filtro Mês -->
             <div>
                 <label class="block text-xs text-gray-400 mb-1">Mês</label>
                 <select id="filter-month"
                     class="w-full px-3 py-2 rounded border border-gray-600 bg-gray-900 text-gray-200">
                     <option value="">Selecione</option>
                     @for ($m = 1; $m <= 12; $m++)
                         <option value="{{ $m }}">{{ $m }}</option>
                     @endfor
                 </select>
             </div>

             <!-- Filtro Semana -->
             <div id="week-container" class="hidden">
                 <label class="block text-xs text-gray-400 mb-1">Semana</label>
                 <select id="filter-week"
                     class="w-full px-3 py-2 rounded border border-gray-600 bg-gray-900 text-gray-200">
                     <option value="">Selecione</option>
                 </select>
             </div>

             <!-- Filtro Dia -->
             <div id="day-container" class="hidden">
                 <label class="block text-xs text-gray-400 mb-1">Dia</label>
                 <select id="filter-day"
                     class="w-full px-3 py-2 rounded border border-gray-600 bg-gray-900 text-gray-200">
                     <option value="">Selecione</option>
                 </select>
             </div>

             <!-- Filtro Tipo -->
             <div>
                 <label class="block text-xs text-gray-400 mb-1">Tipo</label>
                 <select id="filter-tipo"
                     class="w-full px-3 py-2 rounded border border-gray-600 bg-gray-900 text-gray-200">
                     <option value="">Todos</option>
                     <option value="Despesa Fixa">Despesa Fixa</option>
                     <option value="Investimento">Investimento</option>
                     <option value="Meta">Meta</option>
                     <option value="Receita">Receita</option>
                     <option value="Despesa">Despesa</option>
                     <option value="Despesa Comum">Despesa Comum</option>
                 </select>
             </div>

         </div>

         <!-- Tabela -->
         <div class="overflow-x-auto">
             <table id="TransactionsTable" class="min-w-full divide-y divide-gray-700 text-sm text-gray-200">
                 <thead class="bg-gray-800 text-gray-300">
                     <tr>
                         <th class="px-3 py-2 text-left">Categoria</th>
                         <th class="px-3 py-2 text-left">Valor</th>
                         <th class="px-3 py-2 text-left">Data</th>
                         <th class="px-3 py-2 text-left">Tipo</th>
                     </tr>
                 </thead>
             </table>
         </div>
     </div>
 </div>

 <script>
     $(document).ready(function() {

         let table = $('#TransactionsTable').DataTable({
             processing: true,
             serverSide: true,
             ajax: {
                 url: '/bank-manager/api/receiveDataTableTransactions',
                 data: function(d) {
                     d.month = $('#filter-month').val();
                     d.week = $('#filter-week').val();
                     d.day = $('#filter-day').val();
                     d.tipo = $('#filter-tipo').val();
                 }
             },
             columns: [{
                     data: 'real_category_name',
                     name: 'real_category_name'
                 },
                 {
                     data: 'formatted_amount',
                     name: 'formatted_amount'
                 },
                 {
                     data: 'formatted_date',
                     name: 'formatted_date'
                 },
                 {
                     data: 'tipo',
                     name: 'tipo'
                 }
             ]
         });

         /* ----------------------------------------
          * FILTRO MÊS → EXIBIR SEMANA
          * ---------------------------------------- */
         $('#filter-month').on('change', function() {
             let month = $(this).val();

             // Reset & esconder semana/dia
             $('#week-container').addClass('hidden');
             $('#day-container').addClass('hidden');

             $('#filter-week').empty().append('<option value="">Selecione a Semana</option>');
             $('#filter-day').empty().append('<option value="">Selecione o Dia</option>');

             if (month !== "") {
                 for (let i = 1; i <= 5; i++) {
                     $('#filter-week').append(`<option value="${i}">Semana ${i}</option>`);
                 }
                 $('#week-container').removeClass('hidden');
             }

             table.ajax.reload();
         });

         /* ----------------------------------------
          * FILTRO SEMANA → EXIBIR DIAS
          * ---------------------------------------- */
         $('#filter-week').on('change', function() {
             let week = $(this).val();
             let month = $('#filter-month').val();

             $('#day-container').addClass('hidden');
             $('#filter-day').empty().append('<option value="">Selecione o Dia</option>');

             if (week && month) {
                 let year = new Date().getFullYear();
                 let startOfMonth = new Date(year, month - 1, 1);

                 let weekStart = new Date(startOfMonth);
                 weekStart.setDate(startOfMonth.getDate() + (week - 1) * 7);

                 let weekEnd = new Date(weekStart);
                 weekEnd.setDate(weekStart.getDate() + 6);

                 let endOfMonth = new Date(year, month, 0);
                 if (weekEnd > endOfMonth) weekEnd = endOfMonth;

                 for (let d = weekStart.getDate(); d <= weekEnd.getDate(); d++) {
                     $('#filter-day').append(`<option value="${d}">${d}</option>`);
                 }

                 $('#day-container').removeClass('hidden');
             }

             table.ajax.reload();
         });

         /* ----------------------------------------
          * FILTRO DIA
          * ---------------------------------------- */
         $('#filter-day').on('change', function() {
             table.ajax.reload();
         });

         /* ----------------------------------------
          * FILTRO TIPO
          * ---------------------------------------- */
         $('#filter-tipo').on('change', function() {
             table.ajax.reload();
         });

     });
 </script>
