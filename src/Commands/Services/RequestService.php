<?php

namespace Hans\Valravn\Commands\Services;

use Hans\Valravn\Commands\Services\Contracts\CommandsService;
use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;

final class RequestService extends CommandsService
{
    /**
     * @throws FilesystemException
     */
    public function createStoreRequest(): bool
    {
        $stub = $this->requestStub();
        $stub = Str::replace('{{REQUEST::ACTION}}', 'Store', $stub);

        return $this->writeTo('StoreRequest', $stub);
    }

    /**
     * @throws FilesystemException
     */
    public function createUpdateRequest(): bool
    {
        $stub = $this->requestStub();
        $stub = Str::replace('{{REQUEST::ACTION}}', 'Update', $stub);

        return $this->writeTo('UpdateRequest', $stub);
    }

    /**
     * @throws FilesystemException
     */
    public function createBatchUpdateRequest(): bool
    {
        $batchUpdate = $this->getStub('requests/batch-update.stub');
        $batchUpdate = Str::replace('{{REQUEST::NAMESPACE}}', $this->namespace, $batchUpdate);
        $batchUpdate = Str::replace('{{REQUEST::MODEL}}', $this->name, $batchUpdate);
        $batchUpdate = Str::replace('{{REQUEST::VERSION}}', $this->version, $batchUpdate);

        return $this->writeTo('BatchUpdateRequest', $batchUpdate);
    }

    /**
     * @return string
     */
    private function requestStub(): string
    {
        $store = $this->getStub('requests/crud.stub');
        $store = Str::replace('{{REQUEST::NAMESPACE}}', $this->namespace, $store);
        $store = Str::replace('{{REQUEST::MODEL}}', $this->name, $store);

        return Str::replace('{{REQUEST::VERSION}}', $this->version, $store);
    }

    /**
     * @throws FilesystemException
     */
    private function writeTo(string $fileName, string $content): bool
    {
        $file = "Http/Requests/$this->version/$this->namespace/$this->name/$this->name$fileName.php";

        if ($this->filesystem->exists($file)) {
            return false;
        }

        $this->filesystem->write(
            $file,
            $content
        );

        return true;
    }
}
