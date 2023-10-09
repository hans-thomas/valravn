<?php

namespace Hans\Valravn\Tests\Feature\Services\Filtering;

use Hans\Valravn\Services\Filtering\FilteringService;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\TestCase;

class OrderFilterTest extends TestCase
{
    private FilteringService $service;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FilteringService::class);
    }

    /**
     * @test
     *
     * @return void
     */
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
    /**
     * @test
     *
     * @return void
     */
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
}