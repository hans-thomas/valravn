<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Commands\Services\MigrationService;
use Hans\Valravn\Commands\Services\ModelService;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;

class MigrationsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->freezeTime();
        (new ModelService('blog', 'post'))->createModel();
    }

    protected function tearDown(): void
    {
        $this->cleanUp([
            base_path('database/migrations/Blog/'),
            app_path('Http/Requests/V1/Blog/Post/'),
        ]);

        parent::tearDown();
    }

    #[Test]
    public function migration(): void
    {
        $datePrefix = now()->format('Y_m_d_His');
        $file = base_path("database/migrations/Blog/{$datePrefix}_create_posts_table.php");

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:migration blog posts')
            ->expectsOutput('Migration file created.')
            ->doesntExpectOutput('Migration file exists or could not be created.')
            ->assertSuccessful();

        self::assertFileExists($file);

        $migrationStub = $this->getStub('migrations/migration.stub');
        $migrationStub = str_replace('{{MODEL::NAMESPACE}}', 'Blog', $migrationStub);
        $migrationStub = str_replace('{{MODEL::CLASS}}', 'Post', $migrationStub);

        self::assertEquals($migrationStub, file_get_contents($file));
    }

    #[Test]
    public function migrationExists(): void
    {
        $datePrefix = now()->format('Y_m_d_His');
        $file = base_path("database/migrations/Blog/{$datePrefix}_create_posts_table.php");

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:migration blog posts');

        $this->artisan('valravn:migration blog posts')
            ->doesntExpectOutput('Migration file created.')
            ->expectsOutput('Migration file exists or could not be created.')
            ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function pivot(): void
    {
        $datePrefix = now()->format('Y_m_d_His');
        $pivot = database_path("migrations/Blog/{$datePrefix}_create_category_post_table.php");

        self::assertFileDoesNotExist($pivot);

        $this->artisan('valravn:pivot blog posts core category')
            ->expectsOutput('Pivot migration file created.')
            ->doesntExpectOutput('Pivot migration file exists or could not be created.')
            ->expectsQuestion('Should create request for relationship?', false)
            ->assertSuccessful();

        self::assertFileExists($pivot);

        $pivotStub = $this->getStub('migrations/pivot.stub');
        $pivotStub = str_replace('{{PIVOT::NAMESPACE}}', 'Blog', $pivotStub);
        $pivotStub = str_replace('{{PIVOT::MODEL}}', 'Post', $pivotStub);
        $pivotStub = str_replace('{{PIVOT::RELATED-NAMESPACE}}', 'Core', $pivotStub);
        $pivotStub = str_replace('{{PIVOT::RELATED-MODEL}}', 'Category', $pivotStub);
        $pivotStub = str_replace('{{PIVOT::FIRST-MODEL-SINGLE-LOWER}}', 'category', $pivotStub);
        $pivotStub = str_replace('{{PIVOT::SECOND-MODEL-SINGLE-LOWER}}', 'post', $pivotStub);

        self::assertEquals($pivotStub, file_get_contents($pivot));
    }

    #[Test]
    public function pivotExists(): void
    {
        $datePrefix = now()->format('Y_m_d_His');
        $pivot = database_path("migrations/Blog/{$datePrefix}_create_category_post_table.php");

        self::assertFileDoesNotExist($pivot);

        $service = new MigrationService('blog','post');
        $service->createPivot('core', 'category');

        $this->artisan('valravn:pivot blog posts core category')
            ->doesntExpectOutput('Pivot migration file created.')
            ->expectsOutput('Pivot migration file exists or could not be created.')
            ->expectsQuestion('Should create request for relationship?', false)
            ->assertSuccessful();

        self::assertFileExists($pivot);
    }

    #[Test]
    public function pivotWithRequest(): void
    {
        $file = app_path('Http/Requests/V1/Blog/Post/PostCategoriesRequest.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:pivot blog posts core category')
            ->expectsOutput('Pivot migration file created.')
            ->doesntExpectOutput('Pivot migration file exists or could not be created.')
            ->expectsQuestion('Should create request for relationship?', true)
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
}
