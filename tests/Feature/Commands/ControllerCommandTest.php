<?php

	namespace Hans\Tests\Valravn\Feature\Commands;


	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\File;

	class ControllerCommandTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function NotEnoughArguments(): void {
			$this->expectExceptionMessage( 'Not enough arguments (missing: "name").' );
			$this->artisan( 'valravn:controller' );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function namespaceIsRequired(): void {
			$command = $this->artisan( 'valravn:controller posts' );
			$command->expectsOutput( "namespace is required!" );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function crud(): void {
			$file = app_path( "Http/Controllers/V1/Blog/Post/PostCrudController.php" );
			if ( File::exists( $file ) ) {
				unlink( $file );
			}
			self::assertFileDoesNotExist( $file );

			$command = $this->artisan( 'valravn:controller posts --namespace blog' );
			$command->expectsOutput( "controller classes successfully created!" );
			$command->execute();

			self::assertFileExists( $file );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function relations(): void {
			$file = app_path( "Http/Controllers/V1/Blog/Post/PostRelationsController.php" );
			if ( File::exists( $file ) ) {
				unlink( $file );
			}
			self::assertFileDoesNotExist( $file );

			$command = $this->artisan( 'valravn:controller posts --namespace blog --relations' );
			$command->expectsOutput( "controller classes successfully created!" );
			$command->execute();

			self::assertFileExists( $file );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function actions(): void {
			$file = app_path( "Http/Controllers/V1/Blog/Post/PostActionsController.php" );
			if ( File::exists( $file ) ) {
				unlink( $file );
			}
			self::assertFileDoesNotExist( $file );

			$command = $this->artisan( 'valravn:controller posts --namespace blog --actions' );
			$command->expectsOutput( "controller classes successfully created!" );
			$command->execute();

			self::assertFileExists( $file );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function requests(): void {
			$store  = app_path( "Http/Requests/V1/Blog/Post/PostStoreRequest.php" );
			$update = app_path( "Http/Requests/V1/Blog/Post/PostUpdateRequest.php" );
			if ( File::exists( $store ) ) {
				unlink( $store );
			}
			if ( File::exists( $update ) ) {
				unlink( $update );
			}
			self::assertFileDoesNotExist( $store );
			self::assertFileDoesNotExist( $update );

			$command = $this->artisan( 'valravn:controller posts --namespace blog --requests' );
			$command->expectsOutput( "controller classes successfully created!" );
			$command->execute();

			self::assertFileExists( $store );
			self::assertFileExists( $update );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function resources(): void {
			$store  = app_path( "Http/Resources/V1/Blog/Post/PostResource.php" );
			$update = app_path( "Http/Resources/V1/Blog/Post/PostCollection.php" );
			if ( File::exists( $store ) ) {
				unlink( $store );
			}
			if ( File::exists( $update ) ) {
				unlink( $update );
			}
			self::assertFileDoesNotExist( $store );
			self::assertFileDoesNotExist( $update );

			$command = $this->artisan( 'valravn:controller posts --namespace blog --resources' );
			$command->expectsOutput( "controller classes successfully created!" );
			$command->execute();

			self::assertFileExists( $store );
			self::assertFileExists( $update );
		}

	}