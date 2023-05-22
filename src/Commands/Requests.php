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
		protected $signature = '
        valravn:requests 
		{namespace : Group of the entity}
		{name : Name of the entity}
		{--v=1 : Version of the entity}
        ';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Generate store and update request classes.';

		/**
		 * Execute the console command.
		 *
		 * @return void
		 * @throws Throwable
		 */
		public function handle() {
			$singular  = ucfirst( Str::singular( $this->argument( 'name' ) ) );
			$namespace = ucfirst( $this->argument( 'namespace' ) );
			$version   = 'V' . filter_var( $this->option( 'v' ), FILTER_SANITIZE_NUMBER_INT );

			Artisan::call( "make:request $version/$namespace/$singular/{$singular}StoreRequest" );
			Artisan::call( "make:request $version/$namespace/$singular/{$singular}UpdateRequest" );

			$this->info( "request classes successfully created!" );
		}

	}
