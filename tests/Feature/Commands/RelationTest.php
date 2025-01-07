<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;

class RelationTest extends TestCase
{
    protected function tearDown(): void
    {
        $this->cleanUp([
            app_path('Http/Requests/V3/Blog/Post'),
            app_path('Http/Requests/V4/Blog/Post'),
            app_path('Http/Requests/V6/Blog/Post'),
            app_path('Http/Requests/V8/Blog/Post'),
            app_path('Http/Requests/V9/Blog/Like'),
        ]);

        parent::tearDown();
    }

    #[Test]
    public function belongsToMany(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog Post core category --belongs-to-many');

        self::assertFileExists($file);

        $relationStub = $this->getStub('relations/many-to-many.stub');
        $relationStub = str_replace('{{RELATION::VERSION}}', 'V1', $relationStub);
        $relationStub = str_replace('{{RELATION::NAMESPACE}}', 'Blog', $relationStub);
        $relationStub = str_replace('{{RELATION::MODEL}}', 'Post', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-NAMESPACE}}', 'Core', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-MODEL}}', 'Category', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATION}}', 'Categories', $relationStub);
        $relationStub = str_replace('{{RELATION::EXTENDS}}', 'BelongsToManyRequest', $relationStub);

        self::assertEquals($relationStub, file_get_contents($file));
    }

    #[Test]
    public function belongsToManyWithPivot(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        $datePrefix = now()->format('Y_m_d_His');
        $pivot = database_path("migrations/Blog/{$datePrefix}_create_category_post_table.php");

        self::assertFileDoesNotExist($file);
        self::assertFileDoesNotExist($pivot);

        Artisan::call('valravn:relation blog Post core category --belongs-to-many --with-pivot');

        self::assertFileExists($file);
        self::assertFileExists($pivot);
    }

    #[Test]
    public function belongsToManyWithVersion(): void
    {
        $file = app_path('Http/Requests/V3/Blog/Post/PostCategoriesRequest.php');

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog Post core category --belongs-to-many --v 3');

        self::assertFileExists($file);

        $relationStub = $this->getStub('relations/many-to-many.stub');
        $relationStub = str_replace('{{RELATION::VERSION}}', 'V3', $relationStub);
        $relationStub = str_replace('{{RELATION::NAMESPACE}}', 'Blog', $relationStub);
        $relationStub = str_replace('{{RELATION::MODEL}}', 'Post', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-NAMESPACE}}', 'Core', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-MODEL}}', 'Category', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATION}}', 'Categories', $relationStub);
        $relationStub = str_replace('{{RELATION::EXTENDS}}', 'BelongsToManyRequest', $relationStub);

        self::assertEquals($relationStub, file_get_contents($file));
    }

    #[Test]
    public function hasMany(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog Post core category --has-many');

        self::assertFileExists($file);

        $relationStub = $this->getStub('relations/has-many.stub');
        $relationStub = str_replace('{{RELATION::VERSION}}', 'V1', $relationStub);
        $relationStub = str_replace('{{RELATION::NAMESPACE}}', 'Blog', $relationStub);
        $relationStub = str_replace('{{RELATION::MODEL}}', 'Post', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-NAMESPACE}}', 'Core', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-MODEL}}', 'Category', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATION}}', 'Categories', $relationStub);
        $relationStub = str_replace('{{RELATION::EXTENDS}}', 'HasManyRequest', $relationStub);

        self::assertEquals($relationStub, file_get_contents($file));
    }

    #[Test]
    public function hasManyWithVersion(): void
    {
        $file = app_path('Http/Requests/V4/Blog/Post/PostCategoriesRequest.php');

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog Post core category --has-many --v 4');

        self::assertFileExists($file);

        $relationStub = $this->getStub('relations/has-many.stub');
        $relationStub = str_replace('{{RELATION::VERSION}}', 'V4', $relationStub);
        $relationStub = str_replace('{{RELATION::NAMESPACE}}', 'Blog', $relationStub);
        $relationStub = str_replace('{{RELATION::MODEL}}', 'Post', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-NAMESPACE}}', 'Core', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-MODEL}}', 'Category', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATION}}', 'Categories', $relationStub);
        $relationStub = str_replace('{{RELATION::EXTENDS}}', 'HasManyRequest', $relationStub);

        self::assertEquals($relationStub, file_get_contents($file));
    }

    #[Test]
    public function morphedByMany(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog post core category --morphed-by-many');

        self::assertFileExists($file);

        $relationStub = $this->getStub('relations/many-to-many.stub');
        $relationStub = str_replace('{{RELATION::VERSION}}', 'V1', $relationStub);
        $relationStub = str_replace('{{RELATION::NAMESPACE}}', 'Blog', $relationStub);
        $relationStub = str_replace('{{RELATION::MODEL}}', 'Post', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-NAMESPACE}}', 'Core', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-MODEL}}', 'Category', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATION}}', 'Categories', $relationStub);
        $relationStub = str_replace('{{RELATION::EXTENDS}}', 'MorphedByManyRequest', $relationStub);

        self::assertEquals($relationStub, file_get_contents($file));
    }

    #[Test]
    public function morphedByManyWithPivot(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        $datePrefix = now()->format('Y_m_d_His');
        $pivot = database_path("migrations/Blog/{$datePrefix}_create_category_post_table.php");

        self::assertFileDoesNotExist($file);
        self::assertFileDoesNotExist($pivot);

        Artisan::call('valravn:relation blog post core category --morphed-by-many --with-pivot');

        self::assertFileExists($file);
        self::assertFileExists($pivot);
    }

    #[Test]
    public function morphedByManyWithVersion(): void
    {
        $file = app_path('Http/Requests/V6/Blog/Post/PostCategoriesRequest.php');

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog post core category --morphed-by-many --v 6');

        self::assertFileExists($file);

        $relationStub = $this->getStub('relations/many-to-many.stub');
        $relationStub = str_replace('{{RELATION::VERSION}}', 'V6', $relationStub);
        $relationStub = str_replace('{{RELATION::NAMESPACE}}', 'Blog', $relationStub);
        $relationStub = str_replace('{{RELATION::MODEL}}', 'Post', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-NAMESPACE}}', 'Core', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-MODEL}}', 'Category', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATION}}', 'Categories', $relationStub);
        $relationStub = str_replace('{{RELATION::EXTENDS}}', 'MorphedByManyRequest', $relationStub);

        self::assertEquals($relationStub, file_get_contents($file));
    }

    #[Test]
    public function morphedToMany(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog post core category --morph-to-many');

        self::assertFileExists($file);

        $relationStub = $this->getStub('relations/many-to-many.stub');
        $relationStub = str_replace('{{RELATION::VERSION}}', 'V1', $relationStub);
        $relationStub = str_replace('{{RELATION::NAMESPACE}}', 'Blog', $relationStub);
        $relationStub = str_replace('{{RELATION::MODEL}}', 'Post', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-NAMESPACE}}', 'Core', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-MODEL}}', 'Category', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATION}}', 'Categories', $relationStub);
        $relationStub = str_replace('{{RELATION::EXTENDS}}', 'MorphToManyRequest', $relationStub);

        self::assertEquals($relationStub, file_get_contents($file));
    }

    #[Test]
    public function morphedToManyWithPivot(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        $datePrefix = now()->format('Y_m_d_His');
        $pivot = database_path("migrations/Blog/{$datePrefix}_create_category_post_table.php");

        self::assertFileDoesNotExist($file);
        self::assertFileDoesNotExist($pivot);

        Artisan::call('valravn:relation blog post core category --morph-to-many --with-pivot');

        self::assertFileExists($file);
        self::assertFileExists($pivot);
    }

    #[Test]
    public function morphedToManyWithVersion(): void
    {
        $file = app_path('Http/Requests/V8/Blog/Post/PostCategoriesRequest.php');

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog post core category --morph-to-many --v 8');

        self::assertFileExists($file);

        $relationStub = $this->getStub('relations/many-to-many.stub');
        $relationStub = str_replace('{{RELATION::VERSION}}', 'V8', $relationStub);
        $relationStub = str_replace('{{RELATION::NAMESPACE}}', 'Blog', $relationStub);
        $relationStub = str_replace('{{RELATION::MODEL}}', 'Post', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-NAMESPACE}}', 'Core', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATED-MODEL}}', 'Category', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATION}}', 'Categories', $relationStub);
        $relationStub = str_replace('{{RELATION::EXTENDS}}', 'MorphToManyRequest', $relationStub);

        self::assertEquals($relationStub, file_get_contents($file));
    }

    #[Test]
    public function morphTo(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Like/LikeLikableRequest.php');

        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog like likable --morph-to');

        self::assertFileExists($file);

        $relationStub = $this->getStub('relations/morph-to.stub');
        $relationStub = str_replace('{{RELATION::VERSION}}', 'V1', $relationStub);
        $relationStub = str_replace('{{RELATION::NAMESPACE}}', 'Blog', $relationStub);
        $relationStub = str_replace('{{RELATION::MODEL}}', 'Like', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATION}}', 'Likable', $relationStub);

        self::assertEquals($relationStub, file_get_contents($file));
    }

    #[Test]
    public function morphToWithVersion(): void
    {
        $file = app_path('Http/Requests/V9/Blog/Like/LikeLikableRequest.php');

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:relation blog like likable --morph-to --v 9');

        self::assertFileExists($file);

        $relationStub = $this->getStub('relations/morph-to.stub');
        $relationStub = str_replace('{{RELATION::VERSION}}', 'V9', $relationStub);
        $relationStub = str_replace('{{RELATION::NAMESPACE}}', 'Blog', $relationStub);
        $relationStub = str_replace('{{RELATION::MODEL}}', 'Like', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATION}}', 'Likable', $relationStub);

        self::assertEquals($relationStub, file_get_contents($file));
    }
}
