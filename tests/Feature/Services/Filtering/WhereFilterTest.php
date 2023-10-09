<?php

namespace Hans\Valravn\Tests\Feature\Services\Filtering;

use Hans\Valravn\Services\Filtering\FilteringService;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\TestCase;

class WhereFilterTest extends TestCase
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
            'where_filter' => [
                'content' => 'Looks sweet but the devil is in the details',
            ],
        ]);
        $builder = $this->service->apply(Post::query());

        self::assertStringContainsString(
            'where "posts"."content" in (?)',
            $builder->toSql()
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function applyWithMultipleValues(): void
    {
        request()->merge([
            'where_filter' => [
                'id' => '1,2,5',
            ],
        ]);
        $builder = $this->service->apply(Post::query());

        self::assertStringContainsString(
            'where "posts"."id" in (?, ?, ?)',
            $builder->toSql()
        );
    }
}