<?php

    namespace Hans\Valravn\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Contracts\Filesystem\Filesystem;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use League\Flysystem\Visibility;
    use Throwable;

    class Repository extends Command {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'valravn:repository {name} {--namespace=}';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'creates repository classes.';

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
            $namespace = ucfirst( $this->option( 'namespace' ) );

            if ( ! $namespace ) {
                $this->error( 'namespace is required!' );

                return;
            }

            // repository contract
            $iRepositoryStub = $this->fs->read( "app/Repositories/stubs/irepository.stub" );
            $iRepositoryStub = Str::replace( "{{IREPOSITORY::NAMESPACE}}", $namespace, $iRepositoryStub );
            $iRepositoryStub = Str::replace( "{{IREPOSITORY::MODEL}}", $singular, $iRepositoryStub );
            $this->fs->write( "app/Repositories/Contracts/$namespace/I{$singular}Repository.php", $iRepositoryStub );

            // repository class
            $repositoryStub = $this->fs->read( "app/Repositories/stubs/repository.stub" );
            $repositoryStub = Str::replace( "{{REPOSITORY::NAMESPACE}}", $namespace, $repositoryStub );
            $repositoryStub = Str::replace( "{{REPOSITORY::MODEL}}", $singular, $repositoryStub );
            $this->fs->write( "app/Repositories/$namespace/{$singular}Repository.php", $repositoryStub );

            $this->info( "repository classes successfully created!" );
        }

    }
