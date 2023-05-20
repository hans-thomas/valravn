<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\File;

	class ModelTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function model(): void {
			$file = app_path( "Models/Blog/Post.php" );
			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:model blog posts" );

			self::assertFileExists( $file );

			$model_file = '<?php

    namespace App\Models\Blog;

    use App\Models\BaseModel;
    use App\Models\Contracts\EntityClasses;
    use App\Models\Contracts\Filtering\Filterable;
    use App\Models\Contracts\Filtering\Loadable;
    use App\Models\Contracts\ResourceCollectionable;
    use App\Models\Traits\Paginatable;
    use App\Repositories\Contracts\Repository;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Http\Resources\Json\JsonResource;
    use Illuminate\Http\Resources\Json\ResourceCollection;

    class Post extends BaseModel implements Filterable, Loadable, ResourceCollectionable, EntityClasses {
        use HasFactory;
        use Paginatable;

        protected $table = \'blog_posts\';
        protected $fillable = [ ];

        public function getForeignKey() {
            return \'blog_post_id\';
        }

        public function getRepository(): Repository {
            // TODO: Implement getRepository() method.
        }

        public function getService(): object {
            // TODO: Implement getService() method.
        }

        public function getRelationsService(): object {
            // TODO: Implement getRelationsService() method.
        }

        public function getFilterableAttributes(): array {
            // TODO: Implement getFilterableAttributes() method.
        }

        public function getLoadableRelations(): array {
            // TODO: Implement getLoadableRelations() method.
        }

        public static function getResource(): JsonResource {
            // TODO: Implement getResource() method.
        }

        public function toResource(): JsonResource {
            // TODO: Implement toResource() method.
        }

        public static function getResourceCollection(): ResourceCollection {
            // TODO: Implement getResourceCollection() method.
        }
    }
';

			self::assertEquals(
				file_get_contents( $file ),
				$model_file
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function factory(): void {
			$file = base_path( "database/factories/Blog/PostFactory.php" );
			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:model blog posts -f" );

			self::assertFileExists( $file );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function seeder(): void {
			$file = base_path( "database/seeders/Blog/PostSeeder.php" );
			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:model blog posts -s" );

			self::assertFileExists( $file );
		}
		/**
		 * @test
		 *
		 * @return void
		 */
		public function migration(): void {
			$datePrefix = now()->format( 'Y_m_d_His' );
			$file       = base_path( "database/migrations/Blog/{$datePrefix}_create_posts_table.php" );
			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:model blog posts -m" );

			self::assertFileExists( $file );
		}

	}