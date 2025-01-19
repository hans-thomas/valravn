<?php

namespace Hans\Valravn\Commands\Services;

use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;

class MigrationService extends Contracts\CommandsService
{
    private readonly string $plural;

    public function __construct(string $namespace, string $name)
    {
        parent::__construct($namespace, $name, '1');

        $this->plural = Str::of($name)->plural()->ucfirst()->snake()->toString();
        $this->filesystem = $this->createFilesystemFromPath(database_path());
    }

    public static function make(string $namespace, string $name, string $version = '1'): static
    {
        return parent::make($namespace, $name, $version);
    }


    /**
     * @throws FilesystemException
     */
    public function createMigration(): bool
    {
        $stub = $this->getStub('migrations/migration.stub');
        $stub = Str::replace('{{MODEL::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{MODEL::CLASS}}', $this->name, $stub);

        $path = "migrations/$this->namespace";
        $datePrefix = now()->format('Y_m_d_His');
        $fileName = "create_{$this->plural}_table.php";

        foreach ($this->filesystem->allFiles($path) as $file) {
            if (preg_match("/[0-9 _]+_$fileName/s", $file)) {
                return false;
            }
        }

        $this->filesystem->write("$path/{$datePrefix}_$fileName", $stub);

        return true;
    }

    public function createPivot(string $relatedNamespace, string $relatedName): bool
    {
        $relatedNamespace = Str::of($relatedNamespace)->ucfirst()->toString();
        $relatedName = Str::of($relatedName)->singular()->ucfirst()->toString();

        $stub = $this->getStub('migrations/pivot.stub');

        $stub = Str::replace('{{PIVOT::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{PIVOT::MODEL}}', $this->name, $stub);

        $stub = Str::replace('{{PIVOT::RELATED-NAMESPACE}}', $relatedNamespace, $stub);
        $stub = Str::replace('{{PIVOT::RELATED-MODEL}}', $relatedName, $stub);

        // alphabetic sort for pivot table name
        $names = [strtolower($this->name), strtolower($relatedName)];
        sort($names);

        $stub = Str::replace('{{PIVOT::FIRST-MODEL-SINGLE-LOWER}}', $names[0], $stub);
        $stub = Str::replace('{{PIVOT::SECOND-MODEL-SINGLE-LOWER}}', $names[1], $stub);

        $path = "migrations/$this->namespace";
        $datePrefix = now()->format('Y_m_d_His');
        $fileName = "create_{$names[0]}_{$names[1]}_table.php";

        foreach ($this->filesystem->allFiles($path) as $file) {
            if (preg_match("/[0-9 _]+_$fileName/s", $file)) {
                return false;
            }
        }

        $this->filesystem->write("$path/{$datePrefix}_$fileName", $stub);

        return true;
    }
}