<?php

    namespace Hans\Valravn\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Contracts\Filesystem\Filesystem;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use League\Flysystem\Visibility;
    use Throwable;

    class Resources extends Command {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'valravn:resources {name} {--namespace=} {--v=v1}';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'creates resource and collection classes.';

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
            $plural    = Str::of( $this->argument( 'name' ) )->plural()->snake()->lower();
            $namespace = ucfirst( $this->option( 'namespace' ) );
            $version   = ucfirst( $this->option( 'v' ) );

            if ( ! $namespace ) {
                $this->error( 'namespace is required!' );

                return;
            }

            // resource class
            $policyStub = $this->fs->read( 'app/Http/Resources/stubs/resource.stub' );
            $policyStub = Str::replace( "{{RESOURCE::NAMESPACE}}", $namespace, $policyStub );
            $policyStub = Str::replace( "{{RESOURCE::MODEL}}", $singular, $policyStub );
            $policyStub = Str::replace( "{{RESOURCE::PLURAL}}", $plural, $policyStub );
            $policyStub = Str::replace( "{{RESOURCE::VERSION}}", $version, $policyStub );
            $this->fs->write( "app/Http/Resources/$version/$namespace/$singular/{$singular}Resource.php", $policyStub );

            // resource collection class
            $policyStub = $this->fs->read( 'app/Http/Resources/stubs/collection.stub' );
            $policyStub = Str::replace( "{{COLLECTION::NAMESPACE}}", $namespace, $policyStub );
            $policyStub = Str::replace( "{{COLLECTION::MODEL}}", $singular, $policyStub );
            $policyStub = Str::replace( "{{COLLECTION::PLURAL}}", $plural, $policyStub );
            $policyStub = Str::replace( "{{COLLECTION::VERSION}}", $version, $policyStub );
            $this->fs->write( "app/Http/Resources/$version/$namespace/$singular/{$singular}Collection.php",
                $policyStub );

            $this->info( "resource and collection classes successfully created!" );
        }

    }
