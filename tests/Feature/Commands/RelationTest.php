<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class RelationTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function belongsToMany(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        File::delete($file);

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog Post core category --belongs-to-many');

        self::assertFileExists($file);

        $content = "<?php

    namespace App\Http\Requests\V1\Blog\Post;

    use App\Models\Core\Category;
    use Hans\Valravn\Http\Requests\Contracts\Relations\BelongsToManyRequest;

    class PostCategoriesRequest extends BelongsToManyRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
        protected function model(): string {
            return Category::class;
        }

		/**
		 * Check requested ids are exist
		 *
		 * @return Exists
		 */
        protected function pivots(): array {
            return [
                // Pivot columns validation rules go here
            ];
        }

    }
";
        self::assertEquals(
            $content,
            file_get_contents($file)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function belongsToManyWithPivot(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        $datePrefix = now()->format('Y_m_d_His');
        $pivot = database_path("migrations/Blog/{$datePrefix}_create_category_post_table.php");

        File::delete([$file, $pivot]);

        self::assertFileDoesNotExist($file);
        self::assertFileDoesNotExist($pivot);

        Artisan::call('valravn:relation blog Post core category --belongs-to-many --with-pivot');

        self::assertFileExists($file);
        self::assertFileExists($pivot);

        File::delete($pivot);
    }

    /**
     * @test
     *
     * @return void
     */
    public function belongsToManyWithVersion(): void
    {
        $file = app_path('Http/Requests/V3/Blog/Post/PostCategoriesRequest.php');

        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog Post core category --belongs-to-many --v 3');

        self::assertFileExists($file);

        $content = "<?php

    namespace App\Http\Requests\V3\Blog\Post;

    use App\Models\Core\Category;
    use Hans\Valravn\Http\Requests\Contracts\Relations\BelongsToManyRequest;

    class PostCategoriesRequest extends BelongsToManyRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
        protected function model(): string {
            return Category::class;
        }

		/**
		 * Check requested ids are exist
		 *
		 * @return Exists
		 */
        protected function pivots(): array {
            return [
                // Pivot columns validation rules go here
            ];
        }

    }
";
        self::assertEquals(
            $content,
            file_get_contents($file)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function hasMany(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog Post core category --has-many');

        self::assertFileExists($file);

        $content = "<?php

    namespace App\Http\Requests\V1\Blog\Post;

    use App\Models\Core\Category;
    use Hans\Valravn\Http\Requests\Contracts\Relations\HasManyRequest;

    class PostCategoriesRequest extends HasManyRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
        protected function model(): string {
            return Category::class;
        }

    }
";
        self::assertEquals(
            $content,
            file_get_contents($file)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function hasManyWithVersion(): void
    {
        $file = app_path('Http/Requests/V4/Blog/Post/PostCategoriesRequest.php');

        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog Post core category --has-many --v 4');

        self::assertFileExists($file);

        $content = "<?php

    namespace App\Http\Requests\V4\Blog\Post;

    use App\Models\Core\Category;
    use Hans\Valravn\Http\Requests\Contracts\Relations\HasManyRequest;

    class PostCategoriesRequest extends HasManyRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
        protected function model(): string {
            return Category::class;
        }

    }
";
        self::assertEquals(
            $content,
            file_get_contents($file)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function morphedByMany(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        File::delete($file);

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog post core category --morphed-by-many');

        self::assertFileExists($file);

        $content = "<?php

    namespace App\Http\Requests\V1\Blog\Post;

    use App\Models\Core\Category;
    use Hans\Valravn\Http\Requests\Contracts\Relations\MorphedByManyRequest;

    class PostCategoriesRequest extends MorphedByManyRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
        protected function model(): string {
            return Category::class;
        }

		/**
		 * Check requested ids are exist
		 *
		 * @return Exists
		 */
        protected function pivots(): array {
            return [
                // Pivot columns validation rules go here
            ];
        }

    }
";
        self::assertEquals(
            $content,
            file_get_contents($file)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function morphedByManyWithPivot(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        $datePrefix = now()->format('Y_m_d_His');
        $pivot = database_path("migrations/Blog/{$datePrefix}_create_category_post_table.php");

        File::delete([$file, $pivot]);

        self::assertFileDoesNotExist($file);
        self::assertFileDoesNotExist($pivot);

        Artisan::call('valravn:relation blog post core category --morphed-by-many --with-pivot');

        self::assertFileExists($file);
        self::assertFileExists($pivot);

        File::delete($pivot);
    }

    /**
     * @test
     *
     * @return void
     */
    public function morphedByManyWithVersion(): void
    {
        $file = app_path('Http/Requests/V6/Blog/Post/PostCategoriesRequest.php');

        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog post core category --morphed-by-many --v 6');

        self::assertFileExists($file);

        $content = "<?php

    namespace App\Http\Requests\V6\Blog\Post;

    use App\Models\Core\Category;
    use Hans\Valravn\Http\Requests\Contracts\Relations\MorphedByManyRequest;

    class PostCategoriesRequest extends MorphedByManyRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
        protected function model(): string {
            return Category::class;
        }

		/**
		 * Check requested ids are exist
		 *
		 * @return Exists
		 */
        protected function pivots(): array {
            return [
                // Pivot columns validation rules go here
            ];
        }

    }
";
        self::assertEquals(
            $content,
            file_get_contents($file)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function morphedToMany(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog post core category --morph-to-many');

        self::assertFileExists($file);

        $content = "<?php

    namespace App\Http\Requests\V1\Blog\Post;

    use App\Models\Core\Category;
    use Hans\Valravn\Http\Requests\Contracts\Relations\MorphToManyRequest;

    class PostCategoriesRequest extends MorphToManyRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
        protected function model(): string {
            return Category::class;
        }

		/**
		 * Check requested ids are exist
		 *
		 * @return Exists
		 */
        protected function pivots(): array {
            return [
                // Pivot columns validation rules go here
            ];
        }

    }
";
        self::assertEquals(
            $content,
            file_get_contents($file)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function morphedToManyWithPivot(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        $datePrefix = now()->format('Y_m_d_His');
        $pivot = database_path("migrations/Blog/{$datePrefix}_create_category_post_table.php");

        File::delete([$file, $pivot]);

        self::assertFileDoesNotExist($file);
        self::assertFileDoesNotExist($pivot);

        Artisan::call('valravn:relation blog post core category --morph-to-many --with-pivot');

        self::assertFileExists($file);
        self::assertFileExists($pivot);

        File::delete($pivot);
    }

    /**
     * @test
     *
     * @return void
     */
    public function morphedToManyWithVersion(): void
    {
        $file = app_path('Http/Requests/V8/Blog/Post/PostCategoriesRequest.php');

        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog post core category --morph-to-many --v 8');

        self::assertFileExists($file);

        $content = "<?php

    namespace App\Http\Requests\V8\Blog\Post;

    use App\Models\Core\Category;
    use Hans\Valravn\Http\Requests\Contracts\Relations\MorphToManyRequest;

    class PostCategoriesRequest extends MorphToManyRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
        protected function model(): string {
            return Category::class;
        }

		/**
		 * Check requested ids are exist
		 *
		 * @return Exists
		 */
        protected function pivots(): array {
            return [
                // Pivot columns validation rules go here
            ];
        }

    }
";
        self::assertEquals(
            $content,
            file_get_contents($file)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function morphTo(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Like/LikeLikableRequest.php');

        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog like likable --morph-to');

        self::assertFileExists($file);

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
                // Allowed entities go here
            ];
        }

    }
";
        self::assertEquals(
            $content,
            file_get_contents($file)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function morphToWithVersion(): void
    {
        $file = app_path('Http/Requests/V2/Blog/Like/LikeLikableRequest.php');

        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog like likable --morph-to --v 2');

        self::assertFileExists($file);

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
                // Allowed entities go here
            ];
        }

    }
";
        self::assertEquals(
            $content,
            file_get_contents($file)
        );
    }
}
