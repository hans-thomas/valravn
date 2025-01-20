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
    public function createCrud(): bool
    {
        $file = "Http/Controllers/$this->version/$this->namespace/$this->name/{$this->name}CrudController.php";
        $stub = $this->getStub('controllers/crud.stub');
        $stub = Str::replace('{{CRUD::NAMESPACE}}', $this->namespace, $stub);
        $stub = Str::replace('{{CRUD::MODEL}}', $this->name, $stub);
        $stub = Str::replace('{{CRUD::VERSION}}', $this->version, $stub);
        $stub = Str::replace('{{CRUD::MODEL-lower}}', strtolower($this->name), $stub);

        return $this->writeTo($file, $stub);
    }

    public function CreateRelations(): bool
    {
        $file = "Http/Controllers/$this->version/$this->namespace/$this->name/{$this->name}RelationsController.php";
        if ($this->filesystem->exists($file)) {
            return false;
        }
        Artisan::call("make:controller $this->version/$this->namespace/$this->name/{$this->name}RelationsController");

        return true;
    }

    public function CreateActions(): bool
    {
        $file = "Http/Controllers/$this->version/$this->namespace/$this->name/{$this->name}ActionsController.php";
        if ($this->filesystem->exists($file)) {
            return false;
        }
        Artisan::call("make:controller $this->version/$this->namespace/$this->name/{$this->name}ActionsController");

        return true;
    }
}
