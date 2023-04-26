<?php

    namespace Hans\Valravn\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Contracts\Filesystem\Filesystem;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use League\Flysystem\Visibility;
    use Throwable;

    class Exception extends Command {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'valravn:exception {name} {--namespace=}';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'creates exception and error code classes.';

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

            if ( ! $namespace ) {
                $this->error( 'namespace is required!' );

                return;
            }

            // exceptions: error code
            $errorCodeStub = $this->fs->read( 'stubs/exceptions/error-code.stub' );
            $errorCodeStub = Str::replace( '{{ENTITY::NAMESPACE}}', $namespace, $errorCodeStub );
            $errorCodeStub = Str::replace( '{{ENTITY::NAME}}', $singular, $errorCodeStub );
            $errorCode     = "app/Exceptions/$namespace/$singular/{$singular}ErrorCode.php";

            $this->fs->write( $errorCode, $errorCodeStub );
            // exceptions: exception
            $exceptionStub = $this->fs->read( 'stubs/exceptions/exception.stub' );
            $exceptionStub = Str::replace( '{{ENTITY::NAMESPACE}}', $namespace, $exceptionStub );
            $exceptionStub = Str::replace( '{{ENTITY::NAME}}', $singular, $exceptionStub );
            $exception     = "app/Exceptions/$namespace/$singular/{$singular}Exception.php";

            $this->fs->write( $exception, $exceptionStub );

            $this->info( "exception and error code classes successfully created!" );
        }

    }
