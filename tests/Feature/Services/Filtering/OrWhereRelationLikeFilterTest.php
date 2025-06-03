<?php

namespace Hans\Valravn\Tests\Feature\Services\Filtering;

use Hans\Valravn\Services\Filtering\FilteringService;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class OrWhereRelationLikeFilterTest extends TestCase
{
    private FilteringService $service;

    #[Test]
    public function apply(): void
    {
        request()->merge([
            'where_relation_filter' => [
                'categories->id' => '1',
            ],
            'or_where_relation_like_filter' => [
                'categories->name' => 'Send em shots, just know Im hard to kill',
            ],
        ]);
        $builder = $this->service->apply(Post::query());

        self::assertStringContainsString(
            'and "id" = ?',
            $builder->toSql()
        );
        self::assertStringContainsString(
            'or exists (',
            $builder->toSql()
        );
        self::assertStringContainsString(
            'and "name" LIKE ?',
            $builder->toSql()
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FilteringService::class);
    }
}
