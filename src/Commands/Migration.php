<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\MigrationService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Throwable;

class Migration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
		valravn:migration 
		{namespace : Group of the entity}
		{name : Name of the entity}
		';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate migration file.';

    /**
     * Execute the console command.
     *
     * @throws Throwable
     *
     * @return void
     */
    public function handle()
    {
        $this->withProgressBar(1, function (ProgressBar $progress) {
            $service = new MigrationService($this->argument('namespace'), $this->argument('name'));

            if ($service->createMigration()) {
                $this->info('Migration file created.');
            } else {
                $this->error('Migration file exists or could not be created.');
            }
            $progress->advance();
        });
    }
}
