<?php

	namespace Hans\Tests\Valravn;

	use Hans\Tests\Valravn\Core\Models\Post;
	use Hans\Tests\Valravn\Core\Resources\Post\PostResource;
	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Hans\Valravn\ValravnServiceProvider;
	use Illuminate\Contracts\Console\Kernel;
	use Illuminate\Contracts\Filesystem\Filesystem;
	use Illuminate\Foundation\Application;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Routing\Router;
	use Illuminate\Support\Arr;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\Storage;
	use Orchestra\Testbench\TestCase as BaseTestCase;

	class TestCase extends BaseTestCase {
		use RefreshDatabase;

		public Filesystem $storage;
		private array $config;

		public function getConfig( string $key, $default ) {
			return Arr::get( $this->config, $key, $default );
		}

		/**
		 * Setup the test environment.
		 */
		protected function setUp(): void {
			parent::setUp();
			$this->config  = config( 'valravn' );
			$this->storage = Storage::disk( 'public' );
			$this->loadMigrationsFrom( __DIR__ . '/Core/migrations' );
		}

		/**
		 * Get application timezone.
		 *
		 * @param Application $app
		 *
		 * @return string|null
		 */
		protected function getApplicationTimezone( $app ) {
			return 'UTC';
		}

		/**
		 * Get package providers.
		 *
		 * @param Application $app
		 *
		 * @return array
		 */
		protected function getPackageProviders( $app ) {
			return [
				ValravnServiceProvider::class
			];
		}

		/**
		 * Override application aliases.
		 *
		 * @param Application $app
		 *
		 * @return array
		 */
		protected function getPackageAliases( $app ) {
			return [ /* 'Acme' => 'Acme\Facade' */ ];
		}

		/**
		 * Define environment setup.
		 *
		 * @param Application $app
		 *
		 * @return void
		 */
		protected function defineEnvironment( $app ) {
			// Setup default database to use sqlite :memory:
			$app[ 'config' ]->set( 'database.default', 'testbench' );
			$app[ 'config' ]->set( 'database.connections.testbench', [
				'driver'   => 'sqlite',
				'database' => ':memory:',
				'prefix'   => '',
			] );
		}

		/**
		 * Define routes setup.
		 *
		 * @param Router $router
		 *
		 * @return void
		 */
		protected function defineRoutes( $router ) {
			$router->get(
				'/posts/{post}/includes',
				fn( $post ) => PostResource::make( Post::findOrFail( $post ) )->parseIncludes()
			);
		}

		public function resourceToJson( BaseJsonResource $resource ): array {
			return json_decode(
				$resource->toResponse( request() )->content(),
				true
			);
		}

	}