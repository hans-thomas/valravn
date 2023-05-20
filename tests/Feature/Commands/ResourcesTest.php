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

			$resource_file = '<?php

    namespace App\Http\Resources\V1\Blog\Post;

    use Illuminate\Database\Eloquent\Model;
    use App\Http\Resources\Contracts\ValravnJsonResource;

    class PostResource extends ValravnJsonResource {

        /**
         * Extract attributes of the given model
         * if null returned, the parent::toArray method called by default
         *
         * @param Model $model
         *
         * @return array|null
         */
        public function extract( Model $model ): ?array {
            return [
                \'id\' => $model->id,
                //
            ];
        }

        /**
         * Specify the type of your resource
         *
         * @return string
         */
        public function type(): string {
            return \'posts\';
        }

    }
';

			self::assertEquals(
				$resource_file,
				file_get_contents( $resource )
			);

			self::assertFileExists( $collection );

			$collection_file = '<?php

    namespace App\Http\Resources\V1\Blog\Post;

    use Illuminate\Database\Eloquent\Model;
    use App\Http\Resources\Contracts\ValravnResourceCollection;

    class PostCollection extends ValravnResourceCollection {

        /**
         * Extract attributes of the given model
         * if null returned, the parent::toArray method called by default
         *
         * @param Model $model
         *
         * @return array|null
         */
        public function extract( Model $model ): ?array {
            return null;
        }

        /**
         * Specify the type of your resource
         *
         * @return string
         */
        public function type(): string {
            return \'posts\';
        }

    }
';

			self::assertEquals(
				$collection_file,
				file_get_contents( $collection )
			);

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