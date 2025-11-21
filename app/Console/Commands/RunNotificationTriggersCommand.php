<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Modules\Notifications\NotificationTriggerRegistry;

use App\Modules\Notifications\Services\NotificationService;

class RunNotificationTriggersCommand extends Command
{
    protected $signature = 'notifications:run';
    protected $description = 'Executa todos os triggers de notificações dos módulos';

    public function handle()
    {
        $this->info('Executando triggers de notificações...');

        foreach (NotificationTriggerRegistry::all() as $triggerClass) {
            $trigger = new $triggerClass();
            $this->line('- ' . $triggerClass);

            if ($trigger->shouldTrigger()) {
                $trigger->run(new NotificationService());
            }
        }

        $this->info('Concluído.');
    }
}
