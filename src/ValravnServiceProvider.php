<?php


	namespace Hans\Valravn;


	use Hans\Valravn\Commands\Entity;
	use Hans\Valravn\Commands\Controller;
	use Hans\Valravn\Commands\Controllers;
	use Hans\Valravn\Commands\Exception;
	use Hans\Valravn\Commands\Migration;
	use Hans\Valravn\Commands\Model;
	use Hans\Valravn\Commands\Policy;
	use Hans\Valravn\Commands\Repository;
	use Hans\Valravn\Commands\Requests;
	use Hans\Valravn\Commands\Resources;
	use Hans\Valravn\Commands\Service;
	use Hans\Valravn\Services\Caching\CachingService;
	use Hans\Valravn\Services\Filtering\FilteringService;
	use Illuminate\Support\Facades\Route;
	use Illuminate\Support\ServiceProvider;

	class ValravnServiceProvider extends ServiceProvider {

		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register() {
			$this->app->singleton( FilteringService::class, FilteringService::class );
			$this->app->singleton( CachingService::class, CachingService::class );
		}

		/**
		 * Bootstrap any application services.
		 *
		 * @return void
		 */
		public function boot() {
			$this->publishes(
				[
					__DIR__ . '/../config/config.php' => config_path( 'valravn.php' )
				],
				'alicia-config'
			);
			$this->loadMigrationsFrom( __DIR__ . '/../database/migrations' );
			$this->mergeConfigFrom( __DIR__ . '/../config/config.php', 'valravn' );

			$this->registerRoutes();
			$this->registerCommands();
		}

		/**
		 * Register defined routes
		 *
		 * @return void
		 */
		protected function registerRoutes() {
			Route::prefix( 'valravn' )->middleware( 'api' )->group( __DIR__ . '/../routes/api.php' );
		}

		/**
		 * Register created commands
		 *
		 * @return void
		 */
		protected function registerCommands() {
			if ( $this->app->runningInConsole() ) {
				$this->commands( [
					Entity::class,
					Controller::class,
					Controllers::class,
					Exception::class,
					Migration::class,
					Model::class,
					Policy::class,
					Repository::class,
					Requests::class,
					Resources::class,
					Service::class,
				] );
			}
		}

	}
