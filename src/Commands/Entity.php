<?php

    namespace Hans\Valravn\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Support\Facades\Artisan;
    use Throwable;

    class Entity extends Command {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'valravn:entity {name} {--namespace=} {--v=v1}';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'creates an entity includes controllers, requests, resources, model and migration, seeder and factory and more.';

        /**
         * Execute the console command.
         *
         * @return void
         * @throws Throwable
         */
        public function handle() {
            $name      = $this->argument( 'name' );
            $namespace = $this->option( 'namespace' );
            $version   = $this->option( 'v' );

            if ( ! $namespace ) {
                $this->error( 'namespace is required!' );

                return;
            }

            Artisan::call( "valravn:exception $name --namespace $namespace" );

            Artisan::call( "valravn:model $name --namespace $namespace -sfm" );

            Artisan::call( "valravn:migration $name --namespace $namespace" );

            Artisan::call( "valravn:controllers $name --namespace $namespace --v $version --requests --resources" );

            Artisan::call( "valravn:policy $name --namespace $namespace" );

            Artisan::call( "valravn:repository $name --namespace $namespace" );

            Artisan::call( "valravn:service $name --namespace $namespace --relations" );

        }

    }
