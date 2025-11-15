 <!-- Menu de navegação apenas para DESKTOP -->
 <div
     class="hidden md:flex flex-col md:flex-row justify-center md:justify-end gap-4 px-4 py-4 bg-black rounded-b-xl shadow-lg relative z-10">

     <div class="flex items-center justify-center w-full h-full px-8 mb-4">
         <!-- Links principais -->
         <nav class="flex items-center space-x-8">

             <!-- Metas -->
             <a href="{{ route('bank-manager.goals.index') }}"
                 class="flex items-center h-full px-2 font-medium transition duration-300
                        {{ Route::is('bank-manager.goals.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                 <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <circle cx="12" cy="12" r="10" />
                     <circle cx="12" cy="12" r="6" />
                     <circle cx="12" cy="12" r="2" />
                    </svg>
                 Metas
             </a>

             <!-- Dívidas -->
             <a href="{{ route('bank-manager.debts.index') }}"
                 class="flex items-center h-full px-2 font-medium transition duration-300
                        {{ Route::is('bank-manager.debts.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                 <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <path d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3" />
                     <path d="M12 2v20" />
                 </svg>
                 Dívidas
             </a>

             <!-- Dashboard -->
             <a href="{{ route('bank-manager.index') }}"
                 class="flex items-center h-full px-2 font-medium transition duration-300
                        {{ Route::is('bank-manager.index') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                 <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <rect x="4" y="4" width="16" height="16" rx="4" />
                 </svg>
                 Dashboard
             </a>

             <!-- Devedores -->
             <a href="{{ route('bank-manager.debtors.index') }}"
                 class="flex items-center h-full px-2 font-medium transition duration-300
                        {{ Route::is('bank-manager.debtors.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                 <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <circle cx="9" cy="7" r="4" />
                     <path d="M17 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
                     <path d="M2 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
                     <path d="M17 21v-2a4 4 0 0 0-3-3.87" />
                 </svg>
                 Devedores
             </a>

             <!-- Investimentos -->
             <a href="{{ route('bank-manager.investments.index') }}"
                 class="flex items-center h-full px-2 font-medium transition duration-300
                        {{ Route::is('bank-manager.investments.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                 <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <circle cx="12" cy="12" r="3" />
                     <path
                         d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09A1.65 1.65 0 0 0 9 3.09V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h.09a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                 </svg>
                 Investimentos
             </a>

             <!-- Notificações -->
             {{-- <a href="{{ route('bank-manager.notifications.index') }}"
                 class="flex items-center h-full px-2 font-medium transition duration-300
                        {{ Route::is('bank-manager.notifications.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                 <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <rect x="4" y="4" width="16" height="16" rx="4" />
                 </svg>
                 Notificações
             </a> --}}

         </nav>

     </div>
 </div>
