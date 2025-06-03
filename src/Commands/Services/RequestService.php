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
        $file = "Http/Requests/$this->version/$this->namespace/$this->name/{$this->name}StoreRequest.php";
        $stub = $this->requestStub();
        $stub = Str::replace('{{REQUEST::ACTION}}', 'Store', $stub);

        return $this->writeTo($file, $stub);
    }

    /**
     * @throws FilesystemException
     */
    public function createUpdateRequest(): bool
    {
        $file = "Http/Requests/$this->version/$this->namespace/$this->name/{$this->name}UpdateRequest.php";
        $stub = $this->requestStub();
        $stub = Str::replace('{{REQUEST::ACTION}}', 'Update', $stub);

        return $this->writeTo($file, $stub);
    }

    /**
     * @throws FilesystemException
     */
    public function createBatchUpdateRequest(): bool
    {
        $file = "Http/Requests/$this->version/$this->namespace/$this->name/{$this->name}BatchUpdateRequest.php";
        $stub = $this->getStub('requests/batch-update.stub');
        $stub = Str::replace('{{REQUEST::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{REQUEST::MODEL}}', $this->name, $stub);
        $stub = Str::replace('{{REQUEST::VERSION}}', $this->version, $stub);

        return $this->writeTo($file, $stub);
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
}
