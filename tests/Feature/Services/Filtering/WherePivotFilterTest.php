<?php

namespace Hans\Valravn\Tests\Feature\Services\Filtering;

use Hans\Valravn\Services\Filtering\FilteringService;
use Hans\Valravn\Tests\Core\Factories\CategoryFactory;
use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Models\Category;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\TestCase;

class WherePivotFilterTest extends TestCase
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
    public function apply(): void
    {
        request()->merge([
            'where_pivot_filter' => [
                'order' => '30',
            ],
        ]);
        $builder = $this->service->apply(Post::query()->find(1)->categories());

        self::assertStringContainsString(
            '"category_post"."order" in (?)',
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
            'where_pivot_filter' => [
                'order' => '30,50',
            ],
        ]);
        $builder = $this->service->apply(Post::query()->find(1)->categories());

        self::assertStringContainsString(
            '"category_post"."order" in (?, ?)',
            $builder->toSql()
        );
    }
}
