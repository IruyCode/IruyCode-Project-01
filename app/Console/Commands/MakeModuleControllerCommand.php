<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeModuleControllerCommand extends Command
{
    protected $signature = 'make:module-controller 
        {module : Nome do módulo (ex: BankManager)} 
        {name : Nome do Controller (ex: TransactionController)} 
        {--r|resource : Cria controller no formato resource (index, store, show...)} 
        {--vw|with-view : Cria uma view padrão para o controller}';

    protected $description = 'Cria um Controller dentro de um módulo (ex: app/Modules/BankManager/Controllers)';

    public function handle()
    {
        $module = Str::studly($this->argument('module'));
        $name = Str::studly($this->argument('name'));
        $basePath = app_path("Modules/{$module}/Controllers");

        // Garante que a pasta existe
        if (!is_dir($basePath)) {
            mkdir($basePath, 0777, true);
        }

        $namespace = "App\\Modules\\{$module}\\Controllers";
        $filePath = "{$basePath}/{$name}.php";

        if (file_exists($filePath)) {
            $this->error(" O controller {$name} já existe em {$module}.");
            return;
        }

        $isResource = $this->option('resource');

        if ($isResource) {
            Artisan::call('make:controller', [
                'name' => "{$namespace}\\{$name}",
                '--resource' => true,
            ]);
            $this->info("Controller resource criado: {$filePath}");
        } else {
            $content = <<<PHP
            <?php

            namespace {$namespace};

            use App\Http\Controllers\Controller;
            use Illuminate\Http\Request;

            class {$name} extends Controller
            {
                public function index()
                {
                    return view('modules.{$module}.index');
                }
            }

            PHP;
            file_put_contents($filePath, $content);
            $this->info("Controller criado: {$filePath}");
        }

        // Cria view associada se solicitado
        if ($this->option('with-view')) {
            $viewPath = app_path("Modules/{$module}/views");
            if (!is_dir($viewPath)) {
                mkdir($viewPath, 0777, true);
            }

            $viewFile = "{$viewPath}/index.blade.php";
            if (!file_exists($viewFile)) {
                file_put_contents($viewFile, "<h1>{$module} - {$name}</h1>");
            }

            $this->info("View criada: app/Modules/{$module}/views/index.blade.php");
        }
    }
}
