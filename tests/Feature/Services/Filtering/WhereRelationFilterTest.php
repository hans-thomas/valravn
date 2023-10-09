<?php

namespace Hans\Valravn\Tests\Feature\Services\Filtering;

use Hans\Valravn\Services\Filtering\FilteringService;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\TestCase;

class WhereRelationFilterTest extends TestCase
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
    public function apply(): void
    {
        request()->merge([
            'where_relation_filter' => [
                'categories->name' => 'Put away feelings I used to feel',
            ],
        ]);
        $builder = $this->service->apply(Post::query());

        self::assertStringContainsString(
            'and "name" = ?',
            $builder->toSql()
        );
    }
}
