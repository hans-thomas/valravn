<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\File;

	class RelationTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function belongsToMany(): void {
			$file = app_path( "Http/Requests/V1/Blog/Post/PostCategoriesRequest.php" );

			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:relation blog PostCategories --belongs-to-many" );

			self::assertFileExists( $file );

			$content = "<?php

    namespace App\Http\Requests\V1\Blog\Post;

    use App\Models\Blog\Post;
    use Hans\Valravn\Http\Requests\Contracts\Relations\BelongsToManyRequest;

    class PostCategoriesRequest extends BelongsToManyRequest {

        protected function model(): string {
            return Post::class;
        }

        protected function pivots(): array {
            return [
                // Pivot columns validation rules go here
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
		public function belongsToManyWithVersion(): void {
			$file = app_path( "Http/Requests/V3/Blog/Post/PostCategoriesRequest.php" );

			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:relation blog PostCategories --belongs-to-many --v 3" );

			self::assertFileExists( $file );

			$content = "<?php

    namespace App\Http\Requests\V3\Blog\Post;

    use App\Models\Blog\Post;
    use Hans\Valravn\Http\Requests\Contracts\Relations\BelongsToManyRequest;

    class PostCategoriesRequest extends BelongsToManyRequest {

        protected function model(): string {
            return Post::class;
        }

        protected function pivots(): array {
            return [
                // Pivot columns validation rules go here
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
		public function hasMany(): void {
			$file = app_path( "Http/Requests/V1/Blog/Post/PostCategoriesRequest.php" );

			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:relation blog PostCategories --has-many" );

			self::assertFileExists( $file );

			$content = "<?php

    namespace App\Http\Requests\V1\Blog\Post;

    use App\Models\Blog\Post;
    use Hans\Valravn\Http\Requests\Contracts\Relations\HasManyRequest;

    class PostCategoriesRequest extends HasManyRequest {

        protected function model(): string {
            return Post::class;
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
		public function hasManyWithVersion(): void {
			$file = app_path( "Http/Requests/V4/Blog/Post/PostCategoriesRequest.php" );

			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:relation blog PostCategories --has-many --v 4" );

			self::assertFileExists( $file );

			$content = "<?php

    namespace App\Http\Requests\V4\Blog\Post;

    use App\Models\Blog\Post;
    use Hans\Valravn\Http\Requests\Contracts\Relations\HasManyRequest;

    class PostCategoriesRequest extends HasManyRequest {

        protected function model(): string {
            return Post::class;
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
		public function morphedByMany(): void {
			$file = app_path( "Http/Requests/V1/Blog/Post/PostCategoriesRequest.php" );

			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:relation blog PostCategories --morphed-by-many" );

			self::assertFileExists( $file );

			$content = "<?php

    namespace App\Http\Requests\V1\Blog\Post;

    use App\Models\Blog\Post;
    use Hans\Valravn\Http\Requests\Contracts\Relations\MorphedByManyRequest;

    class PostCategoriesRequest extends MorphedByManyRequest {

        protected function model(): string {
            return Post::class;
        }

        protected function pivots(): array {
            return [
                // Pivot columns validation rules go here
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
		public function morphedByManyWithVersion(): void {
			$file = app_path( "Http/Requests/V6/Blog/Post/PostCategoriesRequest.php" );

			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:relation blog PostCategories --morphed-by-many --v 6" );

			self::assertFileExists( $file );

			$content = "<?php

    namespace App\Http\Requests\V6\Blog\Post;

    use App\Models\Blog\Post;
    use Hans\Valravn\Http\Requests\Contracts\Relations\MorphedByManyRequest;

    class PostCategoriesRequest extends MorphedByManyRequest {

        protected function model(): string {
            return Post::class;
        }

        protected function pivots(): array {
            return [
                // Pivot columns validation rules go here
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
		public function morphedToMany(): void {
			$file = app_path( "Http/Requests/V1/Blog/Post/PostCategoriesRequest.php" );

			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:relation blog PostCategories --morph-to-many" );

			self::assertFileExists( $file );

			$content = "<?php

    namespace App\Http\Requests\V1\Blog\Post;

    use App\Models\Blog\Post;
    use Hans\Valravn\Http\Requests\Contracts\Relations\MorphToManyRequest;

    class PostCategoriesRequest extends MorphToManyRequest {

        protected function model(): string {
            return Post::class;
        }

        protected function pivots(): array {
            return [
                // Pivot columns validation rules go here
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
		public function morphedToManyWithVersion(): void {
			$file = app_path( "Http/Requests/V8/Blog/Post/PostCategoriesRequest.php" );

			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:relation blog PostCategories --morph-to-many --v 8" );

			self::assertFileExists( $file );

			$content = "<?php

    namespace App\Http\Requests\V8\Blog\Post;

    use App\Models\Blog\Post;
    use Hans\Valravn\Http\Requests\Contracts\Relations\MorphToManyRequest;

    class PostCategoriesRequest extends MorphToManyRequest {

        protected function model(): string {
            return Post::class;
        }

        protected function pivots(): array {
            return [
                // Pivot columns validation rules go here
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
		public function morphTo(): void {
			$file = app_path( "Http/Requests/V1/Blog/Like/LikeLikableRequest.php" );

			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:relation blog LikeLikable --morph-to" );

			self::assertFileExists( $file );

			$content = "<?php

    namespace App\Http\Requests\V1\Blog\Like;

    use App\Http\Requests\MorphToRequest;

    class LikeLikableRequest extends MorphToRequest {

		/**
		 * Get Allowed entities for MorphTo relationship
		 *
		 * @return array
		 */
        protected function entities(): array {
            return [
                // allowed entities go here
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
		public function morphToWithVersion(): void {
			$file = app_path( "Http/Requests/V2/Blog/Like/LikeLikableRequest.php" );

			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:relation blog LikeLikable --morph-to --v 2" );

			self::assertFileExists( $file );

			$content = "<?php

    namespace App\Http\Requests\V2\Blog\Like;

    use App\Http\Requests\MorphToRequest;

    class LikeLikableRequest extends MorphToRequest {

		/**
		 * Get Allowed entities for MorphTo relationship
		 *
		 * @return array
		 */
        protected function entities(): array {
            return [
                // allowed entities go here
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