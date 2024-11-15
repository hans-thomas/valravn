<?php

namespace Hans\Valravn;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Throwable;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
		valravn:install
		';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Valravn resources.';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Throwable
     */
    public function handle(): void
    {
        $this->comment('Publishing config file...');
        $this->callSilent('vendor:publish', ['--tag' => 'valravn-config']);

        $this->comment('Publishing RepositoryServiceProvider...');
        $this->callSilent('vendor:publish', ['--tag' => 'valravn-provider']);

        $this->comment('Registering RepositoryServiceProvider...');
        $this->registerRepositoryServiceProvider();

        $this->info('Valravn scaffolding installed successfully.');
    }

    /**
     * Register the RepositoryServiceProvider in the application configuration file.
     *
     * @return void
     */
    protected function registerRepositoryServiceProvider(): void
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $providersConfig = file_get_contents(base_path('bootstrap/providers.php'));

        if (Str::contains($providersConfig, $namespace.'\\Providers\\RepositoryServiceProvider::class')) {
            return;
        }

        file_put_contents(base_path('bootstrap/providers.php'), str_replace(
            "{$namespace}\\Providers\AppServiceProvider::class,".PHP_EOL,
            "{$namespace}\\Providers\AppServiceProvider::class,".PHP_EOL."    {$namespace}\Providers\RepositoryServiceProvider::class,".PHP_EOL,
            $providersConfig
        ));

        file_put_contents(app_path('Providers/RepositoryServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/RepositoryServiceProvider.php'))
        ));
    }
}