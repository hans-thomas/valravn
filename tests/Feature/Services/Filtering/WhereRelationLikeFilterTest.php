<?php

namespace Hans\Valravn\Tests\Feature\Services\Filtering;

use Hans\Valravn\Services\Filtering\FilteringService;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class WhereRelationLikeFilterTest extends TestCase
{
    private FilteringService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FilteringService::class);
    }

    #[Test]
    public function apply(): void
    {
        request()->merge([
            'where_relation_like_filter' => [
                'categories->name' => 'Put away feelings I used to feel',
            ],
        ]);
        $builder = $this->service->apply(Post::query());

        self::assertStringContainsString(
            'and "name" LIKE ?',
            $builder->toSql()
        );
    }
}
