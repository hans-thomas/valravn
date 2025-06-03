<?php

namespace Hans\Valravn\Tests\Feature\Repositories;

use Hans\Valravn\DTOs\BatchUpdateDto;
use Hans\Valravn\Repositories\Contracts\VRepository;
use Hans\Valravn\Tests\Core\Factories\CategoryFactory;
use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\Instances\Repositories\SampleVRepository;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use PHPUnit\Framework\Attributes\Test;

class RepositoryTest extends TestCase
{
    private VRepository $repository;

    #[Test]
    public function shouldAuthorizeAsDefault(): void
    {
        Gate::shouldReceive('authorize')
            ->once();
        app(SampleVRepository::class)->all();
    }

    #[Test]
    public function all(): void
    {
        $models = $this->repository->all()->get();
        self::assertEquals(
            Post::all()->toArray(),
            $models->toArray()
        );
    }

    #[Test]
    public function shouldAuthorizeAsDisabled(): void
    {
        $this->repository->disableAuthorization();
        self::assertInstanceOf(Builder::class, $this->repository->all());
    }

    #[Test]
    public function shouldAuthorizeAsEnabled(): void
    {
        $this->repository->disableAuthorization()->enableAuthorization();
        Gate::shouldReceive('authorize')
            ->once();
        $this->repository->all();
    }

    #[Test]
    public function allUsingSelect(): void
    {
        $models = $this->repository->select('id')->all()->get();
        self::assertEquals(
            Post::all()->map(
                fn($value) => ['id' => $value->id]
            )
                ->toArray(),
            $models->toArray()
        );
    }

    #[Test]
    public function allUsingWith(): void
    {
        $models = $this->repository->with('categories')->all()->get();
        self::assertEquals(
            Post::all()->map(
                fn($value) => array_merge($value->toArray(), ['categories' => $value->categories->toArray()])
            )
                ->toArray(),
            $models->toArray()
        );
    }

    #[Test]
    public function findUsingSelect(): void
    {
        $model = $this->repository->select('id')->find(1);
        self::assertEquals(
            [
                'id' => 1,
            ],
            $model->toArray()
        );
    }

    #[Test]
    public function find(): void
    {
        $model = $this->repository->find(1);
        self::assertEquals(
            Post::query()->first()->toArray(),
            $model->toArray()
        );
    }

    #[Test]
    public function findUsingWith(): void
    {
        $model = $this->repository->with('categories')->find(1);
        self::assertEquals(
            array_merge($model->withoutRelations()->toArray(), ['categories' => $model->categories->toArray()]),
            $model->toArray()
        );
    }

    #[Test]
    public function deleteAction(): void
    {
        self::assertTrue($this->repository->delete(1));
        $this->assertDatabaseMissing(Post::table(), ['id' => 1]);
    }

    #[Test]
    public function createAction(): void
    {
        $data = PostFactory::new()->make()->toArray();
        self::assertInstanceOf(
            Model::class,
            $this->repository->create($data)
        );
        $this->assertDatabaseHas(Post::table(), $data);
    }

    #[Test]
    public function updateAction(): void
    {
        $data = PostFactory::new()->make()->toArray();
        self::assertTrue($this->repository->update(1, $data));
        $this->assertDatabaseHas(Post::table(), $data + ['id' => 1]);
    }

    #[Test]
    public function batchUpdateAction(): void
    {
        $data = [
            [
                'id' => 1,
                'title' => fake()->sentence(),
            ],
            [
                'id' => 2,
                'content' => fake()->sentence(),
            ],
            [
                'id' => 3,
                'title' => fake()->sentence(),
                'content' => fake()->sentence(),
            ],
        ];

        self::assertTrue(
            $this->repository->batchUpdate(BatchUpdateDto::make(['batch' => $data]))
        );
        $this->assertDatabaseHas(Post::table(), $data[0]);
        $this->assertDatabaseHas(Post::table(), $data[1]);
        $this->assertDatabaseHas(Post::table(), $data[2]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        PostFactory::new()->count(5)->has(CategoryFactory::new())->create();
        $this->repository = app(SampleVRepository::class)->disableAuthorization();
    }
}
