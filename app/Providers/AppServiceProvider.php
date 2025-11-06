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
    }
}
