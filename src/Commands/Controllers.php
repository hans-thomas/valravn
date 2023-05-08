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
		protected $signature = '
		valravn:controllers 
		{namespace: Group of the entity}
		{name: Name of the entity}
		{--v=1: Version of the entity}
		{--requests: Generate store and update request classes}
		{--resources: Generate resource and resource collection classes}
		';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Generate all controller classes.';

		/**
		 * Execute the console command.
		 *
		 * @return void
		 * @throws Throwable
		 */
		public function handle() {
			$name      = $this->argument( 'name' );
			$namespace = $this->argument( 'namespace' );
			$version   = 'V' . filter_var( $this->option( 'v' ), FILTER_SANITIZE_NUMBER_INT );

			Artisan::call( "valravn:controller", [
				'namespace'   => $namespace,
				'name'        => $name,
				'--v'         => $version,
				'--relations' => true,
				'--actions'   => true,
				'--resources' => $this->option( 'resources' ),
				'--requests'  => $this->option( 'requests' ),
			] );

			$this->info( "controller classes successfully created!" );
		}

	}
