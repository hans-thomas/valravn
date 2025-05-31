<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ModelTest extends TestCase
{
    #[Test]
    public function modelFile(): void
    {
        $file = app_path('Models/Blog/Post.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:model blog posts')
            ->expectsOutput('Model class created.')
            ->expectsQuestion('Should create factory?', false)
            ->doesntExpectOutput('Factory class created.')
            ->expectsQuestion('Should create seeder?', false)
            ->doesntExpectOutput('Seeder class created.')
            ->expectsQuestion('Should create migration?', false)
            ->doesntExpectOutput('Migration class created.')
             ->assertSuccessful();

        self::assertFileExists($file);

        $modelStub = $this->getStub('models/model.stub');
        $modelStub = str_replace('{{MODEL::NAMESPACE}}', 'Blog', $modelStub);
        $modelStub = str_replace('{{MODEL::CLASS}}', 'Post', $modelStub);
        $modelStub = str_replace('{{MODEL::TABLE}}', 'blog_posts', $modelStub);
        $modelStub = str_replace('{{MODEL::FOREIGN_KEY}}', 'blog_post_id', $modelStub);

        self::assertEquals($modelStub, file_get_contents($file));
    }

    #[Test]
    public function modelFileExists(): void
    {
        $file = app_path('Models/Blog/Post.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:model blog posts')
            ->expectsOutput('Model class created.')
            ->expectsQuestion('Should create factory?', false)
            ->doesntExpectOutput('Factory class created.')
            ->expectsQuestion('Should create seeder?', false)
            ->doesntExpectOutput('Seeder class created.')
            ->expectsQuestion('Should create migration?', false)
            ->doesntExpectOutput('Migration class created.')
             ->assertSuccessful();

        $this->artisan('valravn:model blog posts')
             ->expectsOutput('Model class exists or could not be created.')
             ->expectsQuestion('Should create factory?', false)
             ->doesntExpectOutput('Factory class created.')
             ->expectsQuestion('Should create seeder?', false)
             ->doesntExpectOutput('Seeder class created.')
             ->expectsQuestion('Should create migration?', false)
             ->doesntExpectOutput('Migration class created.')
             ->assertSuccessful();

        self::assertFileExists($file);

        $modelStub = $this->getStub('models/model.stub');
        $modelStub = str_replace('{{MODEL::NAMESPACE}}', 'Blog', $modelStub);
        $modelStub = str_replace('{{MODEL::CLASS}}', 'Post', $modelStub);
        $modelStub = str_replace('{{MODEL::TABLE}}', 'blog_posts', $modelStub);
        $modelStub = str_replace('{{MODEL::FOREIGN_KEY}}', 'blog_post_id', $modelStub);

        self::assertEquals($modelStub, file_get_contents($file));
    }

    #[Test]
    public function factoryFile(): void
    {
        $file = base_path('database/factories/Blog/PostFactory.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:model blog posts -f')
             ->expectsOutput('Model class created.')
             ->expectsOutput('Factory class created.')
             ->expectsQuestion('Should create seeder?', false)
             ->doesntExpectOutput('Seeder class created.')
             ->expectsQuestion('Should create migration?', false)
             ->doesntExpectOutput('Migration class created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function factoryFileExists(): void
    {
        $file = base_path('database/factories/Blog/PostFactory.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:model blog posts -f')
             ->expectsOutput('Model class created.')
             ->expectsOutput('Factory class created.')
             ->expectsQuestion('Should create seeder?', false)
             ->doesntExpectOutput('Seeder class created.')
             ->expectsQuestion('Should create migration?', false)
             ->doesntExpectOutput('Migration class created.')
             ->assertSuccessful();

        $this->artisan('valravn:model blog posts -f')
             ->expectsOutput('Model class exists or could not be created.')
             ->expectsOutput('Factory class exists or could not be created.')
             ->expectsQuestion('Should create seeder?', false)
             ->doesntExpectOutput('Seeder class created.')
             ->expectsQuestion('Should create migration?', false)
             ->doesntExpectOutput('Migration class created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function factoryFileWithoutParam(): void
    {
        $file = base_path('database/factories/Blog/PostFactory.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:model blog posts')
             ->expectsOutput('Model class created.')
             ->expectsQuestion('Should create factory?', true)
             ->expectsOutput('Factory class created.')
             ->expectsQuestion('Should create seeder?', false)
             ->doesntExpectOutput('Seeder class created.')
             ->expectsQuestion('Should create migration?', false)
             ->doesntExpectOutput('Migration class created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function seederFile(): void
    {
        $file = base_path('database/seeders/Blog/PostSeeder.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:model blog posts -s')
             ->expectsOutput('Model class created.')
             ->expectsQuestion('Should create factory?', false)
             ->doesntExpectOutput('Factory class created.')
             ->expectsOutput('Seeder class created.')
             ->expectsQuestion('Should create migration?', false)
             ->doesntExpectOutput('Migration class created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function seederFileExists(): void
    {
        $file = base_path('database/seeders/Blog/PostSeeder.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:model blog posts -s')
             ->expectsOutput('Model class created.')
             ->expectsQuestion('Should create factory?', false)
             ->doesntExpectOutput('Factory class created.')
             ->expectsOutput('Seeder class created.')
             ->expectsQuestion('Should create migration?', false)
             ->doesntExpectOutput('Migration class created.')
             ->assertSuccessful();

        $this->artisan('valravn:model blog posts -s')
             ->expectsOutput('Model class exists or could not be created.')
             ->expectsQuestion('Should create factory?', false)
             ->doesntExpectOutput('Factory class created.')
             ->expectsOutput('Seeder class exists or could not be created.')
             ->expectsQuestion('Should create migration?', false)
             ->doesntExpectOutput('Migration class created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function seederFileWithoutParam(): void
    {
        $file = base_path('database/seeders/Blog/PostSeeder.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:model blog posts')
             ->expectsOutput('Model class created.')
             ->expectsQuestion('Should create factory?', false)
             ->doesntExpectOutput('Factory class created.')
             ->expectsQuestion('Should create seeder?', true)
             ->expectsOutput('Seeder class created.')
             ->expectsQuestion('Should create migration?', false)
             ->doesntExpectOutput('Migration class created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function migrationFile(): void
    {
        $this->freezeTime();

        $datePrefix = now()->format('Y_m_d_His');
        $file = base_path("database/migrations/Blog/{$datePrefix}_create_posts_table.php");

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:model blog posts -m')
             ->expectsOutput('Model class created.')
             ->expectsQuestion('Should create factory?', false)
             ->doesntExpectOutput('Factory class created.')
             ->expectsQuestion('Should create seeder?', false)
             ->doesntExpectOutput('Seeder class created.')
             ->expectsOutput('Migration file created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }

    #[Test]
    public function migrationFileWithoutParam(): void
    {
        $this->freezeTime();

        $datePrefix = now()->format('Y_m_d_His');
        $file = base_path("database/migrations/Blog/{$datePrefix}_create_posts_table.php");

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:model blog posts')
             ->expectsOutput('Model class created.')
             ->expectsQuestion('Should create factory?', false)
             ->doesntExpectOutput('Factory class created.')
             ->expectsQuestion('Should create seeder?', false)
             ->doesntExpectOutput('Seeder class created.')
             ->expectsQuestion('Should create migration?', true)
             ->doesntExpectOutput('Migration class created.')
             ->assertSuccessful();

        self::assertFileExists($file);
    }
}
