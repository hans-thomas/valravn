<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ExceptionTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function exceptions(): void
    {
        $exception = app_path('Exceptions/Blog/Post/PostException.php');
        $errorCode = app_path('Exceptions/Blog/Post/PostErrorCode.php');
        File::delete([$exception, $errorCode]);

        self::assertFileDoesNotExist($exception);
        self::assertFileDoesNotExist($errorCode);

        Artisan::call('valravn:exception blog posts');

        self::assertFileExists($exception);
        self::assertFileExists($errorCode);

        $exception_file = '<?php

    namespace App\Exceptions\Blog\Post;

    use Hans\Valravn\Exceptions\VException;
    use Symfony\Component\HttpFoundation\Response;

    class PostException extends VException {

        public static function failedToCreate(): VException {
            return self::make( "Failed to create the Post!", PostErrorCode::failedToCreate(),
                Response::HTTP_INTERNAL_SERVER_ERROR );
        }

        public static function failedToUpdate(): VException {
            return self::make( "Failed to update the Post!", PostErrorCode::failedToUpdate(),
                Response::HTTP_INTERNAL_SERVER_ERROR );
        }

        public static function failedToBatchUpdate(): VException {
            return self::make( "Failed to update the Post!", PostErrorCode::failedToBatchUpdate(),
                Response::HTTP_INTERNAL_SERVER_ERROR );
        }

        public static function failedToDelete(): VException {
            return self::make( "Failed to delete the Post!", PostErrorCode::failedToDelete(),
                Response::HTTP_INTERNAL_SERVER_ERROR );
        }

    }
';

        self::assertEquals(
            $exception_file,
            file_get_contents($exception)
        );

        $error_code_file = '<?php

    namespace App\Exceptions\Blog\Post;

    use Hans\Valravn\Exceptions\VErrorCode;

    class PostErrorCode extends VErrorCode {
        protected static string $prefix = \'ECx\';

        protected int $FAILED_TO_CREATE = 1;
        protected int $FAILED_TO_UPDATE = 2;
        protected int $FAILED_TO_BATCH_UPDATE = 3;
        protected int $FAILED_TO_DELETE = 4;
    }
';

        self::assertEquals(
            $error_code_file,
            file_get_contents($errorCode)
        );
    }
}
