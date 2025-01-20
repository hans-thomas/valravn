<?php

namespace Hans\Valravn\Commands\Services;

use Hans\Valravn\Commands\Services\Contracts\CommandsService;
use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;

class ServicesService extends CommandsService
{
    public function __construct(string $namespace, string $name)
    {
        parent::__construct($namespace, $name, '1');
    }

    public static function make(string $namespace, string $name, string $version = '1'): static
    {
        return parent::make($namespace, $name, $version);
    }

    /**
     * @throws FilesystemException
     */
    public function createCrud(): bool
    {
        $file = "Services/$this->namespace/$this->name/{$this->name}CrudService.php";
        $stub = $this->getStub('services/crud.stub');
        $stub = Str::replace('{{CRUD-SERVICE::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{CRUD-SERVICE::MODEL}}', $this->name, $stub);

        return $this->writeTo($file, $stub);
    }

    /**
     * @throws FilesystemException
     */
    public function createRelations(): bool
    {
        $file = "Services/$this->namespace/$this->name/{$this->name}RelationsService.php";
        $stub = $this->getStub('services/custom.stub');
        $stub = Str::replace('{{CRUD-SERVICE::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{CRUD-SERVICE::MODEL}}', $this->name, $stub);
        $stub = Str::replace('{{CRUD-SERVICE::ACTION}}', 'Relations', $stub);

        return $this->writeTo($file, $stub);
    }

    /**
     * @throws FilesystemException
     */
    public function createActions(): bool
    {
        $file = "Services/$this->namespace/$this->name/{$this->name}ActionsService.php";
        $stub = $this->getStub('services/custom.stub');
        $stub = Str::replace('{{CRUD-SERVICE::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{CRUD-SERVICE::MODEL}}', $this->name, $stub);
        $stub = Str::replace('{{CRUD-SERVICE::ACTION}}', 'Actions', $stub);

        return $this->writeTo($file, $stub);
    }
}