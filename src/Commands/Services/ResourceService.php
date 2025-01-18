<?php

namespace Hans\Valravn\Commands\Services;

use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;

class ResourceService extends CommandsService
{
    /**
     * @throws FilesystemException
     */
    public function createResource(): self
    {
        $stub = $this->getStub('resources/resource.stub');
        $plural = Str::of($this->name)->plural()->snake()->lower()->toString();

        $stub = Str::replace('{{RESOURCE::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{RESOURCE::MODEL}}', $this->name, $stub);
        $stub = Str::replace('{{RESOURCE::PLURAL}}', $plural, $stub);
        $stub = Str::replace('{{RESOURCE::VERSION}}', $this->version, $stub);

        $this->filesystem->write(
            "Http/Resources/$this->version/$this->namespace/$this->name/{$this->name}Resource.php",
            $stub
        );

        return $this;
    }

    /**
     * @throws FilesystemException
     */
    public function createCollection(): self
    {
        $stub = $this->getStub('resources/collection.stub');
        $plural = Str::of($this->name)->plural()->snake()->lower()->toString();

        $stub = Str::replace('{{COLLECTION::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{COLLECTION::MODEL}}', $this->name, $stub);
        $stub = Str::replace('{{COLLECTION::PLURAL}}', $plural, $stub);
        $stub = Str::replace('{{COLLECTION::VERSION}}', $this->version, $stub);

        $this->filesystem->write(
            "Http/Resources/$this->version/$this->namespace/$this->name/{$this->name}Collection.php",
            $stub
        );

        return $this;
    }
}
