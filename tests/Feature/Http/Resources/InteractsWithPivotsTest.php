<?php

namespace Hans\Valravn\Tests\Feature\Http\Resources;

use Hans\Valravn\Tests\Core\Factories\CategoryFactory;
use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\Instances\Http\Resources\SampleWithAliasResource;
use Hans\Valravn\Tests\Instances\Http\Resources\SampleWithExcludeResource;
use Hans\Valravn\Tests\Instances\Http\Resources\SampleWithIncludeResource;
use Hans\Valravn\Tests\TestCase;

class InteractsWithPivotsTest extends TestCase
{
    private Post $post;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->post = PostFactory::new()
                                 ->hasAttached(
                                     CategoryFactory::new()->count(5),
                                     ['order' => rand(1, 100)]
                                 )
                                 ->create();
    }

    /**
     * @test
     *
     * @return void
     */
    public function alias(): void
    {
        $resource = SampleWithAliasResource::make($category = $this->post->categories()->first());
        self::assertEquals(
            [
                'data' => [
                    'type'  => 'samples',
                    'id'    => $category->id,
                    'name'  => $category->name,
                    'pivot' => [
                        'lineup' => $category->pivot->order,
                    ],
                ],
                'type' => 'samples',
            ],
            $this->resourceToJson($resource)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function includes(): void
    {
        $resource = SampleWithIncludeResource::make($category = $this->post->categories()->first());

        self::assertEquals(
            [
                'data' => [
                    'type'  => 'samples',
                    'id'    => $category->id,
                    'name'  => $category->name,
                    'pivot' => [
                        'order'               => $category->pivot->order,
                        'post_id'             => $category->pivot->post_id,
                        'category_identifier' => $category->pivot->category_id,
                    ],
                ],
                'type' => 'samples',
            ],
            $this->resourceToJson($resource)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function excludes(): void
    {
        $resource = SampleWithExcludeResource::make($category = $this->post->categories()->first());

        self::assertEquals(
            [
                'data' => [
                    'type'  => 'samples',
                    'id'    => $category->id,
                    'name'  => $category->name,
                    'pivot' => [
                        'post_id'             => $category->pivot->post_id,
                        'category_identifier' => $category->pivot->category_id,
                    ],
                ],
                'type' => 'samples',
            ],
            $this->resourceToJson($resource)
        );
    }
}
