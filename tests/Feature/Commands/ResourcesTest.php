<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\File;

	class ResourcesTest extends TestCase {

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

			Artisan::call( "valravn:resources blog posts" );

			self::assertFileExists( $resource );
			self::assertFileExists( $collection );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function version(): void {
			$resource   = app_path( "Http/Resources/V2/Blog/Post/PostResource.php" );
			$collection = app_path( "Http/Resources/V2/Blog/Post/PostCollection.php" );

			File::delete( [ $resource, $collection ] );
			self::assertFileDoesNotExist( $resource );
			self::assertFileDoesNotExist( $collection );

			Artisan::call( "valravn:resources blog posts --v 2" );

			self::assertFileExists( $resource );
			self::assertFileExists( $collection );
		}

	}