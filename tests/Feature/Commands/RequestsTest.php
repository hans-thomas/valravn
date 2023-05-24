<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\Artisan;
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

			Artisan::call( "valravn:requests blog posts" );

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

			Artisan::call( "valravn:requests blog posts --v 2" );

			self::assertFileExists( $store );
			self::assertFileExists( $update );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function batchUpdate(): void {
			$file = app_path( "Http/Requests/V1/Blog/Post/PostBatchUpdateRequest.php" );

			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:requests blog posts --batch-update" );

			self::assertFileExists( $file );

			$content = "<?php

    namespace App\Http\Requests\V1\Blog\Post;

    use App\Models\Blog\Post;
    use Hans\Valravn\Http\Requests\Contracts\BatchUpdateRequest;

    class PostBatchUpdateRequest extends BatchUpdateRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
        protected function model(): string {
            return Post::class;
        }

		/**
		 * Get fields and their validation rules
		 *
		 * @return array
		 */
        protected function fields(): array {
            return [
                // fields definition go here
            ];
        }

    }
";
			self::assertEquals(
				$content,
				file_get_contents( $file )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function batchUpdateWithVersion(): void {
			$file = app_path( "Http/Requests/V5/Blog/Post/PostBatchUpdateRequest.php" );

			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:requests blog posts --batch-update --v 5" );

			self::assertFileExists( $file );

			$content = "<?php

    namespace App\Http\Requests\V5\Blog\Post;

    use App\Models\Blog\Post;
    use Hans\Valravn\Http\Requests\Contracts\BatchUpdateRequest;

    class PostBatchUpdateRequest extends BatchUpdateRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
        protected function model(): string {
            return Post::class;
        }

		/**
		 * Get fields and their validation rules
		 *
		 * @return array
		 */
        protected function fields(): array {
            return [
                // fields definition go here
            ];
        }

    }
";
			self::assertEquals(
				$content,
				file_get_contents( $file )
			);
		}

	}