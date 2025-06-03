<?php

namespace Hans\Valravn\Tests\Feature\Services\Filtering;

use Hans\Valravn\Services\Filtering\FilteringService;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class LikeFilterTest extends TestCase
{
    private FilteringService $service;

    #[Test]
    public function apply(): void
    {
        request()->merge([
            'like_filter' => [
                'title' => 'G-Eazy',
            ],
        ]);
        $builder = $this->service->apply(Post::query());

        self::assertStringContainsString(
            '"title" LIKE ?',
            $builder->toSql()
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FilteringService::class);
    }
}
