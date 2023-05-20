<?php

	namespace Hans\Tests\Valravn\Feature\Commands;


	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\File;

	class ControllerTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function crud(): void {
			$file = app_path( "Http/Controllers/V1/Blog/Post/PostCrudController.php" );
			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( 'valravn:controller blog posts' );

			self::assertFileExists( $file );

			$crud_file = '<?php

    namespace App\Http\Controllers\V1\Blog\Post;

    use App\DTOs\BatchUpdateDto;
    use App\Exceptions\ValravnException;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\V1\Blog\Post\PostBatchUpdateRequest;
    use App\Http\Requests\V1\Blog\Post\PostStoreRequest;
    use App\Http\Requests\V1\Blog\Post\PostUpdateRequest;
    use App\Http\Resources\V1\Blog\Post\PostCollection;
    use App\Http\Resources\V1\Blog\Post\PostResource;
    use App\Models\Blog\Post;
    use App\Services\Blog\Post\PostService;
    use Illuminate\Http\Resources\Json\JsonResource;
    use Illuminate\Http\Resources\Json\ResourceCollection;
    use Throwable;

    class PostCrudController extends Controller {
        private PostService $service;

        public function __construct() {
            $this->service    = app( PostService::class );
        }

        /**
         * Display a listing of the resource.
         *
         * @return ResourceCollection
         */
        public function index(): ResourceCollection {
            return Post::getResourceCollection( $this->service->all() );
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param PostStoreRequest $request
         *
         * @return JsonResource
         * @throws Throwable
         */
        public function store( PostStoreRequest $request ): JsonResource {
            return $this->service->create( $request->validated() )->toResource();
        }

        /**
         * Display the specified resource.
         *
         * @param int $post
         *
         * @return JsonResource
         */
        public function show( int $post ): JsonResource {
            return $this->service->find( $post )->toResource();
        }

        /**
         * Update the specified resource in storage.
         *
         * @param PostUpdateRequest $request
         * @param Post              $post
         *
         * @return JsonResource
         * @throws Throwable
         */
        public function update( PostUpdateRequest $request, Post $post ): JsonResource {
            return $this->service->update( $post, $request->validated() )->toResource();
        }

        /**
         * Batch update the specified resource in storage.
         *
         * @param PostBatchUpdateRequest $request
         *
         * @return ResourceCollection
         * @throws ValravnException
         */
        public function batchUpdate( PostBatchUpdateRequest $request ): ResourceCollection {
            return Post::getResourceCollection(
                $this->service->batchUpdate( BatchUpdateDto::make( $request->validated() ) )
            );
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param Post $post
         *
         * @return JsonResource
         * @throws ValravnException
         */
        public function destroy( Post $post ): JsonResource {
            return $this->service->delete( $post )->toResource();
        }
    }
';

			self::assertEquals(
				file_get_contents( $file ),
				$crud_file
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function relations(): void {
			$file = app_path( "Http/Controllers/V1/Blog/Post/PostRelationsController.php" );
			if ( File::exists( $file ) ) {
				unlink( $file );
			}
			self::assertFileDoesNotExist( $file );

			Artisan::call( 'valravn:controller blog posts --relations' );

			self::assertFileExists( $file );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function actions(): void {
			$file = app_path( "Http/Controllers/V1/Blog/Post/PostActionsController.php" );
			if ( File::exists( $file ) ) {
				unlink( $file );
			}
			self::assertFileDoesNotExist( $file );

			Artisan::call( 'valravn:controller blog posts --actions' );

			self::assertFileExists( $file );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function requests(): void {
			$store  = app_path( "Http/Requests/V1/Blog/Post/PostStoreRequest.php" );
			$update = app_path( "Http/Requests/V1/Blog/Post/PostUpdateRequest.php" );
			if ( File::exists( $store ) ) {
				unlink( $store );
			}
			if ( File::exists( $update ) ) {
				unlink( $update );
			}
			self::assertFileDoesNotExist( $store );
			self::assertFileDoesNotExist( $update );

			Artisan::call( 'valravn:controller blog posts --requests' );

			self::assertFileExists( $store );
			self::assertFileExists( $update );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function resources(): void {
			$store  = app_path( "Http/Resources/V1/Blog/Post/PostResource.php" );
			$update = app_path( "Http/Resources/V1/Blog/Post/PostCollection.php" );
			if ( File::exists( $store ) ) {
				unlink( $store );
			}
			if ( File::exists( $update ) ) {
				unlink( $update );
			}
			self::assertFileDoesNotExist( $store );
			self::assertFileDoesNotExist( $update );

			Artisan::call( 'valravn:controller blog posts --resources' );

			self::assertFileExists( $store );
			self::assertFileExists( $update );
		}

	}