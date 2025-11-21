<?php

namespace App\Modules\Notifications;

class NotificationTriggerRegistry
{
    public static function all(): array
    {
        return [
            \App\Modules\BankManager\Notifications\Triggers\CheckExpensesTrigger::class,
        ];
    }
}
