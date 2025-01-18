<?php

namespace Hans\Valravn\Commands\Services;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\Visibility;

abstract class CommandsService
{
    /** @var FilesystemAdapter */
    protected Filesystem $filesystem;
    protected readonly string $namespace;
    protected readonly string $name;
    protected readonly string $version;

    public function __construct(string $namespace, string $name, string $version)
    {
        $this->namespace = ucfirst($namespace);
        $this->name = Str::of($name)->singular()->ucfirst()->toString();
        $this->version = 'V'.filter_var($version, FILTER_SANITIZE_NUMBER_INT);

        $this->filesystem = Storage::createLocalDriver([
            'root'       => app_path(),
            'visibility' => Visibility::PUBLIC,
        ]);
    }

    public static function make(string $namespace, string $name, string $version): static
    {
        return new static($namespace, $name, $version);
    }

    protected function getStub(string $path): string
    {
        return file_get_contents(__DIR__."/../stubs/$path");
    }
}
