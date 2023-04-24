<?php

    namespace Hans\Valravn\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Contracts\Filesystem\Filesystem;
    use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use League\Flysystem\Visibility;
    use Throwable;

    class Model extends Command {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'valravn:model {name} {--namespace=} {--f|factory}} {--s|seeder}} {--m|migration}';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'creates model class.';

        private Filesystem $fs;

        public function __construct() {
            parent::__construct();
            $this->fs = Storage::createLocalDriver( [
                'root'       => base_path(),
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
            $plural    = ucfirst( Str::plural( $this->argument( 'name' ) ) );
            $namespace = ucfirst( $this->option( 'namespace' ) );

            if ( ! $namespace ) {
                $this->error( 'namespace is required!' );

                return;
            }

            $modelStub = $this->fs->read( 'app/Models/stubs/model.stub' );
            $modelStub = Str::replace( "{{MODEL::NAMESPACE}}", $namespace, $modelStub );
            $modelStub = Str::replace( "{{MODEL::CLASS}}", $singular, $modelStub );
            $modelStub = Str::replace( "{{MODEL::TABLE}}",
                $table = strtolower( "{$namespace}_" . Str::snake( $plural ) ),
                $modelStub );
            $modelStub = Str::replace( "{{MODEL::FOREIGNKEY}}", Str::singular( $table ) . '_id', $modelStub );
            $this->fs->write( "app/Models/$namespace/$singular.php", $modelStub );

            if ( $this->option( 'factory' ) ) {
                Artisan::call( "make:factory $namespace/{$singular}Factory --model $namespace/$singular" );
            }

            if ( $this->option( 'seeder' ) ) {
                Artisan::call( "make:seeder $namespace/{$singular}Seeder" );
            }

            if ( $this->option( 'migration' ) ) {
                Artisan::call( "valravn:migration $singular --namespace $namespace" );
            }

            $this->info( "model class successfully created!" );
        }

    }
