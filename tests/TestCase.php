<?php

	namespace Hans\Valravn\Tests;

	use Hans\Valravn\Tests\Core\Models\Post;
	use Hans\Valravn\Tests\Core\Resources\Post\PostCollection;
	use Hans\Valravn\Tests\Core\Resources\Post\PostResource;
	use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
	use Hans\Valravn\ValravnServiceProvider;
	use Illuminate\Foundation\Application;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Routing\Router;
	use Orchestra\Testbench\TestCase as BaseTestCase;

	class TestCase extends BaseTestCase {
		use RefreshDatabase;

		/**
		 * Setup the test environment.
		 */
		protected function setUp(): void {
			parent::setUp();
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
				'/includes/posts/{post}',
				fn( $post ) => PostResource::make( Post::findOrFail( $post ) )->parseIncludes()
			);
			$router->get(
				'/includes/posts',
				fn() => PostCollection::make( Post::all() )->parseIncludes()
			);
			$router->get(
				'/queries/posts/{post}',
				fn( $post ) => PostResource::make( Post::findOrFail( $post ) )->parseQueries()
			);
			$router->get(
				'/queries/posts',
				fn() => PostCollection::make( Post::all() )->parseQueries()
			);
		}

		public function resourceToJson( ValravnJsonResource $resource ): array {
			return json_decode(
				$resource->toResponse( request() )->content(),
				true
			);
		}

	}