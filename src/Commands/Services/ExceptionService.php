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
    public function createFullForm(): self
    {
        $stub = $this->getStub("exceptions/fullFormException.stub");
        $stub = Str::replace('{{ENTITY::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{ENTITY::NAME}}', $this->name, $stub);
        $stub = Str::replace('{{ENTITY::CODE}}', $this->prefix, $stub);

        $this->writeTo($this->name, $stub);

        return $this;
    }

    /**
     * @throws FilesystemException
     */
    public function createCompactForm(string $name): self
    {
        $compactName = ucfirst($name);
        if (str_ends_with($compactName, 'Exception')) {
            $compactName = substr($compactName, 0, strlen($compactName) - strlen('Exception'));
        }

        $stub = $this->getStub("exceptions/compactFormException.stub");
        $stub = Str::replace('{{ENTITY::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{ENTITY::NAME}}', $compactName, $stub);
        $stub = Str::replace('{{ENTITY::CODE}}', $this->prefix, $stub);

        $this->writeTo($compactName, $stub);

        return $this;
    }

    /**
     * @throws FilesystemException
     */
    private function writeTo(string $fileName, string $content): void
    {
        $path = "Exceptions/$this->namespace/$this->name/{$fileName}Exception.php";
        $this->filesystem->write($path, $content);
    }
}