<!-- Cards de Metas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-data="{
    activeEditGoalId: null,
    activeDeleteGoalId: null,
    activeAdjustValueGoalId: null,
    adjustmentAmount: 0,
    adjustmentType: 'add', // 'add' ou 'remove'

    openEditGoalModal(id) {
        this.activeEditGoalId = id;
    },
    closeEditModal() {
        this.activeEditGoalId = null;
    },

    openDeleteGoalModal(id) {
        this.activeDeleteGoalId = id;
    },
    closeDeleteModal() {
        this.activeDeleteGoalId = null;
    },

    openAdjustValueModal(id) {
        this.activeAdjustValueGoalId = id;
        this.adjustmentAmount = 0;
        this.adjustmentType = 'add';
    },
    closeAdjustValueModal() {
        this.activeAdjustValueGoalId = null;
    },

    activeFinishGoalId: null,

    openFinishGoalModal(id) {
        this.activeFinishGoalId = id;
    },
    closeFinishModal() {
        this.activeFinishGoalId = null;
    },
}">

    @foreach ($goals as $goal)
        @php
            // Converte as datas para objetos Carbon e ajusta para início do dia (remove horas/minutos/segundos)
            $createdAt = \Carbon\Carbon::parse($goal->created_at)->startOfDay(); // Data de criação da meta
            $deadline = \Carbon\Carbon::parse($goal->deadline)->startOfDay(); // Data limite da meta
            $now = now()->startOfDay(); // Data atual (início do dia)

            // Calcula a duração total planejada (em dias) entre criação e prazo
            $totalDuration = $createdAt->diffInDays($deadline); // Total de dias planejado

            // Calcula quantos dias já se passaram desde a criação até hoje
            $elapsedDays = $createdAt->diffInDays($now); // Dias já decorridos

            // Calcula quantos dias faltam para o prazo (valor negativo se já passou do prazo)
            $daysRemaining = $now->diffInDays($deadline, false); // Dias restantes (pode ser negativo)

            // Calcula a porcentagem de conclusão da meta financeira:
            $percentage =
                $goal->target_amount > 0 // Verifica se há um valor alvo definido
                    ? min(($goal->current_amount / $goal->target_amount) * 100, 100) // Calcula % sem ultrapassar 100%
                    : 0; // Se não houver valor alvo, considera 0%

            // Calcula o progresso esperado baseado no tempo decorrido:
            $expectedProgress =
                $totalDuration > 0 // Evita divisão por zero
                    ? ($elapsedDays / $totalDuration) * 100 // % do tempo que já passou
                    : 100; // Se não houver duração, considera 100%

            // Verifica se a meta está no prazo (progresso real >= progresso esperado pelo tempo)
            $isOnTrack = $percentage >= $expectedProgress;

            // Calcula quanto ainda falta para atingir o valor alvo (nunca menor que zero)
            $remaining = max($goal->target_amount - $goal->current_amount, 0);

        @endphp

        @if (!$goal->is_completed)
            <div
                class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-shadow">

                <!-- Cabeçalho do Card -->
                <div class="px-6 py-4 border-b dark:border-gray-600">
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                            {{ $goal->name }}</h3>
                        <span
                            class="px-2 py-1 text-xs rounded-full 
                                {{ $percentage >= 100
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                    : ($isOnTrack
                                        ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                            {{ $percentage >= 100 ? 'Concluída' : ($isOnTrack ? 'No prazo' : 'Atrasada') }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $goal->description }}
                    </p>
                </div>

                <!-- Corpo do Card -->
                <div class="px-6 py-4">
                    <!-- Barra de Progresso -->
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-1">
                            <span>€{{ number_format($goal->current_amount, 2) }}</span>
                            <span>€{{ number_format($goal->target_amount, 2) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%">
                            </div>
                        </div>
                        <div class="text-right mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ number_format($percentage, 1) }}% concluído
                        </div>
                    </div>

                    <!-- Estatísticas -->
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Faltam</p>
                            <p class="font-medium text-gray-800 dark:text-white">
                                €{{ number_format($remaining, 2, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Prazo</p>
                            <p class="font-medium text-gray-800 dark:text-white">
                                {{ $deadline->format('d/m/Y') }}
                                <span
                                    class="block text-xs {{ $daysRemaining < 0 ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }}">
                                    ({{ $daysRemaining }}
                                    {{ abs($daysRemaining) === 1 ? 'dia' : 'dias' }} restantes)
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Histórico de Movimentações -->
                    <div class="mt-4">
                        @php
                            $historyCount = $goal->transactions->count();
                        @endphp

                        @if ($historyCount > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-600 dark:text-gray-300">
                                    {{ $historyCount }} movimentações registradas
                                </span>
                                <button @click="$dispatch('toggle-goal-history', { id: {{ $goal->id }} })"
                                    class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                    Ver histórico
                                </button>
                            </div>
                        @else
                            <p class="text-xs text-gray-500 dark:text-gray-400 italic">
                                Nenhuma movimentação registrada
                            </p>
                        @endif
                    </div>

                </div>

                <!-- Rodapé do Card -->
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-600/30 border-t dark:border-gray-600">
                    <div class="flex flex-col items-center sm:flex-row sm:justify-between gap-3">
                        <!-- Data de criação - sempre alinhada à esquerda em desktop -->
                        <div
                            class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap sm:self-center">
                            Criada em {{ \Carbon\Carbon::parse($goal->created_at)->format('d/m/Y') }}
                        </div>

                        <!-- Grupo de botões - centralizado em mobile, à direita em desktop -->
                        <div class="flex justify-center items-center gap-3 sm:gap-2 w-full sm:w-auto">

                            <!-- Botão Ajustar Valor -->
                            <button @click="openAdjustValueModal({{ $goal->id }})"
                                class="p-1.5 sm:p-1 text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 rounded-full hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors"
                                title="Ajustar valor">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </button>

                            <!-- Botão Concluir Meta  -->
                            <button @click="openFinishGoalModal({{ $goal->id }})"
                                class="p-1.5 sm:p-1 text-green-600 hover:text-green-800 dark:text-green-100 dark:hover:text-green-300 rounded-full hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors"
                                title="Concluir meta">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>

                            <!-- Botão Editar Meta  -->
                            <button @click="openEditGoalModal({{ $goal->id }})"
                                class="p-1.5 sm:p-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
                                title="Editar meta">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            <!-- Botão Excluir Meta -->
                            <button @click="openDeleteGoalModal({{ $goal->id }})"
                                class="p-1.5 sm:p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                title="Excluir meta">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>

                        </div>
                    </div>
                </div>

                <!-- Modal para Ajustar Valor da Meta -->
                @include('bankmanager::goals.partials.modals.adjust-value-goal', ['goal' => $goal])

                <!-- Modal de Confirmação para Concluir Meta -->
                @include('bankmanager::goals.partials.modals.finish-goal', ['goal' => $goal])

                <!-- Modal de Edição para esta meta -->
                @include('bankmanager::goals.partials.modals.edit-goal', ['goal' => $goal])

                <!-- Modal de Confirmação de Exclusão -->
                @include('bankmanager::goals.partials.modals.modal-delete-goal', ['goal' => $goal])


                <!-- Histórico detalhado -->
                <div x-data="{ open: false }"
                    x-on:toggle-goal-history.window="if ($event.detail.id === {{ $goal->id }}) open = !open"
                    x-show="open" x-transition
                    class="px-6 py-4 border-t dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm"
                    style="display: none">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-200 mb-2">Histórico da Meta</h4>

                    <ul class="space-y-1 max-h-40 overflow-y-auto text-xs">
                        @foreach ($goal->transactions as $t)
                            <li class="flex justify-between border-b border-gray-200 dark:border-gray-600 py-1">
                                <span>
                                    {{ ucfirst($t->type) }}: €{{ number_format($t->amount, 2, ',', '.') }}
                                    <span class="text-gray-400">({{ $t->performed_at->format('d/m/Y') }})</span>
                                </span>
                                <span class="text-gray-500 italic">{{ $t->note ?? '' }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        @endif
    @endforeach
</div>
