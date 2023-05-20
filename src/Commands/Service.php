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
		protected $signature = '
		valravn:service 
		{namespace : Group of the entity}
		{name : Name of the entity}
		{--r|relations : Generate an extra service to manage relations }
		{--a|actions : Generate an extra service to manage actions}
		';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Generate service classes.';

		private Filesystem $fs;

		public function __construct() {
			parent::__construct();
			$this->fs = Storage::createLocalDriver( [
				'root'       => app_path(),
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
			$namespace = Str::of( $this->argument( 'namespace' ) )->ucfirst()->toString();

			// crud service
			$crudService = file_get_contents( __DIR__ . "/stubs/services/crud.stub" );
			$crudService = Str::replace( "{{CRUD-SERVICE::NAMESPACE}}", $namespace, $crudService );
			$crudService = Str::replace( "{{CRUD-SERVICE::MODEL}}", $singular, $crudService );
			$this->fs->write( "Services/$namespace/$singular/{$singular}CrudService.php", $crudService );

			// relations service
			if ( $this->option( 'relations' ) ) {
				$relationsService = file_get_contents( __DIR__ . "/stubs/services/relations.stub" );
				$relationsService = Str::replace( "{{CRUD-SERVICE::NAMESPACE}}", $namespace, $relationsService );
				$relationsService = Str::replace( "{{CRUD-SERVICE::MODEL}}", $singular, $relationsService );
				$this->fs->write( "Services/$namespace/$singular/{$singular}RelationsService.php", $relationsService );
			}

			// actions service
			if ( $this->option( 'actions' ) ) {
				$actionsService = file_get_contents( __DIR__ . "/stubs/services/actions.stub" );
				$actionsService = Str::replace( "{{CRUD-SERVICE::NAMESPACE}}", $namespace, $actionsService );
				$actionsService = Str::replace( "{{CRUD-SERVICE::MODEL}}", $singular, $actionsService );
				$this->fs->write( "Services/$namespace/$singular/{$singular}ActionsService.php", $actionsService );
			}

			$this->info( "service classes successfully created!" );
		}

	}
