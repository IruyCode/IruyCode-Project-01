<?php

namespace App\Modules\BankManager\Notifications\Triggers;

use App\Modules\Notifications\Services\NotificationService;
use App\Modules\BankManager\Models\Transaction;

class CheckExpensesTrigger implements TriggerInterface
{
    public static function label(): string
    {
        return 'missing_expenses';
    }

    public function shouldTrigger(): bool
    {
        $last = Transaction::latest()->first();
        return !$last || $last->created_at->diffInDays(now()) >= 3;
    }

    public function run(NotificationService $service): void
    {
        $service->notifyOnce([
            'module'  => 'bank-manager',
            'title'   => 'Você registrou suas últimas despesas?',
            'message' => 'Você não registra despesas há mais de 3 dias.',
            'type'    => 'warning',
            'context' => 'missing_expenses',
        ]);
    }
}
