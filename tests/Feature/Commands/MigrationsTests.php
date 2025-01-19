<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;

class MigrationsTests extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->freezeTime();
        Artisan::call('valravn:model blog post');
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

        Artisan::call('valravn:pivot blog posts core category');

        $this->artisan('valravn:pivot blog posts core category')
             ->doesntExpectOutput('Pivot migration file created.')
             ->expectsOutput('Pivot migration file exists or could not be created.')
             ->assertSuccessful();

        self::assertFileExists($pivot);
    }
}
