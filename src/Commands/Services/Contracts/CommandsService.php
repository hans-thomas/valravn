<?php

namespace Hans\Valravn\Commands\Services\Contracts;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;
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
        $this->version = 'V' . filter_var($version, FILTER_SANITIZE_NUMBER_INT);

        $this->filesystem = $this->createFilesystemFromPath(app_path());
    }

    /**
     * @param string $path
     *
     * @return Filesystem
     */
    protected function createFilesystemFromPath(string $path): Filesystem
    {
        return Storage::createLocalDriver([
            'root' => $path,
            'visibility' => Visibility::PUBLIC,
        ]);
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getStub(string $path): string
    {
        return file_get_contents(__DIR__ . "/../../stubs/$path");
    }

    /**
     * @param string $file
     * @param string $stub
     *
     * @return bool
     * @throws FilesystemException
     *
     */
    protected function writeTo(string $file, string $stub): bool
    {
        if ($this->filesystem->exists($file)) {
            return false;
        }

        $this->filesystem->write($file, $stub);

        return true;
    }
}
