<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;

class ModelTest extends TestCase
{
    #[Test]
    public function modelFile(): void
    {
        $file = app_path('Models/Blog/Post.php');

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:model blog posts');

        self::assertFileExists($file);

        $modelStub = $this->getStub('models/model.stub');
        $modelStub = str_replace('{{MODEL::NAMESPACE}}', 'Blog', $modelStub);
        $modelStub = str_replace('{{MODEL::CLASS}}', 'Post', $modelStub);
        $modelStub = str_replace('{{MODEL::TABLE}}', 'blog_posts', $modelStub);
        $modelStub = str_replace('{{MODEL::FOREIGNKEY}}', 'blog_post_id', $modelStub);

        self::assertEquals($modelStub, file_get_contents($file));
    }

    #[Test]
    public function factoryFile(): void
    {
        $file = base_path('database/factories/Blog/PostFactory.php');

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:model blog posts -f');

        self::assertFileExists($file);
    }

    #[Test]
    public function seederFile(): void
    {
        $file = base_path('database/seeders/Blog/PostSeeder.php');

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:model blog posts -s');

        self::assertFileExists($file);
    }

    #[Test]
    public function migrationFile(): void
    {
        $this->freezeTime();

        $datePrefix = now()->format('Y_m_d_His');
        $file = base_path("database/migrations/Blog/{$datePrefix}_create_posts_table.php");

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:model blog posts -m');

        self::assertFileExists($file);
    }
}
