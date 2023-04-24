<?php

    namespace Hans\Valravn\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Str;
    use Throwable;

    class Requests extends Command {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'valravn:requests {name} {--namespace=} {--v=v1}';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'creates request classes.';

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

            Artisan::call( "make:request $version/$namespace/$singular/{$singular}StoreRequest" );
            Artisan::call( "make:request $version/$namespace/$singular/{$singular}UpdateRequest" );

            $this->info( "request classes successfully created!" );
        }

    }
