<?php

namespace Hans\Valravn\Tests\Feature\Models;

use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\Instances\Models\AliasForModelAttributesModel;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;

class BaseModelTest extends TestCase
{
    private Model $model;

    protected function setUp(): void
    {
        parent::setUp();
        $post = PostFactory::new()->create();
        $this->model = AliasForModelAttributesModel::query()->make([
            'content' => fake()->paragraph(),
        ]);
        $this->model->post()->associate($post);
        $this->model->save();
    }

    /**
     * @test
     *
     * @return void
     */
    public function aliasForModelAttributesOnGettingAlias(): void
    {
        self::assertEquals(
            $this->model->post_id,
            $this->model->its_post_id
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function aliasForModelAttributesOnUpdatingAlias(): void
    {
        $post = PostFactory::new()->create();
        $this->model->update([
            'its_post_id' => $post->id,
        ]);

        self::assertEquals(
            $this->model->post_id,
            $post->id
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function table(): void
    {
        self::assertEquals(
            (new Post())->getTable(),
            Post::table()
        );
    }
    /**
     * @test
     *
     * @return void
     */
    public function foreignKey(): void
    {
        self::assertEquals(
            (new Post())->getForeignKey(),
            Post::foreignKey()
        );
    }
}
