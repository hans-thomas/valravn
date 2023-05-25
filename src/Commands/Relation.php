<?php

	namespace Hans\Valravn\Commands;

	use Hans\Valravn\Http\Requests\Contracts\Relations\BelongsToManyRequest;
	use Hans\Valravn\Http\Requests\Contracts\Relations\HasManyRequest;
	use Hans\Valravn\Http\Requests\Contracts\Relations\MorphedByManyRequest;
	use Hans\Valravn\Http\Requests\Contracts\Relations\MorphToManyRequest;
	use Illuminate\Console\Command;
	use Illuminate\Contracts\Filesystem\Filesystem;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Str;
	use League\Flysystem\Visibility;
	use Throwable;

	class Relation extends Command {

		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = '
        valravn:relation
		{namespace : Group of the entity}
		{name : Name of the entity}
		{related-namespace : Group of the related entity}
		{related-name? : Name of the related entity}
		{--v=1 : Version of the entity}
		{--belongs-to-many : Belongs to many request}
		{--has-many : Has many request}
		{--morphed-by-many : Morphed by many request}
		{--morph-to-many : Morph to many request}
		{--morph-to : morph to request}
        ';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Generate store and update request classes.';

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
			$name      = $this->argument( 'name' );
			$singular  = Str::of( $name )->singular()->ucfirst()->toString();
			$namespace = ucfirst( $this->argument( 'namespace' ) );

			$relatedName      = $this->argument( 'related-name' );
			$relatedSingular  = Str::of( $relatedName )->singular()->ucfirst()->toString();
			$relatedNamespace = ucfirst( $this->argument( 'related-namespace' ) );

			$version = 'V' . filter_var( $this->option( 'v' ), FILTER_SANITIZE_NUMBER_INT );

			if (
				$this->option( 'belongs-to-many' ) or
				$this->option( 'morphed-by-many' ) or
				$this->option( 'morph-to-many' ) or
				$this->option( 'has-many' )
			) {
				$relation = Str::plural( $relatedSingular );
				$content  = file_get_contents(
					$this->option( 'has-many' ) ?
						__DIR__ . "/stubs/relations/has-many.stub" :
						__DIR__ . "/stubs/relations/many-to-many.stub"
				);

				$content = Str::replace( "{{RELATION::VERSION}}", $version, $content );
				$content = Str::replace( "{{RELATION::NAMESPACE}}", $namespace, $content );
				$content = Str::replace( "{{RELATION::ENTITY}}", $singular, $content );

				$content = Str::replace( "{{RELATION::RELATED-NAMESPACE}}", $relatedNamespace, $content );
				$content = Str::replace( "{{RELATION::MODEL}}", $relatedSingular, $content );
				$content = Str::replace( "{{RELATION::RELATION}}", $relation, $content );

				if ( $this->option( 'belongs-to-many' ) ) {
					$extends = class_basename( BelongsToManyRequest::class );
				} elseif ( $this->option( 'morphed-by-many' ) ) {
					$extends = class_basename( MorphedByManyRequest::class );
				} elseif ( $this->option( 'morph-to-many' ) ) {
					$extends = class_basename( MorphToManyRequest::class );
				} elseif ( $this->option( 'has-many' ) ) {
					$extends = class_basename( HasManyRequest::class );
				}

				$content = Str::replace(
					"{{RELATION::EXTENDS}}",
					$extends,
					$content
				);
				$this->fs->write(
					"Http/Requests/$version/$namespace/$singular/{$singular}{$relation}Request.php",
					$content
				);
			}

			if ( $this->option( 'morph-to' ) ) {
				$morphTo = file_get_contents( __DIR__ . "/stubs/relations/morph-to.stub" );

				$morphTo = Str::replace( "{{RELATION::VERSION}}", $version, $morphTo );
				$morphTo = Str::replace( "{{RELATION::NAMESPACE}}", $namespace, $morphTo );
				$morphTo = Str::replace( "{{RELATION::MODEL}}", $singular, $morphTo );
				$morphTo = Str::replace( "{{RELATION::RELATION}}", $relatedNamespace, $morphTo );

				$this->fs->write(
					"Http/Requests/$version/$namespace/$singular/{$singular}{$relatedNamespace}Request.php",
					$morphTo
				);
			}

			if (
				$this->option( 'belongs-to-many' ) or
				$this->option( 'morphed-by-many' ) or
				$this->option( 'morph-to-many' ) or
				$this->option( 'has-many' ) or
				$this->option( 'morph-to' )
			) {
				$this->error( 'You should pass one option at least.' );

				return;
			}


			$this->info( "request class successfully created!" );
		}

	}
