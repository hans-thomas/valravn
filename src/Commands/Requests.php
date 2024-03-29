<?php

namespace Hans\Valravn\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\Visibility;
use Throwable;

class Requests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        valravn:requests 
		{namespace : Group of the entity}
		{name : Name of the entity}
		{--v=1 : Version of the entity}
		{--batch-update : Create batch update request}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate basic request classes.';

    private Filesystem $fs;

    public function __construct()
    {
        parent::__construct();
        $this->fs = Storage::createLocalDriver([
            'root'       => app_path(),
            'visibility' => Visibility::PUBLIC,
        ]);
    }

    /**
     * Execute the console command.
     *
     * @throws Throwable
     *
     * @return void
     */
    public function handle()
    {
        $singular = ucfirst(Str::singular($this->argument('name')));
        $namespace = ucfirst($this->argument('namespace'));
        $version = 'V'.filter_var($this->option('v'), FILTER_SANITIZE_NUMBER_INT);

        // store request
        $store = file_get_contents(__DIR__.'/stubs/requests/crud.stub');
        $store = Str::replace('{{REQUEST::VERSION}}', $version, $store);
        $store = Str::replace('{{REQUEST::NAMESPACE}}', $namespace, $store);
        $store = Str::replace('{{REQUEST::MODEL}}', $singular, $store);
        $store = Str::replace('{{REQUEST::ACTION}}', 'Store', $store);
        $this->fs->write(
            "Http/Requests/$version/$namespace/$singular/{$singular}StoreRequest.php",
            $store
        );

        // update request
        $update = file_get_contents(__DIR__.'/stubs/requests/crud.stub');
        $update = Str::replace('{{REQUEST::VERSION}}', $version, $update);
        $update = Str::replace('{{REQUEST::NAMESPACE}}', $namespace, $update);
        $update = Str::replace('{{REQUEST::MODEL}}', $singular, $update);
        $update = Str::replace('{{REQUEST::ACTION}}', 'Update', $update);
        $this->fs->write(
            "Http/Requests/$version/$namespace/$singular/{$singular}UpdateRequest.php",
            $update
        );

        if ($this->option('batch-update')) {
            $batchUpdate = file_get_contents(__DIR__.'/stubs/requests/batch-update.stub');
            $batchUpdate = Str::replace('{{REQUEST::VERSION}}', $version, $batchUpdate);
            $batchUpdate = Str::replace('{{REQUEST::NAMESPACE}}', $namespace, $batchUpdate);
            $batchUpdate = Str::replace('{{REQUEST::MODEL}}', $singular, $batchUpdate);
            $this->fs->write(
                "Http/Requests/$version/$namespace/$singular/{$singular}BatchUpdateRequest.php",
                $batchUpdate
            );
        }

        $this->info('request classes successfully created!');
    }
}
