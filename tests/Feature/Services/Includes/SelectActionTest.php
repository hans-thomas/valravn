<?php

namespace Hans\Valravn\Tests\Feature\Services\Includes;

use Hans\Valravn\Services\Includes\IncludingService;
use Hans\Valravn\Tests\Core\Factories\CategoryFactory;
use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Resources\Category\CategoryCollection;
use Hans\Valravn\Tests\Core\Resources\Post\PostResource;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;

class SelectActionTest extends TestCase
{
    private Collection $posts;
    private IncludingService $service;

    #[Test]
    public function apply(): void
    {
        $model = $this->posts->first();
        $data = $this->service->registerIncludesUsingQueryString('categories:select(id)')
            ->applyRequestedIncludes($model)
            ->getIncludedData();

        self::assertEquals(
            [
                'categories' => CategoryCollection::make($model->categories()->select('id')->get()),
            ],
            $data
        );
    }

    #[Test]
    public function applyWithNoParam(): void
    {
        $model = $this->posts->first();
        $data = $this->service->registerIncludesUsingQueryString('categories:select()')
            ->applyRequestedIncludes($model)
            ->getIncludedData();

        self::assertEquals(
            [
                'categories' => CategoryCollection::make($model->categories),
            ],
            $data
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->posts = PostFactory::new()
            ->count(3)
            ->has(CategoryFactory::new()->count(5))
            ->create();
        $resource = PostResource::make($this->posts->first());
        $this->service = app(IncludingService::class, ['resource' => $resource]);
    }
}
