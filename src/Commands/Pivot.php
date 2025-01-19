<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\MigrationService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Throwable;

class Pivot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
		valravn:pivot 
		{namespace : Group of the entity}
		{name : Name of the entity}
		{related-namespace : Group of the related entity}
		{related-name : Name of the related entity}
		';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate pivot migration file for many-to-many relationships.';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Throwable
     *
     */
    public function handle()
    {
        $service = new MigrationService($this->argument('namespace'), $this->argument('name'));

        $this->withProgressBar(1, function (ProgressBar $progress) use ($service) {
            if ($service->createPivot($this->argument('related-namespace'), $this->argument('related-name'))) {
                $this->info('Pivot migration file created.');
            } else {
                $this->error('Pivot migration file exists or could not be created.');
            }
            $progress->advance();
        });
    }
}
