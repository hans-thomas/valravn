<?php


	namespace Hans\Starter;


	use Illuminate\Support\Facades\Route;
	use Illuminate\Support\ServiceProvider;

	class StarterServiceProvider extends ServiceProvider {
		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register() {
			//
		}

		/**
		 * Bootstrap any application services.
		 *
		 * @return void
		 */
		public function boot() {
			$this->publishes( [
				__DIR__ . '/../config/config.php' => config_path( 'starter.php' )
			], 'alicia-config' );
			$this->loadMigrationsFrom( __DIR__ . '/../database/migrations' );
			$this->mergeConfigFrom( __DIR__ . '/../config/config.php', 'starter' );

			$this->registerRoutes();
		}

		/**
		 * Define routes setup.
		 *
		 * @return void
		 */
		protected function registerRoutes() {
			Route::prefix( 'starter' )->middleware( 'api' )->group( __DIR__ . '/../routes/api.php' );
		}

	}
