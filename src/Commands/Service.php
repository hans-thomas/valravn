<?php

	namespace Hans\Valravn\Commands;

	use Illuminate\Console\Command;
	use Illuminate\Contracts\Filesystem\Filesystem;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Str;
	use League\Flysystem\Visibility;
	use Throwable;

	class Service extends Command {
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'valravn:service {name} {--namespace=} {--r|relations} {--a|actions}';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'creates service classes.';

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
			$singular  = Str::of( $this->argument( 'name' ) )->singular()->ucfirst()->toString();
			$namespace = Str::of( $this->option( 'namespace' ) )->ucfirst()->toString();

			if ( ! $namespace ) {
				$this->error( 'namespace is required!' );

				return;
			}

			// crud service
			$crudService = $this->fs->read( "stubs/services/crud.stub" );
			$crudService = Str::replace( "{{CRUD-SERVICE::NAMESPACE}}", $namespace, $crudService );
			$crudService = Str::replace( "{{CRUD-SERVICE::MODEL}}", $singular, $crudService );
			$this->fs->write( "app/Services/$namespace/$singular/{$singular}Service.php", $crudService );

			// relations service
			if ( $this->option( 'relations' ) ) {
				$crudService = $this->fs->read( "stubs/services/relations.stub" );
				$crudService = Str::replace( "{{CRUD-SERVICE::NAMESPACE}}", $namespace, $crudService );
				$crudService = Str::replace( "{{CRUD-SERVICE::MODEL}}", $singular, $crudService );
				$this->fs->write( "app/Services/$namespace/$singular/{$singular}RelationsService.php", $crudService );
			}

			// relations service
			if ( $this->option( 'actions' ) ) {
				$crudService = $this->fs->read( "stubs/services/actions.stub" );
				$crudService = Str::replace( "{{CRUD-SERVICE::NAMESPACE}}", $namespace, $crudService );
				$crudService = Str::replace( "{{CRUD-SERVICE::MODEL}}", $singular, $crudService );
				$this->fs->write( "app/Services/$namespace/$singular/{$singular}ActionsService.php", $crudService );
			}

			$this->info( "service classes successfully created!" );
		}

	}
