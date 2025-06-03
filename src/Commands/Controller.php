<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\ControllerService;
use Hans\Valravn\Commands\Services\RequestService;
use Hans\Valravn\Commands\Services\ResourceService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Throwable;

class Controller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        valravn:controller
		{namespace : Group of the entity}
		{name : Name of the entity}
		{--v=1 : Version of the entity}
		{--r|relations : Generate an extra controller for relations management}
		{--a|actions : Generate an extra controller for actions management}
		{--e|requests : Generate store and update request classes}
		{--s|resources : Generate resource and resource collection classes}
		     ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate controller classes.';

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

        $service = new ControllerService($namespace, $name, $version);

        $this->withProgressBar(5, function (ProgressBar $progressBar) use ($service, $namespace, $name, $version) {
            $this->newLine();
            if ($service->createCrud()) {
                $this->info('Controller class created.');
            } else {
                $this->error('Controller class exists or could not be created.');
            }

            $progressBar->advance();
            $this->newLine();

            if ($this->option('relations') || $this->confirm('Should create relations?')) {
                if ($service->CreateRelations()) {
                    $this->info('Relations class created.');
                } else {
                    $this->error('Relations class exists or could not be created.');
                }
            }
            $progressBar->advance();
            $this->newLine();

            if ($this->option('actions') || $this->confirm('Should create actions?')) {
                if ($service->CreateActions()) {
                    $this->info('Actions class created.');
                } else {
                    $this->error('Actions class exists or could not be created.');
                }
            }
            $progressBar->advance();
            $this->newLine();

            if ($this->option('requests') || $this->confirm('Should create requests?')) {
                $requestService = new RequestService(
                    $this->argument('namespace'),
                    $this->argument('name'),
                    $this->option('v')
                );
                if (collect([
                    $requestService->createStoreRequest(),
                    $requestService->createUpdateRequest(),
                    $requestService->createBatchUpdateRequest(),
                ])->every(fn($item) => $item === true)) {
                    $this->info('Request classes created.');
                } else {
                    $this->error('Some request classes are exist or could not be created.');
                }
            }
            $progressBar->advance();
            $this->newLine();

            if ($this->option('resources') || $this->confirm('Should create resources?')) {
                $resourceService = new ResourceService($namespace, $name, $version);
                if (collect([
                    $resourceService->createResource(),
                    $resourceService->createCollection(),
                ])->every(fn($item) => $item === true)) {
                    $this->info('Resource classes created.');
                } else {
                    $this->error('Some resource classes are exist or could not be created.');
                }
            }
            $progressBar->advance();
            $this->newLine();
        });

        return self::SUCCESS;
    }
}
