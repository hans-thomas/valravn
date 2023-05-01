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
			$singular  = ucfirst( Str::singular( $this->argument( 'name' ) ) );
			$plural    = Str::of( $this->argument( 'name' ) )->plural()->snake()->lower();
			$namespace = ucfirst( $this->option( 'namespace' ) );
			$version   = ucfirst( $this->option( 'v' ) );

			if ( ! $namespace ) {
				$this->error( 'namespace is required!' );

				return;
			}

			// resource class
			$resourceStub = file_get_contents( __DIR__ . '/stubs/resources/resource.stub' );
			$resourceStub = Str::replace( "{{RESOURCE::NAMESPACE}}", $namespace, $resourceStub );
			$resourceStub = Str::replace( "{{RESOURCE::MODEL}}", $singular, $resourceStub );
			$resourceStub = Str::replace( "{{RESOURCE::PLURAL}}", $plural, $resourceStub );
			$resourceStub = Str::replace( "{{RESOURCE::VERSION}}", $version, $resourceStub );
			$this->fs->write( "Http/Resources/$version/$namespace/$singular/{$singular}Resource.php", $resourceStub );

			// resource collection class
			$collectionStub = file_get_contents( __DIR__ . '/stubs/resources/collection.stub' );
			$collectionStub = Str::replace( "{{COLLECTION::NAMESPACE}}", $namespace, $collectionStub );
			$collectionStub = Str::replace( "{{COLLECTION::MODEL}}", $singular, $collectionStub );
			$collectionStub = Str::replace( "{{COLLECTION::PLURAL}}", $plural, $collectionStub );
			$collectionStub = Str::replace( "{{COLLECTION::VERSION}}", $version, $collectionStub );
			$this->fs->write(
				"Http/Resources/$version/$namespace/$singular/{$singular}Collection.php",
				$collectionStub
			);

			$this->info( "resource and collection classes successfully created!" );
		}

	}
