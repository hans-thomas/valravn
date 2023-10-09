<?php

namespace Hans\Valravn\Tests\Feature\Services\Filtering;

use Hans\Valravn\Services\Filtering\FilteringService;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\TestCase;

class OrWhereRelationLikeFilterTest extends TestCase
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
            'or_where_relation_like_filter' => [
                'categories->name' => 'Send em shots, just know Im hard to kill',
            ],
        ]);
        $builder = $this->service->apply(Post::query());

        self::assertStringContainsString(
            'and "name" LIKE ?',
            $builder->toSql()
        );
    }
}
