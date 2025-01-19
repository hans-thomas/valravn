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
    public function createStoreRequest(): self
    {
        $stub = $this->requestStub();
        $stub = Str::replace('{{REQUEST::ACTION}}', 'Store', $stub);

        $this->writeTo('StoreRequest', $stub);

        return $this;
    }

    /**
     * @throws FilesystemException
     */
    public function createUpdateRequest(): self
    {
        $stub = $this->requestStub();
        $stub = Str::replace('{{REQUEST::ACTION}}', 'Update', $stub);

        $this->writeTo('UpdateRequest', $stub);

        return $this;
    }

    /**
     * @throws FilesystemException
     */
    public function createBatchUpdateRequest(): self
    {
        $batchUpdate = $this->getStub('requests/batch-update.stub');
        $batchUpdate = Str::replace('{{REQUEST::NAMESPACE}}', $this->namespace, $batchUpdate);
        $batchUpdate = Str::replace('{{REQUEST::MODEL}}', $this->name, $batchUpdate);
        $batchUpdate = Str::replace('{{REQUEST::VERSION}}', $this->version, $batchUpdate);

        $this->writeTo('BatchUpdateRequest', $batchUpdate);

        return $this;
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
    private function writeTo(string $fileName, string $content): void
    {
        $this->filesystem->write(
            "Http/Requests/$this->version/$this->namespace/$this->name/$this->name$fileName.php",
            $content
        );
    }
}
