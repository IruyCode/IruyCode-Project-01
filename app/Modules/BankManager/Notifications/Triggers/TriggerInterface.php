<?php

namespace App\Modules\BankManager\Notifications\Triggers;

use App\Modules\Notifications\Services\NotificationService;


interface TriggerInterface
{
    public static function label(): string;

    public function shouldTrigger(): bool;

    public function run(NotificationService $service): void;
}
