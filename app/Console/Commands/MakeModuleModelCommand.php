<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeModuleModelCommand extends Command
{
    protected $signature = 'make:module-model 
        {module : Nome do módulo (ex: BankManager)} 
        {name : Nome do Model (ex: Transaction)} 
        {--m|migration : Criar migration também} 
        {--c|controller : Criar controller também} 
        {--f|factory : Criar factory também}';

    protected $description = 'Cria um Model dentro de um módulo (ex: app/Modules/BankManager/Models)';

    public function handle()
    {
        $module = Str::studly($this->argument('module'));
        $name = Str::studly($this->argument('name'));
        $basePath = app_path("Modules/{$module}/Models");

        // cria pasta se não existir
        if (!is_dir($basePath)) {
            mkdir($basePath, 0777, true);
        }

        $namespace = "App\\Modules\\{$module}\\Models";
        $filePath = "{$basePath}/{$name}.php";

        // evita sobrescrever
        if (file_exists($filePath)) {
            $this->error("O Model {$name} já existe em {$module}.");
            return;
        }

        // conteúdo base do model
        $content = <<<PHP
        <?php

        namespace {$namespace};

        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\Model;

        class {$name} extends Model
        {
            use HasFactory;

            protected \$table = Str::snake('app_{$module}_{$name}s');
            protected \$guarded = [];
        }

        PHP;

        file_put_contents($filePath, $content);
        $this->info("Model criado com sucesso: {$filePath}");

        // Cria arquivos adicionais se solicitado
        if ($this->option('migration')) {
            Artisan::call('make:migration', [
                'name' => "create_".Str::snake($name)."s_table",
                '--path' => "database/migrations/{$module}"
            ]);
            $this->info("Migration criada para {$name}");
        }

        if ($this->option('controller')) {
            Artisan::call('make:controller', [
                'name' => "App\\Modules\\{$module}\\Controllers\\{$name}Controller",
            ]);
            $this->info("Controller criado para {$name}");
        }

        if ($this->option('factory')) {
            Artisan::call('make:factory', [
                'name' => "{$name}Factory",
                '--model' => "{$namespace}\\{$name}",
            ]);
            $this->info("Factory criada para {$name}");
        }
    }
}
