<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\File;

	class ControllersCommandTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function NotEnoughArguments(): void {
			$this->expectExceptionMessage( 'Not enough arguments (missing: "namespace, name").' );
			$this->artisan( 'valravn:controllers' );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function namespaceIsRequired(): void {
			$this->expectExceptionMessage( 'Not enough arguments (missing: "name").' );
			$this->artisan( 'valravn:controllers posts' );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function controllers(): void {
			$crud      = app_path( "Http/Controllers/V1/Blog/Post/PostCrudController.php" );
			$relations = app_path( "Http/Controllers/V1/Blog/Post/PostRelationsController.php" );
			$actions   = app_path( "Http/Controllers/V1/Blog/Post/PostActionsController.php" );
			File::delete( [ $crud, $relations, $actions ] );
			self::assertFileDoesNotExist( $crud );
			self::assertFileDoesNotExist( $relations );
			self::assertFileDoesNotExist( $actions );

			$command = $this->artisan( 'valravn:controllers blog posts' );
			$command->execute();
			$command->expectsOutput( "controller classes successfully created!" );

			self::assertFileExists( $crud );
			self::assertFileExists( $relations );
			self::assertFileExists( $actions );
		}


		/**
		 * @test
		 *
		 * @return void
		 */
		public function requests(): void {
			$store  = app_path( "Http/Requests/V1/Blog/Post/PostStoreRequest.php" );
			$update = app_path( "Http/Requests/V1/Blog/Post/PostUpdateRequest.php" );
			File::delete( [ $store, $update ] );
			self::assertFileDoesNotExist( $store );
			self::assertFileDoesNotExist( $update );

			$command = $this->artisan( 'valravn:controllers blog posts --requests' );
			$command->execute();
			$command->expectsOutput( "controller classes successfully created!" );

			self::assertFileExists( $store );
			self::assertFileExists( $update );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function resources(): void {
			$resource   = app_path( "Http/Resources/V1/Blog/Post/PostResource.php" );
			$collection = app_path( "Http/Resources/V1/Blog/Post/PostCollection.php" );
			File::delete( [ $resource, $collection ] );
			self::assertFileDoesNotExist( $resource );
			self::assertFileDoesNotExist( $collection );

			$command = $this->artisan( 'valravn:controllers blog posts --resources' );
			$command->execute();
			$command->expectsOutput( "controller classes successfully created!" );

			self::assertFileExists( $resource );
			self::assertFileExists( $collection );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function version(): void {
			$crud       = app_path( "Http/Controllers/V2/Blog/Post/PostCrudController.php" );
			$relations  = app_path( "Http/Controllers/V2/Blog/Post/PostRelationsController.php" );
			$actions    = app_path( "Http/Controllers/V2/Blog/Post/PostActionsController.php" );
			$store      = app_path( "Http/Requests/V2/Blog/Post/PostStoreRequest.php" );
			$update     = app_path( "Http/Requests/V2/Blog/Post/PostUpdateRequest.php" );
			$resource   = app_path( "Http/Resources/V2/Blog/Post/PostResource.php" );
			$collection = app_path( "Http/Resources/V2/Blog/Post/PostCollection.php" );
			File::delete( [ $crud, $relations, $actions, $store, $update, $resource, $collection ] );

			$command = $this->artisan( 'valravn:controllers blog posts --requests --resources --v=2' );
			$command->execute();
			$command->expectsOutput( "controller classes successfully created!" );

			self::assertFileExists( $crud );
			self::assertFileExists( $relations );
			self::assertFileExists( $actions );
			self::assertFileExists( $store );
			self::assertFileExists( $update );
			self::assertFileExists( $resource );
			self::assertFileExists( $collection );
		}


	}