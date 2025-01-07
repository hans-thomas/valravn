<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;

class MigrationTests extends TestCase
{
    #[Test]
    public function migration(): void
    {
        $this->withoutMockingConsoleOutput();
        $this->freezeTime();

        $datePrefix = now()->format('Y_m_d_His');
        $file = base_path("database/migrations/Blog/{$datePrefix}_create_posts_table.php");

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:migration blog posts');

        self::assertFileExists($file);

        $migrationStub = $this->getStub('migrations/migration.stub');
        $migrationStub = str_replace('{{MODEL::NAMESPACE}}', 'Blog', $migrationStub);
        $migrationStub = str_replace('{{MODEL::CLASS}}', 'Post', $migrationStub);

        self::assertEquals($migrationStub, file_get_contents($file));
        self::assertStringContainsString('migration class successfully created!', Artisan::output());

        Artisan::call('valravn:migration blog posts');
        self::assertStringContainsString('migration class exists!', Artisan::output());
    }
}
