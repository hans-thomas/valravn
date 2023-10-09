<?php

namespace Hans\Valravn\Tests\Feature\Services\Filtering;

use Hans\Valravn\Services\Filtering\FilteringService;
use Hans\Valravn\Services\Filtering\Filters\LikeFilter;
use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\TestCase;

class FilteringServiceTest extends TestCase
{
    private FilteringService $service;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FilteringService::class);
        PostFactory::new()->count(5)->create();
    }

    /**
     * @test
     *
     * @return void
     */
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

    /**
     * @test
     *
     * @return void
     */
    public function applyWithOnly(): void
    {
        request()->merge([
            'like_filter'  => [
                'title' => 'One life to live, I would die for you',
            ],
            'order_filter' => [
                'title' => 'desc',
            ],
        ]);
        $builder = $this->service->apply(Post::query(), ['only' => LikeFilter::class]);

        self::assertStringContainsString(
            '"title" LIKE ?',
            $builder->toSql()
        );
        self::assertStringNotContainsString(
            'order by "title" desc',
            $builder->toSql()
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function applyWithExcept(): void
    {
        request()->merge([
            'order_filter' => [
                'title' => 'desc',
            ],
            'like_filter'  => [
                'title' => 'One life to live, I would die for you',
            ],
        ]);
        $builder = $this->service->apply(Post::query(), ['except' => LikeFilter::class]);

        self::assertStringContainsString(
            'order by "title" desc',
            $builder->toSql()
        );
        self::assertStringNotContainsString(
            '"title" LIKE ?',
            $builder->toSql()
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function getRegistered(): void
    {
        self::assertEquals(
            valravn_config('filters'),
            $this->service->getRegistered()
        );
    }
}
