<?php

    namespace Hans\Valravn\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Contracts\Filesystem\Filesystem;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use League\Flysystem\Visibility;
    use Throwable;

    class Migration extends Command {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'valravn:migration {name} {--namespace=}';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'creates migration class.';

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
            $plural    = ucfirst( Str::plural( $this->argument( 'name' ) ) );
            $namespace = ucfirst( $this->option( 'namespace' ) );

            if ( ! $namespace ) {
                $this->error( 'namespace is required!' );

                return;
            }

            $migrationStub = $this->fs->read( 'stubs/migrations/migration.stub' );
            $migrationStub = Str::replace(
				"{{MODEL::NAMESPACE}}", "App\\Models\\$namespace\\$singular",
                $migrationStub
            );
            $migrationStub = Str::replace( "{{MODEL::CLASS}}", $singular, $migrationStub );
            $datePrefix    = now()->format( 'Y_m_d_His' );
            $this->fs->write(
				"database/migrations/$namespace/{$datePrefix}_create_" . Str::snake( $plural ) . "_table.php",
                $migrationStub
            );

            $this->info( "migration class successfully created!" );
        }

    }
