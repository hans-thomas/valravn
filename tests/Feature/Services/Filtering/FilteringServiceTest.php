<?php

namespace Hans\Valravn\Tests\Feature\Services\Filtering;

use Hans\Valravn\Services\Filtering\FilteringService;
use Hans\Valravn\Services\Filtering\Filters\LikeFilter;
use Hans\Valravn\Services\Filtering\Filters\OrderFilter;
use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class FilteringServiceTest extends TestCase
{
    private FilteringService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FilteringService::class);
        PostFactory::new()->count(5)->create();
    }

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function getRegistered(): void
    {
        self::assertEquals(
            valravn_config('filters'),
            $this->service->getRegistered()
        );
    }

    #[Test]
    public function withFilter(): void
    {
        request()->merge([
            'order_filter' => [
                'title' => 'desc',
            ],
        ]);

        $builder = $this->service->withFilter(LikeFilter::class, ['title' => 'value'])->apply(Post::query());

        self::assertStringContainsString(
            'order by "title" desc',
            $builder->toSql()
        );
        self::assertStringContainsString(
            '"title" LIKE ?',
            $builder->toSql()
        );
    }

    #[Test]
    public function withFilters(): void
    {
        $builder = $this->service->withFilters([
            OrderFilter::class => ['title' => 'desc'],
            LikeFilter::class  => ['title' => 'value'],
        ])->apply(Post::query());

        self::assertStringContainsString(
            'order by "title" desc',
            $builder->toSql()
        );
        self::assertStringContainsString(
            '"title" LIKE ?',
            $builder->toSql()
        );
    }

    #[Test]
    public function withFiltersWithNotRegisteredFilter(): void
    {
        $builder = $this->service->withFilters([
            OrderFilter::class => ['title' => 'desc'],
            Post::class        => ['title' => 'value'],
        ])->apply(Post::query());

        self::assertStringContainsString(
            'order by "title" desc',
            $builder->toSql()
        );
    }

    #[Test]
    public function withNoFilterAndNoRequest(): void
    {
        $builder = $this->service->apply(Post::query());

        self::assertStringContainsString(
            'select * from "posts"',
            $builder->toSql()
        );
    }
}
