<?php

namespace Hans\Valravn\Commands\Services;

use Hans\Valravn\Commands\Services\Contracts\CommandsService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;

final class ControllerService extends CommandsService
{
    /**
     * @throws FilesystemException
     */
    public function createCrud(): self
    {
        $controllerStub = $this->getStub('controllers/crud.stub');
        $controllerStub = Str::replace('{{CRUD::NAMESPACE}}', $this->namespace, $controllerStub);
        $controllerStub = Str::replace('{{CRUD::MODEL}}', $this->name, $controllerStub);
        $controllerStub = Str::replace('{{CRUD::VERSION}}', $this->version, $controllerStub);
        $controllerStub = Str::replace('{{CRUD::MODEL-lower}}', strtolower($this->name), $controllerStub);
        $destination = "Http/Controllers/$this->version/$this->namespace/$this->name/{$this->name}CrudController.php";

        $this->filesystem->write($destination, $controllerStub);

        return $this;
    }

    public function CreateRelations(): self
    {
        Artisan::call("make:controller $this->version/$this->namespace/$this->name/{$this->name}RelationsController");

        return $this;
    }

    public function CreateActions(): self
    {
        Artisan::call("make:controller $this->version/$this->namespace/$this->name/{$this->name}ActionsController");

        return $this;
    }
}
