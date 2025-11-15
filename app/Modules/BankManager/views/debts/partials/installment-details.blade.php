<!-- Detalhes das Parcelas (Expandível) -->
<div x-show="isExpanded({{ $debt->id }})" x-collapse.duration.300ms class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4">
    <!-- Próxima Parcela -->
    @if ($nextInstallment)
        <div
            class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg border border-blue-200 dark:border-blue-900/30 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                            PRÓXIMA PARCELA
                        </span>
                        @if (\Carbon\Carbon::parse($nextInstallment->due_date)->isPast())
                            <span
                                class="px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                ATRASADA
                            </span>
                        @endif
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">
                        {{ \Carbon\Carbon::parse($nextInstallment->due_date)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    </h4>
                    <div class="mt-1 flex items-center gap-3">
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Parcela #{{ $nextInstallment->installment_number }}
                        </span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white">
                            € {{ number_format($nextInstallment->amount, 2) }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('bank-manager.debts.installments.markPaid', $nextInstallment->id) }}"
                    method="post">
                    @csrf
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors whitespace-nowrap">
                        Pagar Esta Parcela
                    </button>
                </form>
            </div>
        </div>
    @endif


    <!-- Lista Resumida de Parcelas -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border dark:border-gray-700 p-4">
        <h4 class="text-md font-medium text-gray-800 dark:text-white mb-3">
            Próximas parcelas ({{ $pendingInstallments->count() }} restantes)
        </h4>

        <div class="space-y-2">
            @foreach ($pendingInstallments->take(5) as $installment)
                <div
                    class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-800 dark:text-white">
                            #{{ $installment->installment_number }}
                        </span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y') }}
                        </span>
                    </div>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">
                        € {{ number_format($installment->amount, 2) }}
                    </span>
                </div>
            @endforeach

            @if ($pendingInstallments->count() > 5)
                <div class="pt-2 text-center text-sm text-gray-500 dark:text-gray-400">
                    +{{ $pendingInstallments->count() - 5 }} parcelas não exibidas
                </div>
            @endif
        </div>
    </div>
</div>
