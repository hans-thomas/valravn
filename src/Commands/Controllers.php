<?php

	namespace Hans\Valravn\Commands;

	use Illuminate\Console\Command;
	use Illuminate\Support\Facades\Artisan;
	use Throwable;

	class Controllers extends Command {
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'valravn:controllers {namespace} {name} {--v=1} {--req|requests} {--res|resources}';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'creates controller classes.';

		/**
		 * Execute the console command.
		 *
		 * @return void
		 * @throws Throwable
		 */
		public function handle() {
			$name      = $this->argument( 'name' );
			$namespace = $this->argument( 'namespace' );
			$version   = 'v' . filter_var( $this->option( 'v' ), FILTER_SANITIZE_NUMBER_INT );

			Artisan::call( "valravn:controller", [
				'name'        => $name,
				'--namespace' => $namespace,
				'--v'         => $version,
				'--relations' => true,
				'--actions'   => true,
				'--resources' => $this->option( 'resources' ),
				'--requests'  => $this->option( 'requests' ),
			] );

			$this->info( "controller classes successfully created!" );
		}

	}
