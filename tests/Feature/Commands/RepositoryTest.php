<?php

	namespace Hans\Valravn\Tests\Feature\Commands;

	use Hans\Valravn\Tests\TestCase;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\File;

	class RepositoryTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function repository(): void {
			$contract   = app_path( "Repositories/Contracts/Blog/IPostRepository.php" );
			$repository = app_path( "Repositories/Blog/PostRepository.php" );

			File::delete( [ $contract, $repository ] );
			self::assertFileDoesNotExist( $contract );
			self::assertFileDoesNotExist( $repository );

			Artisan::call( "valravn:repository blog posts" );

			self::assertFileExists( $contract );

			$contract_file = '<?php

    namespace App\Repositories\Contracts\Blog;

    use App\Models\Blog\Post;
    use App\Repositories\Contracts\Repository;

    abstract class IPostRepository extends Repository {

    }
';

			self::assertEquals(
				$contract_file,
				file_get_contents( $contract )
			);

			self::assertFileExists( $repository );

			$repository_file = '<?php

    namespace App\Repositories\Blog;

    use App\Models\Blog\Post;
    use App\Repositories\Contracts\Blog\IPostRepository;
    use Illuminate\Contracts\Database\Eloquent\Builder;
    use Illuminate\Support\Facades\Gate;

    class PostRepository extends IPostRepository {

        protected function getQueryBuilder(): Builder {
            return Post::query();
        }

    }
';

			self::assertEquals(
				$repository_file,
				file_get_contents( $repository )
			);

		}

	}