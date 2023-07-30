<?php

namespace Hans\Valravn\Tests\Feature\Repositories;

use Hans\Valravn\DTOs\BatchUpdateDto;
use Hans\Valravn\Exceptions\ValravnException;
use Hans\Valravn\Repositories\Contracts\Repository;
use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\Instances\Repositories\SampleRepository;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class RepositoryAuthorizationTest extends TestCase
{
    private Repository $repository;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        PostFactory::new()->count(2)->create();
        $this->repository = app(SampleRepository::class);
    }

    /**
     * @test
     *
     * @throws AuthorizationException
     *
     * @return void
     */
    public function allAction(): void
    {
        Gate::shouldReceive('authorize')
            ->once()
            ->with('viewAny', [Post::class]);
        $this->repository->all()->get();
    }

    /**
     * @test
     *
     * @throws AuthorizationException
     *
     * @return void
     */
    public function findAction(): void
    {
        $model = Post::query()->first();
        Gate::shouldReceive('authorize')
            ->once()
            ->with('view', [$model]);
        $this->repository->find($model->id);
    }

    /**
     * @test
     *
     * @throws AuthorizationException
     * @throws ValravnException
     *
     * @return void
     */
    public function deleteAction(): void
    {
        $model = Post::query()->first();
        Gate::shouldReceive('authorize')
            ->once()
            ->with('delete', [$model]);
        $this->repository->delete($model->id);
    }

    /**
     * @test
     *
     * @throws AuthorizationException
     *
     * @return void
     */
    public function createAction(): void
    {
        $data = PostFactory::new()->make()->toArray();
        Gate::shouldReceive('authorize')
            ->once()
            ->with('create', [Post::class]);
        $this->repository->create($data);
    }

    /**
     * @test
     *
     * @throws AuthorizationException
     *
     * @return void
     */
    public function updateAction(): void
    {
        $data = PostFactory::new()->make()->toArray();
        $model = Post::query()->first();
        Gate::shouldReceive('authorize')
            ->once()
            ->with('update', [$model]);
        $this->repository->update($model, $data);
    }

    /**
     * @test
     *
     * @throws AuthorizationException
     *
     * @return void
     */
    public function batchUpdateAction(): void
    {
        $data = [
            [
                'id'      => 1,
                'content' => fake()->sentence(),
            ],
            [
                'id'      => 2,
                'title'   => fake()->sentence(),
                'content' => fake()->sentence(),
            ],
        ];
        $dto = BatchUpdateDto::make(['batch' => $data]);

        Gate::shouldReceive('authorize')
            ->once()
            ->with('batchUpdate', [Post::class, $dto->getData()]);

        $this->repository->batchUpdate($dto);
    }
}
