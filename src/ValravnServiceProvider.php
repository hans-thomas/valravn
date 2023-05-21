<?php


	namespace Hans\Valravn;


	use Hans\Valravn\Commands\Controller;
	use Hans\Valravn\Commands\Controllers;
	use Hans\Valravn\Commands\Entity;
	use Hans\Valravn\Commands\Exception;
	use Hans\Valravn\Commands\Migration;
	use Hans\Valravn\Commands\Model;
	use Hans\Valravn\Commands\Policy;
	use Hans\Valravn\Commands\Repository;
	use Hans\Valravn\Commands\Requests;
	use Hans\Valravn\Commands\Resources;
	use Hans\Valravn\Commands\Service;
	use Hans\Valravn\Services\Filtering\FilteringService;
	use Hans\Valravn\Services\Routing\RoutingService;
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Relations\Relation;
	use Illuminate\Database\Events\QueryExecuted;
	use Illuminate\Foundation\Application;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Route;
	use Illuminate\Support\ServiceProvider;

	class ValravnServiceProvider extends ServiceProvider {

		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register() {
			$this->app->bind( 'routing-service', RoutingService::class );
		}

		/**
		 * Bootstrap any application services.
		 *
		 * @return void
		 */
		public function boot() {
			$this->loadMigrationsFrom( __DIR__ . '/../database/migrations' );
			$this->mergeConfigFrom( __DIR__ . '/../config/config.php', 'valravn' );

			$this->registerRoutes();
			$this->registerMacros();
			if ( $this->app->runningInConsole() ) {
				$this->registerCommands();
				$this->registerPublishes();
			}
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

		protected function registerPublishes() {
			$this->publishes(
				[
					__DIR__ . '/../config/config.php' => config_path( 'valravn.php' )
				],
				'alicia-config'
			);
		}

		/**
		 * Register common macros
		 *
		 * @return void
		 */
		protected function registerMacros() {
			if ( env( 'ENABLE_DB_LOG', false ) ) {
				DB::listen( function( QueryExecuted $query ) {
					$bindings = implode( ',', $query->bindings );
					Log::info( $query->sql, [ "binding: [ $bindings ] execute time: $query->time" ]
					);
				} );
			}

			if ( ! Builder::hasGlobalMacro( 'applyFilters' ) ) {
				Builder::macro( 'applyFilters', function( array $options = [] ) {
					return app( FilteringService::class )->apply( $this, $options );
				} );
			}

			if ( ! Relation::hasMacro( 'applyFilters' ) ) {
				Relation::macro( 'applyFilters', function( array $options = [] ) {
					return app( FilteringService::class )->apply( $this, $options );
				} );
			}

			if ( ! Builder::hasGlobalMacro( 'whereLike' ) ) {
				Builder::macro( 'whereLike', function( $column, $value = null, $boolean = 'and' ) {
					$this->where( $column, 'LIKE', "%{$value}%", $boolean );
				} );
			}

			if ( ! Builder::hasGlobalMacro( 'orWhereLike' ) ) {
				Builder::macro( 'orWhereLike', function( $column, $value = null, $boolean = 'and' ) {
					$this->orWhere( $column, 'LIKE', "%{$value}%", $boolean );
				} );
			}

			if ( ! Application::hasMacro( 'runningInDev' ) ) {
				Application::macro( 'runningInDev', function() {
					if ( env( 'APP_ENV', 'local' ) != 'production' ) {
						return true;
					}

					return false;
				} );
			}
		}

	}
