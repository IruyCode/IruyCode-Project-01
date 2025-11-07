<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //** Carrega migrações de todas as subpastas dentro de database/migrations */
        $migrationsPath = database_path('migrations');

        // Adiciona a pasta principal
        $migrationDirs = [$migrationsPath];

        // Busca todas as subpastas, em todos os níveis
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($migrationsPath, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                $migrationDirs[] = $file->getPathname();
            }
        }

        // Carrega todas as pastas encontradas
        foreach ($migrationDirs as $dir) {
            $this->loadMigrationsFrom($dir);
        }


        //** Carrega views de módulos */
        $modulesPath = app_path('Modules');

        if (!is_dir($modulesPath)) {
            return;
        }

        // percorre todos os módulos dentro de app/Modules
        foreach (scandir($modulesPath) as $module) {
            if ($module === '.' || $module === '..') continue;

            $viewsPath = "{$modulesPath}/{$module}/views";

            // se o módulo tiver pasta "views", registra como namespace
            if (is_dir($viewsPath)) {
                $this->loadViewsFrom($viewsPath, strtolower($module));
            }
        }
    }
}
