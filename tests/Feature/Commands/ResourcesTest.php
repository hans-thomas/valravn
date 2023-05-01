<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
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

			$command = $this->artisan( "valravn:resources blog posts" );
			$command->execute();
			$command->expectsOutput( "resource and collection classes successfully created!" );

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

			$command = $this->artisan( "valravn:resources blog posts --v 2" );
			$command->execute();
			$command->expectsOutput( "resource and collection classes successfully created!" );

			self::assertFileExists( $resource );
			self::assertFileExists( $collection );
		}

	}