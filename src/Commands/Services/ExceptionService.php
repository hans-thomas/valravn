<?php

namespace Hans\Valravn\Commands\Services;

use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;

final class ExceptionService extends Contracts\CommandsService
{
    private readonly string $prefix;

    public function __construct(string $namespace, string $name, string $prefix)
    {
        parent::__construct($namespace, $name, '1');

        $this->prefix = ucfirst($prefix);
    }

    /**
     * @throws FilesystemException
     */
    public function createFullForm(): bool
    {
        $file = "Exceptions/$this->namespace/$this->name/{$this->name}Exception.php";
        $stub = $this->getStub("exceptions/fullFormException.stub");
        $stub = Str::replace('{{ENTITY::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{ENTITY::NAME}}', $this->name, $stub);
        $stub = Str::replace('{{ENTITY::CODE}}', $this->prefix, $stub);

        return $this->writeTo($file, $stub);
    }

    /**
     * @throws FilesystemException
     */
    public function createCompactForm(string $name): bool
    {
        $compactName = ucfirst($name);
        if (str_ends_with($compactName, 'Exception')) {
            $compactName = substr($compactName, 0, strlen($compactName) - strlen('Exception'));
        }
        $file = "Exceptions/$this->namespace/$this->name/{$compactName}Exception.php";

        $stub = $this->getStub("exceptions/compactFormException.stub");
        $stub = Str::replace('{{ENTITY::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{ENTITY::NAME}}', $compactName, $stub);
        $stub = Str::replace('{{ENTITY::CODE}}', $this->prefix, $stub);

        return $this->writeTo($file, $stub);
    }
}