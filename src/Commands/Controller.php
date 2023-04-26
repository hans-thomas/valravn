<?php

    namespace Hans\Valravn\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Contracts\Filesystem\Filesystem;
    use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use League\Flysystem\Visibility;
    use Throwable;

    class Controller extends Command {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'valravn:controller {name} {--namespace=} {--v=v1} {--r|relations} {--a|actions} {--re|requests} {--res|resources}';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'creates controller classes.';
        private Filesystem $fs;

        public function __construct() {
            parent::__construct();
            $this->fs = Storage::createLocalDriver( [
                'root'       => __DIR__,
                'visibility' => Visibility::PUBLIC
            ] );
        }

        /**
         * Execute the console command.
         *
         * @return void
         * @throws Throwable
         */
        public function handle() {
            $singular  = ucfirst( Str::singular( $this->argument( 'name' ) ) );
            $namespace = ucfirst( $this->option( 'namespace' ) );
            $version   = ucfirst( $this->option( 'v' ) );

            if ( ! $namespace ) {
                $this->error( 'namespace is required!' );

                return;
            }

            // controllers: crud
            $errorCodeStub = $this->fs->read( 'stubs/controllers/crud.stub' );
            $errorCodeStub = Str::replace( '{{CRUD::VERSION}}', $version, $errorCodeStub );
            $errorCodeStub = Str::replace( '{{CRUD::NAMESPACE}}', $namespace, $errorCodeStub );
            $errorCodeStub = Str::replace( '{{CRUD::MODEL}}', $singular, $errorCodeStub );
            $errorCodeStub = Str::replace( '{{CRUD::MODEL-lower}}', strtolower( $singular ), $errorCodeStub );
            $errorCode     = "app/Http/Controllers/$version/$namespace/$singular/{$singular}CrudController.php";

            $this->fs->write( $errorCode, $errorCodeStub );
            // controllers: relations
            if ( $this->option( 'relations' ) ) {
                Artisan::call( "make:controller $version/$namespace/$singular/{$singular}RelationsController" );
            }
            // controllers: actions
            if ( $this->option( 'actions' ) ) {
                Artisan::call( "make:controller $version/$namespace/$singular/{$singular}ActionsController" );
            }

            if ( $this->option( "requests" ) ) {
                Artisan::call( "valravn:requests $singular --namespace $namespace --v $version" );
            }

            if ( $this->option( "resources" ) ) {
                Artisan::call( "valravn:resources $singular --namespace $namespace --v $version" );
            }

            $this->info( "controller classes successfully created!" );
        }

    }
