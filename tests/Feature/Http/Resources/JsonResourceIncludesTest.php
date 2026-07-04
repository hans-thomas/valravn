<?php

namespace Hans\Valravn\Tests\Feature\Http\Resources;

use Hans\Valravn\Tests\Core\Factories\CommentFactory;
use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Models\Comment;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\Core\Resources\Post\PostCollection;
use Hans\Valravn\Tests\Core\Resources\Post\PostResource;
use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class JsonResourceIncludesTest extends TestCase
{
    private Post $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->post = PostFactory::new()->has(CommentFactory::new()->count(5))->create();
    }

    #[Test]
    public function includesOnResource(): void
    {
        $resource = PostResource::make($this->post)->withCommentsIncludes();
        self::assertEquals(
            [
                'data' => [
                    'type'     => 'posts',
                    'id'       => $this->post->id,
                    'title'    => $this->post->title,
                    'content'  => $this->post->content,
                    'comments' => $this->post
                        ->comments
                        ->map(
                            fn (Comment $value) => [
                                'type'    => 'comments',
                                'id'      => $value->id,
                                'content' => $value->content,
                            ]
                        )
                        ->toArray(),
                ],
                'type' => 'posts',
            ],
            $this->resourceToJson($resource)
        );
    }

    #[Test]
    public function includesOnCollection(): void
    {
        PostFactory::new()->has(CommentFactory::new()->count(5))->createMany();

        $collection = PostCollection::make(Post::query()->paginate())->withFirstCommentQuery();
        self::assertEquals(
            [
                'data' => Post::query()
                    ->get()
                    ->map(
                        fn (Post $post) => [
                            'type'          => 'posts',
                            'id'            => $post->id,
                            'title'         => $post->title,
                            'content'       => $post->content,
                            'first_comment' => $post
                                ->comments
                                ->map(
                                    fn (Comment $value) => [
                                        'type'    => 'comments',
                                        'id'      => $value->id,
                                        'content' => $value->content,
                                    ]
                                )
                                ->toArray()[0] ?? null,
                        ]
                    )
                ->toArray(),
            ],
            [
                'data' => $this->resourceToJson($collection)['data'],
            ]
        );
    }

    #[Test]
    public function includesThroughApi(): void
    {
        $content = $this->get("/includes/posts/{$this->post->id}?includes=comments")
            ->json();
        self::assertEquals(
            [
                'data' => [
                    'type'     => 'posts',
                    'id'       => $this->post->id,
                    'title'    => $this->post->title,
                    'content'  => $this->post->content,
                    'comments' => $this->post
                        ->comments
                        ->map(
                            fn (Comment $value) => [
                                'type'    => 'comments',
                                'id'      => $value->id,
                                'content' => $value->content,
                            ]
                        )
                        ->toArray(),
                ],
                'type' => 'posts',
            ],
            $content
        );
    }
}
