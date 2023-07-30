<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ServiceTests extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function crud(): void
    {
        $crud = app_path('Services/Blog/Post/PostCrudService.php');

        File::delete($crud);
        self::assertFileDoesNotExist($crud);

        Artisan::call('valravn:service blog posts');

        self::assertFileExists($crud);

        $crud_file = '<?php

    namespace App\Services\Blog\Post;

    use App\DTOs\BatchUpdateDto;
    use App\Exceptions\Blog\Post\PostException;
    use App\Models\Blog\Post;
    use App\Repositories\Contracts\Blog\IPostRepository;
    use Hans\Valravn\Services\Contracts\Service;
    use Illuminate\Contracts\Pagination\Paginator;
    use Illuminate\Support\Facades\DB;
    use Throwable;

    class PostService extends Service {
        private IPostRepository $repository;

        public function __construct() {
            $this->repository = app( IPostRepository::class );
        }

        public function all(): Paginator {
            return $this->repository->all()->applyFilters()->paginate();
        }

        public function create( array $data ): Post {
            DB::beginTransaction();
            try {
                throw_unless( $model = $this->repository->create( $data ), PostException::failedToCreate() );
            } catch ( Throwable $e ) {
                DB::rollBack();
                throw $e;
            }
            DB::commit();

            return $model;
        }

        public function find( int|string $model ): Post {
            return $this->repository->find( $model, is_numeric( $model ) ? \'id\' : \'slug\' );
        }

        public function update( Post $model, array $data ): Post {
            if ( $this->repository->update( $model, $data ) ) {
                return $model;
            }

            throw PostException::failedToUpdate();
        }

        public function batchUpdate( BatchUpdateDto $dto ): Paginator {
            if ( $this->repository->batchUpdate( $dto ) ) {
                return $this->repository->all()
                                        ->whereIn( \'id\', $dto->getData()->pluck( \'id\' ) )
                                        ->applyFilters()
                                        ->paginate();
            }

            throw PostException::failedToBatchUpdate();
        }

        public function delete( Post $model ): Post {
            if ( $this->repository->delete( $model ) ) {
                return $model;
            }

            throw PostException::failedToDelete();
        }
    }
';

        self::assertEquals(
            $crud_file,
            file_get_contents($crud)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function relations(): void
    {
        $relations = app_path('Services/Blog/Post/PostRelationsService.php');

        File::delete($relations);
        self::assertFileDoesNotExist($relations);

        Artisan::call('valravn:service blog posts -r');

        self::assertFileExists($relations);

        $relations_file = '<?php

    namespace App\Services\Blog\Post;

    use App\Exceptions\Blog\Post\PostException;
    use App\Models\Blog\Post;
    use App\Repositories\Contracts\Blog\IPostRepository;
    use Hans\Valravn\Services\Contracts\Service;
    use Illuminate\Contracts\Pagination\Paginator;
    use Illuminate\Support\Facades\DB;
    use Throwable;

    class PostRelationsService extends Service {
        private IPostRepository $repository;

        public function __construct() {
            $this->repository = app( IPostRepository::class );
        }

    }
';

        self::assertEquals(
            $relations_file,
            file_get_contents($relations)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function actions(): void
    {
        $actions = app_path('Services/Blog/Post/PostActionsService.php');

        File::delete($actions);
        self::assertFileDoesNotExist($actions);

        Artisan::call('valravn:service blog posts -a');

        self::assertFileExists($actions);

        $actions_file = '<?php

    namespace App\Services\Blog\Post;

    use App\Exceptions\Blog\Post\PostException;
    use App\Models\Blog\Post;
    use App\Repositories\Contracts\Blog\IPostRepository;
    use Hans\Valravn\Services\Contracts\Service;
    use Illuminate\Contracts\Pagination\Paginator;
    use Illuminate\Support\Facades\DB;
    use Throwable;

    class PostActionsService extends Service {
        private IPostRepository $repository;

        public function __construct() {
            $this->repository = app( IPostRepository::class );
        }

    }
';

        self::assertEquals(
            $actions_file,
            file_get_contents($actions)
        );
    }
}
