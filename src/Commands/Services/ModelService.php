<?php

namespace Hans\Valravn\Commands\Services;

use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;

final class ModelService extends Contracts\CommandsService
{
    private readonly string $plural;

    public function __construct(string $namespace, string $name)
    {
        parent::__construct($namespace, $name, '1');

        $this->plural = Str::of($this->name)->plural()->snake()->toString();
    }

    /**
     * @throws FilesystemException
     */
    public function createModel(): bool
    {
        if ($this->filesystem->exists("Models/$this->namespace/$this->name.php")) {
            return false;
        }
        $table = strtolower("{$this->namespace}_$this->plural");

        $stub = $this->getStub('models/model.stub');
        $stub = Str::replace('{{MODEL::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{MODEL::CLASS}}', $this->name, $stub);
        $stub = Str::replace('{{MODEL::TABLE}}', $table, $stub);
        $stub = Str::replace('{{MODEL::FOREIGNKEY}}', Str::singular($table).'_id', $stub);

        $this->filesystem->write("Models/$this->namespace/$this->name.php", $stub);

        return true;
    }
}
