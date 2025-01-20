<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\ServicesService;
use Illuminate\Console\Command;
use League\Flysystem\FilesystemException;
use Symfony\Component\Console\Helper\ProgressBar;

class Service extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
		valravn:service 
		{namespace : Group of the entity}
		{name : Name of the entity}
		{--r|relations : Generate an extra service to manage relations }
		{--a|actions : Generate an extra service to manage actions}
		';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate service classes.';

    /**
     * Execute the console command.
     *
     * @throws FilesystemException
     *
     * @return int
     */
    public function handle(): int
    {
        $service = new ServicesService($this->argument('namespace'), $this->argument('name'));

        $this->withProgressBar(3, function (ProgressBar $progress) use ($service) {
            $this->newLine();
            if ($service->createCrud()) {
                $this->info('CRUD service created.');
            } else {
                $this->error('CRUD service exists or could be created.');
            }
            $progress->advance();
            $this->newLine();

            if ($this->option('relations')) {
                if ($service->createRelations()) {
                    $this->info('Relations service created.');
                } else {
                    $this->error('Relations service exists or could be created.');
                }
            }
            $progress->advance();
            $this->newLine();

            if ($this->option('actions')) {
                if ($service->createActions()) {
                    $this->info('Actions service created.');
                } else {
                    $this->error('Actions service exists or could be created.');
                }
            }
            $progress->advance();
            $this->newLine();
        });

        return self::SUCCESS;
    }
}
