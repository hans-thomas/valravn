<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\File;

	class ExceptionTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function exceptions(): void {
			$exception = app_path( "Exceptions/Blog/Post/PostException.php" );
			$errorCode = app_path( "Exceptions/Blog/Post/PostErrorCode.php" );
			File::delete( [ $exception, $errorCode ] );

			self::assertFileDoesNotExist( $exception );
			self::assertFileDoesNotExist( $errorCode );

			Artisan::call( 'valravn:exception blog posts' );

			self::assertFileExists( $exception );
			self::assertFileExists( $errorCode );
		}

	}