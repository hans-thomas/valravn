<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\File;

	class RequestsTest extends TestCase {

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

			$command = $this->artisan( "valravn:requests blog posts" );
			$command->execute();
			$command->expectsOutput( "request classes successfully created!" );

			self::assertFileExists( $store );
			self::assertFileExists( $update );
		}

		/**
		 * @test
		 *
		 * @return void
		*/
		public function version(): void {
			$store  = app_path( "Http/Requests/V2/Blog/Post/PostStoreRequest.php" );
			$update = app_path( "Http/Requests/V2/Blog/Post/PostUpdateRequest.php" );

			File::delete( [ $store, $update ] );
			self::assertFileDoesNotExist( $store );
			self::assertFileDoesNotExist( $update );

			$command = $this->artisan( "valravn:requests blog posts --v 2" );
			$command->execute();
			$command->expectsOutput( "request classes successfully created!" );

			self::assertFileExists( $store );
			self::assertFileExists( $update );
		}

	}