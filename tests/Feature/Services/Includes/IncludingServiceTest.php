<?php

namespace Hans\Valravn\Tests\Feature\Services\Includes;

use Hans\Valravn\Http\Resources\Contracts\VJsonResource;
use Hans\Valravn\Services\Includes\Actions\LimitAction;
use Hans\Valravn\Services\Includes\Actions\OrderAction;
use Hans\Valravn\Services\Includes\Actions\SelectAction;
use Hans\Valravn\Services\Includes\IncludingService;
use Hans\Valravn\Tests\Core\Factories\CategoryFactory;
use Hans\Valravn\Tests\Core\Factories\CommentFactory;
use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Resources\Post\PostResource;
use Hans\Valravn\Tests\Instances\Http\Includes\CategoriesVIncludes;
use Hans\Valravn\Tests\Instances\Http\Includes\CommentsVIncludes;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;

class IncludingServiceTest extends TestCase
{
    private Collection $posts;
    private IncludingService $service;
    private VJsonResource $resource;

    #[Test]
    public function getRequestedIncludesAsNotLoadableRelations(): void
    {
        $this->service->registerIncludesUsingQueryString('users');
        self::assertEquals(
            [],
            $this->resource->getRequestedIncludes()
        );
    }

    #[Test]
    public function getRequestedIncludes(): void
    {
        $this->service->registerIncludesUsingQueryString('categories.posts,comments.post.comments');
        self::assertEquals(
            [
                CategoriesVIncludes::class => [],
                CommentsVIncludes::class   => [],
            ],
            $this->resource->getRequestedIncludes()
        );
    }

    #[Test]
    public function getRequestedIncludesAsNull(): void
    {
        $this->service->registerIncludesUsingQueryString(null);
        self::assertEquals(
            [],
            $this->resource->getRequestedIncludes()
        );
    }

    #[Test]
    public function getRequestedIncludesAsCondition(): void
    {
        $this->service->registerIncludesUsingQueryStringWhen(true, 'categories');
        self::assertEquals(
            [CategoriesVIncludes::class => []],
            $this->resource->getRequestedIncludes()
        );
    }

    #[Test]
    public function getNestedEagerLoads(): void
    {
        $this->service->registerIncludesUsingQueryString('categories.posts,comments.post.comments');
        self::assertEquals(
            [
                'categories' => 'posts',
                'comments'   => 'post.comments',
            ],
            $this->resource->getNestedEagerLoads()
        );
    }

    #[Test]
    public function parseInclude(): void
    {
        $parse = $this->service->parseInclude('comments:limit(1).post:select(id).comments');
        self::assertEquals(
            [
                'relation' => 'comments',
                'actions'  => [
                    LimitAction::class => [1],
                ],
                'nested' => 'post:select(id).comments',
            ],
            $parse
        );
    }

    #[Test]
    public function applyRequestedIncludes(): void
    {
        $model = $this->posts->first();
        $data = $this->service->registerIncludesUsingQueryString('categories.posts,comments.post.comments')
            ->applyRequestedIncludes($model)
            ->getIncludedData();
        $output = null;
        foreach ($data as $item) {
            $output[] = $item->toResponse(request())->getData(true);
        }
        self::assertEquals(
            [
                [
                    'data' => $model->categories->map(
                        fn ($category) => [
                            'type'  => 'categories',
                            'id'    => $category->id,
                            'name'  => $category->name,
                            'posts' => $category->posts->map(
                                fn ($post) => [
                                    'type'    => 'posts',
                                    'id'      => $post->id,
                                    'title'   => $post->title,
                                    'content' => $post->content,
                                    'pivot'   => [
                                        'order' => $post->pivot->order,
                                    ],
                                ]
                            )->toArray(),
                            'pivot' => [
                                'order' => $category->pivot->order,
                            ],
                        ]
                    )->toArray(),
                    'type' => 'categories',
                ],
                [
                    'data' => $model->comments->map(
                        fn ($comment) => [
                            'type'    => 'comments',
                            'id'      => $comment->id,
                            'content' => $comment->content,
                            'post'    => [
                                'type'     => 'posts',
                                'id'       => ($post = $comment->post)->id,
                                'title'    => $post->title,
                                'content'  => $post->content,
                                'comments' => $post->comments->map(
                                    fn ($comment) => [
                                        'type'    => 'comments',
                                        'id'      => $comment->id,
                                        'content' => $comment->content,
                                    ]
                                )->toArray(),
                            ],
                        ]
                    )->toArray(),
                    'type' => 'comments',
                ],
            ],
            $output
        );
    }

    #[Test]
    public function getRegisteredActions(): void
    {
        $actions = $this->service->getRegisteredActions();
        self::assertEquals(
            [
                'select' => SelectAction::class,
                'order'  => OrderAction::class,
                'limit'  => LimitAction::class,
            ],
            $actions
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->posts = PostFactory::new()
            ->count(3)
            ->has(CommentFactory::new()->count(5))
            ->has(CategoryFactory::new()->count(5))
            ->create();
        $this->resource = PostResource::make($this->posts->first());
        $this->service = app(IncludingService::class, ['resource' => $this->resource]);
    }
}
