<?php

namespace Hans\Valravn\Tests\Feature\Services\Filtering;

use Hans\Valravn\Services\Filtering\FilteringService;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class OrderFilterTest extends TestCase
{
    private FilteringService $service;

    #[Test]
    public function applyAsc(): void
    {
        request()->merge([
            'order_filter' => [
                'title' => 'asc',
            ],
        ]);
        $builder = $this->service->apply(Post::query());

        self::assertStringContainsString(
            'order by "title" asc',
            $builder->toSql()
        );
    }

    #[Test]
    public function applyDesc(): void
    {
        request()->merge([
            'order_filter' => [
                'title' => 'desc',
            ],
        ]);
        $builder = $this->service->apply(Post::query());

        self::assertStringContainsString(
            'order by "title" desc',
            $builder->toSql()
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FilteringService::class);
    }
}
