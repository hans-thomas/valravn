<?php

    namespace Hans\Valravn\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Contracts\Filesystem\Filesystem;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use League\Flysystem\Visibility;
    use Throwable;

    class Policy extends Command {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'valravn:policy {name} {--namespace=}';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'creates policy class.';

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

            $policyStub = $this->fs->read( 'app/Policies/stubs/policy.stub' );
            $policyStub = Str::replace( "{{POLICY::NAMESPACE}}", $namespace, $policyStub );
            $policyStub = Str::replace( "{{POLICY::MODEL}}", $singular, $policyStub );
            $this->fs->write( "app/Policies/$namespace/{$singular}Policy.php", $policyStub );

            $this->info( "policy class successfully created!" );
        }

    }
