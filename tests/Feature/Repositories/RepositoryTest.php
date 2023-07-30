<?php

namespace Hans\Valravn\Tests\Feature\Repositories;

    use Hans\Valravn\DTOs\BatchUpdateDto;
    use Hans\Valravn\Repositories\Contracts\Repository;
    use Hans\Valravn\Tests\Core\Factories\PostFactory;
    use Hans\Valravn\Tests\Core\Models\Post;
    use Hans\Valravn\Tests\Instances\Repositories\SampleRepository;
    use Hans\Valravn\Tests\TestCase;
    use Illuminate\Auth\Access\AuthorizationException;
    use Illuminate\Contracts\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Gate;

    class RepositoryTest extends TestCase
    {
        private Repository $repository;

        /**
         * @return void
         */
        protected function setUp(): void
        {
            parent::setUp();
            PostFactory::new()->count(5)->create();
            $this->repository = app(SampleRepository::class)->disableAuthorization();
        }

        /**
         * @test
         *
         * @return void
         */
        public function shouldAuthorizeAsDefault(): void
        {
            Gate::shouldReceive('authorize')
                ->once();
            app(SampleRepository::class)->all();
        }

        /**
         * @test
         *
         * @throws AuthorizationException
         *
         * @return void
         */
        public function shouldAuthorizeAsDisabled(): void
        {
            $this->repository->disableAuthorization();
            self::assertInstanceOf(Builder::class, $this->repository->all());
        }

        /**
         * @test
         *
         * @throws AuthorizationException
         *
         * @return void
         */
        public function shouldAuthorizeAsEnabled(): void
        {
            $this->repository->disableAuthorization()->enableAuthorization();
            Gate::shouldReceive('authorize')
                ->once();
            $this->repository->all();
        }

        /**
         * @test
         *
         * @throws AuthorizationException
         *
         * @return void
         */
        public function all(): void
        {
            $models = $this->repository->all()->get();
            self::assertEquals(
                Post::all()->toArray(),
                $models->toArray()
            );
        }

        /**
         * @test
         *
         * @throws AuthorizationException
         *
         * @return void
         */
        public function allUsingSelect(): void
        {
            $models = $this->repository->select('id')->all()->get();
            self::assertEquals(
                Post::all()->map(
                    fn ($value) => ['id' => $value->id]
                )
                    ->toArray(),
                $models->toArray()
            );
        }

        /**
         * @test
         *
         * @throws AuthorizationException
         *
         * @return void
         */
        public function find(): void
        {
            $model = $this->repository->find(1);
            self::assertEquals(
                Post::query()->first()->toArray(),
                $model->toArray()
            );
        }

        /**
         * @test
         *
         * @throws AuthorizationException
         *
         * @return void
         */
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

        /**
         * @test
         *
         * @return void
         */
        public function deleteAction(): void
        {
            self::assertTrue($this->repository->delete(1));
            $this->assertDatabaseMissing(Post::table(), ['id' => 1]);
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
            self::assertInstanceOf(
                Model::class,
                $this->repository->create($data)
            );
            $this->assertDatabaseHas(Post::table(), $data);
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
            self::assertTrue($this->repository->update(1, $data));
            $this->assertDatabaseHas(Post::table(), $data + ['id' => 1]);
        }

        /**
         * @test
         *
         * @return void
         */
        public function batchUpdateAction(): void
        {
            $data = [
                [
                    'id'    => 1,
                    'title' => fake()->sentence(),
                ],
                [
                    'id'      => 2,
                    'content' => fake()->sentence(),
                ],
                [
                    'id'      => 3,
                    'title'   => fake()->sentence(),
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
    }
