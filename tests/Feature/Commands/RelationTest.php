<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
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
            app_path('Http/Requests/V1/Blog/Like'),
            app_path('Http/Requests/V9/Blog/Like'),
        ]);

        parent::tearDown();
    }

    #[Test]
    public function relationWithChoiceBelongsToMany(): void
    {
        $this->artisan('valravn:relation blog Post core category')
             ->expectsQuestion('What relation type should create?', 'BelongsToManyRequest')
             ->expectsOutput('Relation BelongsToMany request class created.')
             ->assertSuccessful();
    }

    #[Test]
    public function relationWithChoiceMorphedByMany(): void
    {
        $this->artisan('valravn:relation blog Post core category')
             ->expectsQuestion('What relation type should create?', 'MorphedByManyRequest')
             ->expectsOutput('Relation MorphedByMany request class created.')
             ->assertSuccessful();
    }

    #[Test]
    public function relationWithChoiceMorphToMany(): void
    {
        $this->artisan('valravn:relation blog Post core category')
             ->expectsQuestion('What relation type should create?', 'MorphToManyRequest')
             ->expectsOutput('Relation MorphToMany request class created.')
             ->assertSuccessful();
    }

    #[Test]
    public function relationWithChoiceHasMany(): void
    {
        $this->artisan('valravn:relation blog Post core category')
             ->expectsQuestion('What relation type should create?', 'HasManyRequest')
             ->expectsOutput('Relation HasMany request class created.')
             ->assertSuccessful();
    }

    #[Test]
    public function relationWithChoiceMorphTo(): void
    {
        $this->artisan('valravn:relation blog Post core category')
             ->expectsQuestion('What relation type should create?', 'MorphToRequest')
             ->expectsOutput('Relation MorphTo request class created.')
             ->assertSuccessful();
    }

    #[Test]
    public function belongsToMany(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:relation blog Post core category --belongs-to-many')
             ->expectsOutput('Relation BelongsToMany request class created.')
             ->assertSuccessful();

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
    public function belongsToManyExists(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:relation blog Post core category --belongs-to-many')
             ->expectsOutput('Relation BelongsToMany request class created.')
             ->assertSuccessful();

        $this->artisan('valravn:relation blog Post core category --belongs-to-many')
             ->expectsOutput('Relation BelongsToMany request class exists or could not be created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function belongsToManyWithPivot(): void
    {
        $this->freezeTime();

        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        $datePrefix = now()->format('Y_m_d_His');
        $pivot = database_path("migrations/Blog/{$datePrefix}_create_category_post_table.php");

        self::assertFileDoesNotExist($file);
        self::assertFileDoesNotExist($pivot);

        $this->artisan('valravn:relation blog Post core category --belongs-to-many --with-pivot')
             ->expectsOutput('Relation BelongsToMany request class created.')
             ->expectsOutput('Pivot migration file created.')
             ->assertSuccessful();

        self::assertFileExists($file);
        self::assertFileExists($pivot);
    }

    #[Test]
    public function belongsToManyWithPivotExists(): void
    {
        $this->freezeTime();

        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        $datePrefix = now()->format('Y_m_d_His');
        $pivot = database_path("migrations/Blog/{$datePrefix}_create_category_post_table.php");

        self::assertFileDoesNotExist($file);
        self::assertFileDoesNotExist($pivot);

        $this->artisan('valravn:relation blog Post core category --belongs-to-many --with-pivot')
             ->expectsOutput('Relation BelongsToMany request class created.')
             ->expectsOutput('Pivot migration file created.')
             ->assertSuccessful();

        $this->artisan('valravn:relation blog Post core category --belongs-to-many --with-pivot')
             ->expectsOutput('Relation BelongsToMany request class exists or could not be created.')
             ->expectsOutput('Pivot migration file exists or could not be created.')
             ->assertSuccessful();

        self::assertFileExists($file);
        self::assertFileExists($pivot);
    }

    #[Test]
    public function belongsToManyWithVersion(): void
    {
        $file = app_path('Http/Requests/V3/Blog/Post/PostCategoriesRequest.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:relation blog Post core category --belongs-to-many --v 3')
             ->expectsOutput('Relation BelongsToMany request class created.')
             ->assertSuccessful();

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
    public function belongsToManyWithoutRelatedName(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:relation blog Post core --belongs-to-many')
             ->assertFailed();

        self::assertFileDoesNotExist($file);
    }

    #[Test]
    public function hasMany(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        File::delete($file);
        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:relation blog Post core category --has-many')
             ->expectsOutput('Relation HasMany request class created.')
             ->assertSuccessful();

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

        $this->artisan('valravn:relation blog Post core category --has-many --v 4')
             ->expectsOutput('Relation HasMany request class created.')
             ->assertSuccessful();

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

        $this->artisan('valravn:relation blog post core category --morphed-by-many')
             ->expectsOutput('Relation MorphedByMany request class created.')
             ->assertSuccessful();

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
        $this->freezeTime();

        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        $datePrefix = now()->format('Y_m_d_His');
        $pivot = database_path("migrations/Blog/{$datePrefix}_create_category_post_table.php");

        self::assertFileDoesNotExist($file);
        self::assertFileDoesNotExist($pivot);

        $this->artisan('valravn:relation blog post core category --morphed-by-many --with-pivot')
             ->expectsOutput('Relation MorphedByMany request class created.')
             ->expectsOutput('Pivot migration file created.')
             ->assertSuccessful();

        self::assertFileExists($file);
        self::assertFileExists($pivot);
    }

    #[Test]
    public function morphedByManyWithVersion(): void
    {
        $file = app_path('Http/Requests/V6/Blog/Post/PostCategoriesRequest.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:relation blog post core category --morphed-by-many --v 6')
             ->expectsOutput('Relation MorphedByMany request class created.')
             ->assertSuccessful();

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

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:relation blog post core category --morph-to-many')
             ->expectsOutput('Relation MorphToMany request class created.')
             ->assertSuccessful();

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
        $this->freezeTime();

        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        $datePrefix = now()->format('Y_m_d_His');
        $pivot = database_path("migrations/Blog/{$datePrefix}_create_category_post_table.php");

        self::assertFileDoesNotExist($file);
        self::assertFileDoesNotExist($pivot);

        $this->artisan('valravn:relation blog post core category --morph-to-many --with-pivot')
             ->expectsOutput('Relation MorphToMany request class created.')
             ->assertSuccessful();

        self::assertFileExists($file);
        self::assertFileExists($pivot);
    }

    #[Test]
    public function morphedToManyWithVersion(): void
    {
        $file = app_path('Http/Requests/V8/Blog/Post/PostCategoriesRequest.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:relation blog post core category --morph-to-many --v 8')
             ->expectsOutput('Relation MorphToMany request class created.')
             ->assertSuccessful();

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

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:relation blog like likable --morph-to')
             ->expectsOutput('Relation MorphTo request class created.')
             ->assertSuccessful();

        self::assertFileExists($file);

        $relationStub = $this->getStub('relations/morph-to.stub');
        $relationStub = str_replace('{{RELATION::VERSION}}', 'V1', $relationStub);
        $relationStub = str_replace('{{RELATION::NAMESPACE}}', 'Blog', $relationStub);
        $relationStub = str_replace('{{RELATION::MODEL}}', 'Like', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATION}}', 'Likable', $relationStub);

        self::assertEquals($relationStub, file_get_contents($file));
    }

    #[Test]
    public function morphToExists(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Like/LikeLikableRequest.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:relation blog like likable --morph-to')
             ->expectsOutput('Relation MorphTo request class created.')
             ->assertSuccessful();

        $this->artisan('valravn:relation blog like likable --morph-to')
             ->expectsOutput('Relation MorphTo request class exists or could not be created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function morphToWithVersion(): void
    {
        $file = app_path('Http/Requests/V9/Blog/Like/LikeLikableRequest.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:relation blog like likable --morph-to --v 9')
             ->expectsOutput('Relation MorphTo request class created.')
             ->assertSuccessful();

        self::assertFileExists($file);

        $relationStub = $this->getStub('relations/morph-to.stub');
        $relationStub = str_replace('{{RELATION::VERSION}}', 'V9', $relationStub);
        $relationStub = str_replace('{{RELATION::NAMESPACE}}', 'Blog', $relationStub);
        $relationStub = str_replace('{{RELATION::MODEL}}', 'Like', $relationStub);
        $relationStub = str_replace('{{RELATION::RELATION}}', 'Likable', $relationStub);

        self::assertEquals($relationStub, file_get_contents($file));
    }
}
