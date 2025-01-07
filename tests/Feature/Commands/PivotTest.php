<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;

class PivotTest extends TestCase
{
    #[Test]
    public function pivot(): void
    {
        $this->withoutMockingConsoleOutput();

        $datePrefix = now()->format('Y_m_d_His');
        $pivot = database_path("migrations/Blog/{$datePrefix}_create_category_post_table.php");

        self::assertFileDoesNotExist($pivot);

        Artisan::call('valravn:pivot blog posts core category');

        self::assertFileExists($pivot);

        $pivotStub = $this->getStub('migrations/pivot.stub');
        $pivotStub = str_replace('{{PIVOT::NAMESPACE}}', 'Blog', $pivotStub);
        $pivotStub = str_replace('{{PIVOT::MODEL}}', 'Post', $pivotStub);
        $pivotStub = str_replace('{{PIVOT::RELATED-NAMESPACE}}', 'Core', $pivotStub);
        $pivotStub = str_replace('{{PIVOT::RELATED-MODEL}}', 'Category', $pivotStub);
        $pivotStub = str_replace('{{PIVOT::FIRST-MODEL-SINGLE-LOWER}}', 'category', $pivotStub);
        $pivotStub = str_replace('{{PIVOT::SECOND-MODEL-SINGLE-LOWER}}', 'post', $pivotStub);

        self::assertEquals($pivotStub, file_get_contents($pivot));
        self::assertStringContainsString('pivot migration class successfully created!', Artisan::output());

        Artisan::call('valravn:pivot blog posts core category');
        self::assertStringContainsString('pivot migration class exists!', Artisan::output());
    }
}
