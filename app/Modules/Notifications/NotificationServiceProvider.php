<?php

namespace App\Modules\Notifications;

use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Rotas
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        // Views
        $this->loadViewsFrom(__DIR__ . '/Views', 'notifications');

        // Migrations
        $this->loadMigrationsFrom(database_path('migrations/Notifications'));
    }
}
