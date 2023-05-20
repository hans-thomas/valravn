<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
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
				file_get_contents( $contract ),
				$contract_file
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
				file_get_contents( $repository ),
				$repository_file
			);

		}

	}