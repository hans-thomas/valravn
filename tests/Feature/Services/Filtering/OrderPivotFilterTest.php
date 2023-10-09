<?php

namespace Hans\Valravn\Tests\Feature\Services\Filtering;

use Hans\Valravn\Services\Filtering\FilteringService;
use Hans\Valravn\Tests\Core\Factories\CategoryFactory;
use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Models\Category;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\TestCase;

class OrderPivotFilterTest extends TestCase
{
    private FilteringService $service;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FilteringService::class);
        PostFactory::new()->create();
        CategoryFactory::new()->count(5)->create()->each(
            fn (Category $category) => $category->posts()->attach([1 => ['order' => rand(1, 100)]])
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function applyAsc(): void
    {
        request()->merge([
            'order_pivot_filter' => [
                'order' => 'asc',
            ],
        ]);
        $builder = $this->service->apply(Post::query()->find(1)->categories());

        self::assertStringContainsString(
            'order by "category_post"."order" asc',
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
            'order_pivot_filter' => [
                'order' => 'desc',
            ],
        ]);
        $builder = $this->service->apply(Post::query()->find(1)->categories());

        self::assertStringContainsString(
            'order by "category_post"."order" desc',
            $builder->toSql()
        );
    }
}