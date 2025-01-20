<?php

namespace Hans\Valravn\Commands\Services;

use Hans\Valravn\Commands\Services\Contracts\CommandsService;
use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;

class RepositoryService extends CommandsService
{
    public function __construct(string $namespace, string $name)
    {
        parent::__construct($namespace, $name, '1');
    }

    /**
     * @throws FilesystemException
     */
    public function createContract(): int
    {
        $file = "Repositories/Contracts/$this->namespace/I{$this->name}Repository.php";

        $stub = $this->getStub('repositories/repository-contract.stub');
        $stub = Str::replace('{{IREPOSITORY::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{IREPOSITORY::MODEL}}', $this->name, $stub);

        return $this->writeTo($file, $stub);
    }

    /**
     * @throws FilesystemException
     */
    public function createClass(): int
    {
        $file = "Repositories/$this->namespace/{$this->name}Repository.php";

        $stub = $this->getStub('repositories/repository.stub');
        $stub = Str::replace('{{REPOSITORY::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{REPOSITORY::MODEL}}', $this->name, $stub);

        return $this->writeTo($file, $stub);
    }
}
