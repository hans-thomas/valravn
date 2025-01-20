<?php

namespace Hans\Valravn\Commands;

use Hans\Valravn\Commands\Services\ControllerService;
use Hans\Valravn\Commands\Services\ExceptionService;
use Hans\Valravn\Commands\Services\MigrationService;
use Hans\Valravn\Commands\Services\ModelService;
use Hans\Valravn\Commands\Services\PolicyService;
use Hans\Valravn\Commands\Services\RepositoryService;
use Hans\Valravn\Commands\Services\RequestService;
use Hans\Valravn\Commands\Services\ResourceService;
use Hans\Valravn\Commands\Services\ServicesService;
use Illuminate\Console\Command;
use League\Flysystem\FilesystemException;
use Symfony\Component\Console\Helper\ProgressBar;

class Entity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
		valravn:entity 
		{namespace : Group of the entity}
		{name : Name of the entity}
		{prefix : A unique string to prefix exceptions of the entity}
		{--v=1 : Version of the entity}
		';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the whole needed classes for an entity.';

    /**
     * Execute the console command.
     *
     * @throws FilesystemException
     *
     * @return int
     */
    public function handle(): int
    {
        $namespace = $this->argument('namespace');
        $name = $this->argument('name');
        $prefix = $this->argument('prefix');
        $version = $this->option('v');

        $exceptionService = new ExceptionService($namespace, $name, $prefix);
        $modelService = new ModelService($namespace, $name);
        $migrationService = new MigrationService($namespace, $name);
        $controllerService = new ControllerService($namespace, $name, $version);
        $requestService = new RequestService($namespace, $name, $version);
        $resourceService = new ResourceService($namespace, $name, $version);
        $policyService = new PolicyService($namespace, $name);
        $repositoryService = new RepositoryService($namespace, $name);
        $servicesService = new ServicesService($namespace, $name);

        $this->withProgressBar(6, function (ProgressBar $progress) use (
            $exceptionService,
            $modelService,
            $migrationService,
            $controllerService,
            $requestService,
            $resourceService,
            $policyService,
            $repositoryService,
            $servicesService
        ) {
            $this->newLine();
            if ($exceptionService->createFullForm()) {
                $this->info('Exception class created.');
            } else {
                $this->error('Exception class exists or could not be created.');
            }
            $progress->advance();

            $this->newLine();
            if (collect([
                $modelService->createModel(), $migrationService->createSeeder(), $migrationService->createFactory(),
                $migrationService->createMigration(),
            ])->every(fn ($item) => $item == true)) {
                $this->info('Model and database classes created.');
            } else {
                $this->error('Some of model or database classes are exist or could not be created.');
            }
            $progress->advance();

            $this->newLine();
            if (collect([
                $controllerService->createCrud(), $controllerService->CreateActions(),
                $controllerService->CreateRelations(), $requestService->createStoreRequest(),
                $requestService->createUpdateRequest(), $requestService->createBatchUpdateRequest(),
                $resourceService->createResource(), $resourceService->createCollection(),
            ])->every(fn ($item) => $item == true)) {
                $this->info('Controllers, requests and resources classes created.');
            } else {
                $this->error('Some of controllers or requests or resources classes are exist or could not be created.');
            }
            $progress->advance();

            $this->newLine();
            if ($policyService->createPolicy()) {
                $this->info('Policy class created.');
            } else {
                $this->error('Policy class exists or could not be created.');
            }
            $progress->advance();

            $this->newLine();
            if (collect([$repositoryService->createContract(), $repositoryService->createClass()])
                ->every(fn ($item) => $item == true)) {
                $this->info('Repository classes created.');
            } else {
                $this->error('Repository classes are exist or could not be created.');
            }
            $progress->advance();

            $this->newLine();
            if (collect([
                $servicesService->createCrud(), $servicesService->CreateActions(), $servicesService->createRelations(),
            ])->every(fn ($item) => $item == true)) {
                $this->info('Service classes created.');
            } else {
                $this->error('Service classes are exist or could not be created.');
            }
            $progress->advance();
        });

        return self::SUCCESS;
    }
}
