<?php

	namespace Hans\Valravn\Tests\Feature;

	use Hans\Valravn\Tests\TestCase;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\File;
	use Illuminate\Support\Str;

	class InstallTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function install(): void {
			$config          = config_path( "valravn.php" );
			$serviceProvider = app_path( "Providers/RepositoryServiceProvider.php" );
			File::delete( [ $config, $serviceProvider ] );

			self::assertFileDoesNotExist( $config );
			self::assertFileDoesNotExist( $serviceProvider );

			Artisan::call( 'valravn:install' );

			self::assertFileExists( $config );
			self::assertFileExists( $serviceProvider );

			$serviceProviderContent = '<?php

    namespace App\Providers;

    use Illuminate\Support\ServiceProvider;

    class RepositoryServiceProvider extends ServiceProvider {

        /**
         * Register services.
         *
         * @return void
         */
        public function register() {
            // bind your repository contracts to your repository classes
        }

        /**
         * Bootstrap services.
         *
         * @return void
         */
        public function boot() {
            //
        }

    }
';

			self::assertEquals(
				$serviceProviderContent,
				file_get_contents( $serviceProvider )
			);

			// remove registered RepositoryServiceProvider in app config
			$appConfig = file_get_contents( config_path( 'app.php' ) );

			if ( Str::contains( $appConfig, 'App\\Providers\\RepositoryServiceProvider::class' ) ) {
				file_put_contents( config_path( 'app.php' ), str_replace(
					"App\\Providers\RepositoryServiceProvider::class," . PHP_EOL,
					null,
					$appConfig
				) );
			}

		}

	}