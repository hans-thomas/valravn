<?php

namespace Hans\Valravn\Tests\Feature\Http\Resources;

use Hans\Valravn\Tests\Core\Factories\CategoryFactory;
use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class InteractsWithRelationsTest extends TestCase
{
    private Post $post;

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
    
    #[Test]
    public function loadRelation(): void
    {
        $this->post->loadMissing('categories');

        self::assertArrayHasKey(
            'categories',
            $this->post->toResource()->toResponse(request())->getData(true)['data']
        );
    }

    #[Test]
    public function ignoreRelationWhenEmpty(): void
    {
        $this->post->categories()->detach($this->post->categories()->pluck('id'));
        $this->post->load('categories');

        self::assertArrayNotHasKey(
            'categories',
            $this->post->toResource()->toResponse(request())->getData(true)['data']
        );
    }
}