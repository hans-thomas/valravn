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

			$exception_file = '<?php

    namespace App\Exceptions\Blog\Post;

    use App\Exceptions\ValravnException;
    use Symfony\Component\HttpFoundation\Response;

    class PostException extends ValravnException {

        public static function failedToCreate(): ValravnException {
            return self::make( "Failed to create the Post!", PostErrorCode::failedToCreate(),
                Response::HTTP_INTERNAL_SERVER_ERROR );
        }

        public static function failedToUpdate(): ValravnException {
            return self::make( "Failed to update the Post!", PostErrorCode::failedToUpdate(),
                Response::HTTP_INTERNAL_SERVER_ERROR );
        }

        public static function failedToBatchUpdate(): ValravnException {
            return self::make( "Failed to update the Post!", PostErrorCode::failedToBatchUpdate(),
                Response::HTTP_INTERNAL_SERVER_ERROR );
        }

        public static function failedToDelete(): ValravnException {
            return self::make( "Failed to delete the Post!", PostErrorCode::failedToDelete(),
                Response::HTTP_INTERNAL_SERVER_ERROR );
        }

    }
';

			self::assertEquals(
				file_get_contents( $exception ),
				$exception_file
			);

			$error_code_file = '<?php

    namespace App\Exceptions\Blog\Post;

    use App\Exceptions\ErrorCode;

    class PostErrorCode extends ErrorCode {
        protected static string $prefix = \'ECx\';

        protected int $FAILED_TO_CREATE = 1;
        protected int $FAILED_TO_UPDATE = 2;
        protected int $FAILED_TO_BATCH_UPDATE = 3;
        protected int $FAILED_TO_DELETE = 4;
    }
';

			self::assertEquals(
				file_get_contents( $errorCode ),
				$error_code_file
			);

		}

	}