<?php

namespace Hans\Valravn\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Contracts\Filesystem\Filesystem;
    use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use League\Flysystem\Visibility;
    use Throwable;

    class Controller extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = '
        valravn:controller
		{namespace : Group of the entity}
		{name : Name of the entity}
		{--v=1 : Version of the entity}
		{--r|relations : Generate an extra controller for relations management}
		{--a|actions : Generate an extra controller for actions management}
		{--requests : Generate store and update request classes}
		{--resources : Generate resource and resource collection classes}
		     ';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Generate controller classes.';
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

            // controllers: crud
            $controllerStub = file_get_contents(__DIR__.'/stubs/controllers/crud.stub');
            $controllerStub = Str::replace('{{CRUD::VERSION}}', $version, $controllerStub);
            $controllerStub = Str::replace('{{CRUD::NAMESPACE}}', $namespace, $controllerStub);
            $controllerStub = Str::replace('{{CRUD::MODEL}}', $singular, $controllerStub);
            $controllerStub = Str::replace('{{CRUD::MODEL-lower}}', strtolower($singular), $controllerStub);
            $destination = "Http/Controllers/$version/$namespace/$singular/{$singular}CrudController.php";

            $this->fs->write($destination, $controllerStub);
            // controllers: relations
            if ($this->option('relations')) {
                Artisan::call("make:controller $version/$namespace/$singular/{$singular}RelationsController");
            }
            // controllers: actions
            if ($this->option('actions')) {
                Artisan::call("make:controller $version/$namespace/$singular/{$singular}ActionsController");
            }

            if ($this->option('requests')) {
                Artisan::call("valravn:requests $namespace $singular --v $version --batch-update");
            }

            if ($this->option('resources')) {
                Artisan::call("valravn:resources $namespace $singular --v $version");
            }

            $this->info('controller classes successfully created!');
        }
    }
