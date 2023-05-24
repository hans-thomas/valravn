<?php

	namespace Hans\Valravn\Commands;

	use Hans\Valravn\Http\Requests\Contracts\Relations\BelongsToManyRequest;
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
		{name : Name of the request}
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
			$name = $this->argument( 'name' );
			try {
				[ $entity, $relation ] = Str::of( $name )->ucfirst()->ucsplit()->toArray();
			} catch ( Throwable $e ) {
				$this->error( "Invalid name!" );

				return;
			}

			$singular  = Str::singular( $entity );
			$namespace = ucfirst( $this->argument( 'namespace' ) );
			$version   = 'V' . filter_var( $this->option( 'v' ), FILTER_SANITIZE_NUMBER_INT );

			if ( $this->option( 'belongs-to-many' ) ) {
				$belongsToMany = file_get_contents( __DIR__ . "/stubs/relations/many-to-many.stub" );
				$belongsToMany = Str::replace( "{{RELATION::VERSION}}", $version, $belongsToMany );
				$belongsToMany = Str::replace( "{{RELATION::NAMESPACE}}", $namespace, $belongsToMany );
				$belongsToMany = Str::replace( "{{RELATION::MODEL}}", $singular, $belongsToMany );
				$belongsToMany = Str::replace( "{{RELATION::RELATION}}", $relation, $belongsToMany );
				$belongsToMany = Str::replace(
					"{{RELATION::EXTENDS}}",
					class_basename( BelongsToManyRequest::class ),
					$belongsToMany
				);
				$this->fs->write(
					"Http/Requests/$version/$namespace/$singular/{$singular}{$relation}Request.php",
					$belongsToMany
				);
			}

			if ( $this->option( 'has-many' ) ) {
				$hasMany = file_get_contents( __DIR__ . "/stubs/relations/has-many.stub" );
				$hasMany = Str::replace( "{{RELATION::VERSION}}", $version, $hasMany );
				$hasMany = Str::replace( "{{RELATION::NAMESPACE}}", $namespace, $hasMany );
				$hasMany = Str::replace( "{{RELATION::MODEL}}", $singular, $hasMany );
				$hasMany = Str::replace( "{{RELATION::RELATION}}", $relation, $hasMany );
				$this->fs->write(
					"Http/Requests/$version/$namespace/$singular/{$singular}{$relation}Request.php",
					$hasMany
				);
			}

			if ( $this->option( 'morphed-by-many' ) ) {
				$morphedByMany = file_get_contents( __DIR__ . "/stubs/relations/many-to-many.stub" );
				$morphedByMany = Str::replace( "{{RELATION::VERSION}}", $version, $morphedByMany );
				$morphedByMany = Str::replace( "{{RELATION::NAMESPACE}}", $namespace, $morphedByMany );
				$morphedByMany = Str::replace( "{{RELATION::MODEL}}", $singular, $morphedByMany );
				$morphedByMany = Str::replace( "{{RELATION::RELATION}}", $relation, $morphedByMany );
				$morphedByMany = Str::replace(
					"{{RELATION::EXTENDS}}",
					class_basename( MorphedByManyRequest::class ),
					$morphedByMany
				);
				$this->fs->write(
					"Http/Requests/$version/$namespace/$singular/{$singular}{$relation}Request.php",
					$morphedByMany
				);
			}

			if ( $this->option( 'morph-to-many' ) ) {
				$morphToMany = file_get_contents( __DIR__ . "/stubs/relations/many-to-many.stub" );
				$morphToMany = Str::replace( "{{RELATION::VERSION}}", $version, $morphToMany );
				$morphToMany = Str::replace( "{{RELATION::NAMESPACE}}", $namespace, $morphToMany );
				$morphToMany = Str::replace( "{{RELATION::MODEL}}", $singular, $morphToMany );
				$morphToMany = Str::replace( "{{RELATION::RELATION}}", $relation, $morphToMany );
				$morphToMany = Str::replace(
					"{{RELATION::EXTENDS}}",
					class_basename( MorphToManyRequest::class ),
					$morphToMany
				);
				$this->fs->write(
					"Http/Requests/$version/$namespace/$singular/{$singular}{$relation}Request.php",
					$morphToMany
				);
			}

			if ( $this->option( 'morph-to' ) ) {
				$morphTo = file_get_contents( __DIR__ . "/stubs/relations/morph-to.stub" );
				$morphTo = Str::replace( "{{RELATION::VERSION}}", $version, $morphTo );
				$morphTo = Str::replace( "{{RELATION::NAMESPACE}}", $namespace, $morphTo );
				$morphTo = Str::replace( "{{RELATION::MODEL}}", $singular, $morphTo );
				$morphTo = Str::replace( "{{RELATION::RELATION}}", $relation, $morphTo );
				$this->fs->write(
					"Http/Requests/$version/$namespace/$singular/{$singular}{$relation}Request.php",
					$morphTo
				);
			}


			$this->info( "request class successfully created!" );
		}

	}
