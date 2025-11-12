@extends('bankmanager::dashboard')

@section('content-component')
    <!-- Bloco Metas -->
    <div class="w-full px-4 py-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg" x-data="metas">

        <div class="container mx-auto">

            <!-- Cabeçalho e Estatísticas -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">

                <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                        <circle cx="12" cy="12" r="6" stroke="currentColor" stroke-width="2" fill="none" />
                        <circle cx="12" cy="12" r="2" stroke="currentColor" stroke-width="2" fill="none" />
                    </svg>
                    Metas Financeiras
                </h1>
                <p class="text-gray-600 dark:text-gray-300">
                    Acompanhe seu progresso em direção aos seus objetivos financeiros
                </p>

                <div class=" flex items-center justify-center py-4" x-data="{ showAddGoalModal: false }">

                    <!-- Botão -->
                    <button @click="showAddGoalModal = true"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Nova Meta
                    </button>

                    @include('bankmanager::goals.partials.modals.modal-create')

                </div>

            </div>

            @include('bankmanager::goals.partials.card-goals')

            {{-- <!-- Seção de Metas Concluídas -->
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Metas Concluídas</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($goals as $goal)
                        @if ($goal->is_completed)
                            <div
                                class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden border border-green-200 dark:border-green-800/50">
                                <!-- Cabeçalho do Card -->
                                <div class="px-6 py-4 border-b dark:border-gray-600 bg-green-50 dark:bg-green-900/20">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                            {{ $goal->name }}
                                        </h3>
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Concluída
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $goal->description }}
                                    </p>
                                </div>

                                <!-- Corpo do Card -->
                                <div class="px-6 py-4">
                                    <!-- Barra de Progresso (100%) -->
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-1">
                                            <span>€{{ number_format($goal->current_amount, 2) }}</span>
                                            <span>€{{ number_format($goal->target_amount, 2) }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                            <div class="bg-green-500 h-2.5 rounded-full" style="width: 100%"></div>
                                        </div>
                                        <div class="text-right mt-1 text-xs text-green-600 dark:text-green-400">
                                            100% concluído
                                        </div>
                                    </div>

                                    <!-- Estatísticas -->
                                    <div class="grid grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Valor atingido</p>
                                            <p class="font-medium text-gray-800 dark:text-white">
                                                €{{ number_format($goal->current_amount, 2, ',', '.') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Objetivo</p>
                                            <p class="font-medium text-gray-800 dark:text-white">
                                                €{{ number_format($goal->target_amount, 2, ',', '.') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Diferença</p>
                                            <p
                                                class="font-medium @if ($goal->current_amount > $goal->target_amount) text-green-600 dark:text-green-400 @else text-gray-800 dark:text-white @endif">
                                                @php
                                                    $difference = $goal->current_amount - $goal->target_amount;
                                                @endphp
                                                {{ $difference > 0 ? '+' : '' }}€{{ number_format(abs($difference), 2, ',', '.') }}
                                            </p>
                                        </div>
                                        <div class="col-span-full"> <!-- Nova linha para a data -->
                                            <p class="text-gray-500 dark:text-gray-400">Concluída em</p>
                                            <p class="font-medium text-gray-800 dark:text-white">
                                                {{ $goal->completed_at ? \Carbon\Carbon::parse($goal->completed_at)->format('d/m/Y') : '--' }}
                                            </p>
                                        </div>
                                    </div>

                                </div>

                                <!-- Rodapé do Card (metas concluídas) -->
                                <div
                                    class="px-6 py-3 bg-gray-50 dark:bg-gray-600/30 border-t dark:border-gray-600 text-center">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Criada em {{ \Carbon\Carbon::parse($goal->created_at)->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if ($goals->where('is_completed', true)->isEmpty())
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">Nenhuma meta concluída ainda</p>
                        </div>
                    @endif
                </div>
            </div> --}}

            <!-- Seção de Metas Concluídas -->
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Metas Concluídas</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    

                    @foreach ($completedGoals as $goal)
                        <div
                            class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden border border-green-200 dark:border-green-800/50">
                            <!-- Cabeçalho do Card -->
                            <div class="px-6 py-4 border-b dark:border-gray-600 bg-green-50 dark:bg-green-900/20">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                        {{ $goal->name }}
                                    </h3>
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Concluída
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $goal->description }}
                                </p>
                            </div>

                            <!-- Corpo do Card -->
                            <div class="px-6 py-4">
                                <!-- Barra de Progresso (100%) -->
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-1">
                                        <span>€{{ number_format($goal->current_amount, 2) }}</span>
                                        <span>€{{ number_format($goal->target_amount, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                        <div class="bg-green-500 h-2.5 rounded-full" style="width: 100%"></div>
                                    </div>
                                    <div class="text-right mt-1 text-xs text-green-600 dark:text-green-400">
                                        100% concluído
                                    </div>
                                </div>

                                <!-- Estatísticas -->
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400">Valor atingido</p>
                                        <p class="font-medium text-gray-800 dark:text-white">
                                            €{{ number_format($goal->current_amount, 2, ',', '.') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400">Objetivo</p>
                                        <p class="font-medium text-gray-800 dark:text-white">
                                            €{{ number_format($goal->target_amount, 2, ',', '.') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400">Diferença</p>
                                        <p
                                            class="font-medium @if ($goal->current_amount > $goal->target_amount) text-green-600 dark:text-green-400 @else text-gray-800 dark:text-white @endif">
                                            @php
                                                $difference = $goal->current_amount - $goal->target_amount;
                                            @endphp
                                            {{ $difference > 0 ? '+' : '' }}€{{ number_format(abs($difference), 2, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="col-span-full">
                                        <p class="text-gray-500 dark:text-gray-400">Concluída em</p>
                                        <p class="font-medium text-gray-800 dark:text-white">
                                            {{ $goal->completed_at ? \Carbon\Carbon::parse($goal->completed_at)->format('d/m/Y') : '--' }}
                                        </p>
                                    </div>
                                </div>

                            </div>

                            <!-- Rodapé -->
                            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-600/30 border-t dark:border-gray-600 text-center">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Criada em {{ \Carbon\Carbon::parse($goal->created_at)->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($completedGoals->isEmpty())
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">Nenhuma meta concluída ainda</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sem metas cadastradas -->
            @if ($goals->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Nenhuma meta cadastrada
                    </h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">Comece definindo sua primeira meta
                        financeira.</p>
                </div>
            @endif

        </div>

    </div>
@endsection
