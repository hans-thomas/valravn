<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\MigrationService;
use Hans\Valravn\Commands\Services\ModelService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Helper\ProgressBar;
use Throwable;

class Model extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
		valravn:model 
		{namespace : Group of the entity}
		{name : Name of the entity}
		{--f|factory : Generate database factory} 
		{--s|seeder : Generate database seeder} 
		{--m|migration : Generate migration file}
		';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate model class.';

    /**
     * Execute the console command.
     *
     * @throws Throwable
     *
     * @return void
     */
    public function handle()
    {
        $service = new ModelService($this->argument('namespace'), $this->argument('name'));
        $migrationService = new MigrationService($this->argument('namespace'), $this->argument('name'));

        $this->withProgressBar(4, function (ProgressBar $progressBar) use ($service, $migrationService) {
            if ($service->createModel()) {
                $this->info('Model class created.');
            } else {
                $this->error('Model class exists or could not be created.');
            }
            $progressBar->advance();

            if ($this->option('factory') || $this->confirm('Should create factory?')) {
                if ($migrationService->createFactory()) {
                    $this->info('Factory class created.');
                } else {
                    $this->error('Factory class exists or could not be created.');
                }
            }
            $progressBar->advance();

            if ($this->option('seeder') || $this->confirm('Should create seeder?')) {
                if ($migrationService->createSeeder()) {
                    $this->info('Seeder class created.');
                } else {
                    $this->error('Seeder class exists or could not be created.');
                }
            }
            $progressBar->advance();

            if ($this->option('migration') || $this->confirm('Should create migration?')) {
                Artisan::call(
                    'valravn:migration',
                    ['namespace' => $this->argument('namespace'), 'name' => $this->argument('name')]
                );
            }
            $progressBar->advance();
        });
    }
}
