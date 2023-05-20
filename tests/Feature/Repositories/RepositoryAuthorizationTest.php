<?php

	namespace Hans\Tests\Valravn\Feature\Repositories;

	use Hans\Tests\Valravn\Core\Factories\PostFactory;
	use Hans\Tests\Valravn\Core\Models\Post;
	use Hans\Tests\Valravn\Instances\Repositories\SampleRepository;
	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\DTOs\BatchUpdateDto;
	use Hans\Valravn\Exceptions\ValravnException;
	use Hans\Valravn\Repositories\Contracts\Repository;
	use Illuminate\Auth\Access\AuthorizationException;
	use Illuminate\Support\Facades\Gate;

	class RepositoryAuthorizationTest extends TestCase {

		private Repository $repository;

		/**
		 * @return void
		 */
		protected function setUp(): void {
			parent::setUp();
			PostFactory::new()->count( 2 )->create();
			$this->repository = app( SampleRepository::class );
		}

		/**
		 * @test
		 *
		 * @return void
		 * @throws AuthorizationException
		 */
		public function allAction(): void {
			Gate::shouldReceive( 'authorize' )
			    ->once()
			    ->with( 'viewAny', [ Post::class ] );
			$this->repository->all()->get();
		}

		/**
		 * @test
		 *
		 * @return void
		 * @throws AuthorizationException
		 */
		public function findAction(): void {
			$model = Post::query()->first();
			Gate::shouldReceive( 'authorize' )
			    ->once()
			    ->with( 'view', [ $model ] );
			$this->repository->find( $model->id );
		}

		/**
		 * @test
		 *
		 * @return void
		 * @throws AuthorizationException
		 * @throws ValravnException
		 */
		public function deleteAction(): void {
			$model = Post::query()->first();
			Gate::shouldReceive( 'authorize' )
			    ->once()
			    ->with( 'delete', [ $model ] );
			$this->repository->delete( $model->id );
		}

		/**
		 * @test
		 *
		 * @return void
		 * @throws AuthorizationException
		 */
		public function createAction(): void {
			$data = PostFactory::new()->make()->toArray();
			Gate::shouldReceive( 'authorize' )
			    ->once()
			    ->with( 'create', [ Post::class ] );
			$this->repository->create( $data );
		}

		/**
		 * @test
		 *
		 * @return void
		 * @throws AuthorizationException
		 */
		public function updateAction(): void {
			$data  = PostFactory::new()->make()->toArray();
			$model = Post::query()->first();
			Gate::shouldReceive( 'authorize' )
			    ->once()
			    ->with( 'update', [ $model ] );
			$this->repository->update( $model, $data );
		}

		/**
		 * @test
		 *
		 * @return void
		 * @throws AuthorizationException
		 */
		public function batchUpdateAction(): void {
			$data = [
				[
					'id'      => 1,
					'content' => fake()->sentence()
				],
				[
					'id'      => 2,
					'title'   => fake()->sentence(),
					'content' => fake()->sentence()
				]
			];
			$dto  = BatchUpdateDto::make( [ 'batch' => $data ] );

			Gate::shouldReceive( 'authorize' )
			    ->once()
			    ->with( 'batchUpdate', [ Post::class, $dto->getData() ] );

			$this->repository->batchUpdate( $dto );
		}


	}