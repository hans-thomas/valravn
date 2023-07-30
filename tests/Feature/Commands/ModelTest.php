<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ModelTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function model(): void
    {
        $file = app_path('Models/Blog/Post.php');
        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:model blog posts');

        self::assertFileExists($file);

        $model_file = '<?php

    namespace App\Models\Blog;

	use App\Models\Contracts\EntityClasses;
	use App\Models\Contracts\Filterable;
	use App\Models\Contracts\Loadable;
	use App\Models\Contracts\ResourceCollectionable;
	use App\Models\Traits\Paginatable;
	use App\Models\ValravnModel;
	use App\Repositories\Contracts\Repository;
	use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
	use Hans\Valravn\Http\Resources\Contracts\ValravnResourceCollection;
	use Hans\Valravn\Services\Contracts\Service;
	use Illuminate\Database\Eloquent\Factories\HasFactory;

    class Post extends ValravnModel implements Filterable, Loadable, ResourceCollectionable, EntityClasses {
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

        public function getService(): Service {
            // TODO: Implement getService() method.
        }

        public function getRelationsService(): Service {
            // TODO: Implement getRelationsService() method.
        }

        public function getFilterableAttributes(): array {
            // TODO: Implement getFilterableAttributes() method.
        }

        public function getLoadableRelations(): array {
            // TODO: Implement getLoadableRelations() method.
        }

        public static function getResource(): ValravnJsonResource {
            // TODO: Implement getResource() method.
        }

        public function toResource(): ValravnJsonResource {
            // TODO: Implement toResource() method.
        }

        public static function getResourceCollection(): ValravnResourceCollection {
            // TODO: Implement getResourceCollection() method.
        }
    }
';

        self::assertEquals(
            $model_file,
            file_get_contents($file)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function factory(): void
    {
        $file = base_path('database/factories/Blog/PostFactory.php');
        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:model blog posts -f');

        self::assertFileExists($file);
    }

    /**
     * @test
     *
     * @return void
     */
    public function seeder(): void
    {
        $file = base_path('database/seeders/Blog/PostSeeder.php');
        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:model blog posts -s');

        self::assertFileExists($file);
    }

    /**
     * @test
     *
     * @return void
     */
    public function migration(): void
    {
        $datePrefix = now()->format('Y_m_d_His');
        $file = base_path("database/migrations/Blog/{$datePrefix}_create_posts_table.php");
        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:model blog posts -m');

        self::assertFileExists($file);
        File::delete($file);
    }
}
