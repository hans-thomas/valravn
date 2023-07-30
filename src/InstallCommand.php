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
         * @throws Throwable
         *
         * @return void
         */
        public function handle()
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

            $appConfig = file_get_contents(config_path('app.php'));

            if (Str::contains($appConfig, $namespace.'\\Providers\\RepositoryServiceProvider::class')) {
                return;
            }

            file_put_contents(config_path('app.php'), str_replace(
                "{$namespace}\\Providers\RouteServiceProvider::class,".PHP_EOL,
                "{$namespace}\\Providers\RouteServiceProvider::class,".PHP_EOL."        {$namespace}\Providers\RepositoryServiceProvider::class,".PHP_EOL,
                $appConfig
            ));

            file_put_contents(app_path('Providers/RepositoryServiceProvider.php'), str_replace(
                "namespace App\Providers;",
                "namespace {$namespace}\Providers;",
                file_get_contents(app_path('Providers/RepositoryServiceProvider.php'))
            ));
        }
    }
