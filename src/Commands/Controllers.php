<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\ControllerService;
use Hans\Valravn\Commands\Services\RequestService;
use Hans\Valravn\Commands\Services\ResourceService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Throwable;

class Controllers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
		valravn:controllers 
		{namespace : Group of the entity}
		{name : Name of the entity}
		{--v=1 : Version of the entity}
		{--requests : Generate store and update request classes}
		{--resources : Generate resource and resource collection classes}
		';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all controller classes.';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Throwable
     *
     */
    public function handle(): int
    {
        $namespace = $this->argument('namespace');
        $name = $this->argument('name');
        $version = $this->option('v');

        $this->withProgressBar(3, function (ProgressBar $progressBar) use ($namespace, $name, $version) {
            $service = new ControllerService($namespace, $name, $version);
            $service->createCrud();
            $service->CreateActions();
            $service->CreateRelations();

            $this->info('Controller classes created.');
            $progressBar->advance();

            if ($this->option('requests') || $this->confirm('Should create requests?')) {
                $requestService = new RequestService($namespace, $name, $version);
                $requestService->createStoreRequest();
                $requestService->createUpdateRequest();
                $requestService->createBatchUpdateRequest();

                $this->info('Request classes created.');
            }
            $progressBar->advance();

            if ($this->option('resources') || $this->confirm('Should create resources?')) {
                $resourceService = new ResourceService($namespace, $name, $version);
                $resourceService->createResource();
                $resourceService->createCollection();

                $this->info('Resource and ResourceCollection classes created.');
            }
            $progressBar->advance();
        });

        return self::SUCCESS;
    }
}
