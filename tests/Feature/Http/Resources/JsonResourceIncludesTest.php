<?php

namespace Hans\Valravn\Tests\Feature\Http\Resources;

    use Hans\Valravn\Tests\Core\Factories\CommentFactory;
    use Hans\Valravn\Tests\Core\Factories\PostFactory;
    use Hans\Valravn\Tests\Core\Models\Comment;
    use Hans\Valravn\Tests\Core\Models\Post;
    use Hans\Valravn\Tests\Core\Resources\Post\PostResource;
    use Hans\Valravn\Tests\TestCase;

    class JsonResourceIncludesTest extends TestCase
    {
        private Post $post;

        /**
         * @return void
         */
        protected function setUp(): void
        {
            parent::setUp();
            $this->post = PostFactory::new()->has(CommentFactory::new()->count(5))->create();
        }

        /**
         * @test
         *
         * @return void
         */
        public function includes(): void
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

        /**
         * @test
         *
         * @return void
         */
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
