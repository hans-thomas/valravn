<?php

	namespace Hans\Valravn\Tests\Feature\Commands;

	use Hans\Valravn\Tests\TestCase;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\File;

	class ControllersTest extends TestCase {

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

			Artisan::call( 'valravn:controllers blog posts' );

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
			$store       = app_path( "Http/Requests/V1/Blog/Post/PostStoreRequest.php" );
			$update      = app_path( "Http/Requests/V1/Blog/Post/PostUpdateRequest.php" );
			$batchUpdate = app_path( "Http/Requests/V1/Blog/Post/PostBatchUpdateRequest.php" );

			File::delete( [ $store, $update, $batchUpdate ] );

			self::assertFileDoesNotExist( $store );
			self::assertFileDoesNotExist( $update );

			Artisan::call( 'valravn:controllers blog posts --requests' );

			self::assertFileExists( $store );
			self::assertFileExists( $update );
			self::assertFileExists( $batchUpdate );
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

			Artisan::call( 'valravn:controllers blog posts --resources' );

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
			self::assertFileDoesNotExist( $crud );
			self::assertFileDoesNotExist( $relations );
			self::assertFileDoesNotExist( $actions );
			self::assertFileDoesNotExist( $store );
			self::assertFileDoesNotExist( $update );
			self::assertFileDoesNotExist( $resource );
			self::assertFileDoesNotExist( $collection );

			Artisan::call( 'valravn:controllers blog posts --requests --resources --v=2' );

			self::assertFileExists( $crud );
			self::assertFileExists( $relations );
			self::assertFileExists( $actions );
			self::assertFileExists( $store );
			self::assertFileExists( $update );
			self::assertFileExists( $resource );
			self::assertFileExists( $collection );
		}


	}