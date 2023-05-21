<?php

	namespace Hans\Valravn\Tests\Contracts;

	use Hans\Valravn\Repositories\Contracts\Repository;
	use Illuminate\Database\Eloquent\Factories\Factory as EloquentFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Collection;

	abstract class Factory {

		/**
		 * Store the created model
		 *
		 * @var Model
		 */
		protected Model $model;

		public function __construct( array $data = [] ) {
			$this->model = static::factory()->create( $data );
		}

		/**
		 * Return related factory instance
		 *
		 * @return EloquentFactory
		 */
		abstract protected static function getFactory(): EloquentFactory;

		/**
		 * Return related repository instance
		 *
		 * @return Repository
		 */
		abstract public static function getRepository(): Repository;

		/**
		 * PreCreate hook executes before factory ran
		 *
		 * @return void
		 */
		protected static function preCreateHook(): void { }

		/**
		 * Execute hooks and then factory
		 *
		 * @return EloquentFactory
		 */
		protected static function factory(): EloquentFactory {
			static::preCreateHook();

			return static::getFactory();
		}

		/**
		 * Create an instance
		 *
		 * @param array $data
		 *
		 * @return static
		 */
		public static function create( array $data = [] ): static {
			return new static( $data );
		}

		/**
		 * Create an instance but don't store
		 *
		 * @param int|null $count
		 * @param array    $data
		 *
		 * @return Collection|Model
		 */
		public static function make( ?int $count = null, array $data = [] ): Collection|Model {
			return static::factory()->count( $count )->make( $data );
		}

		/**
		 * Create many fake data at once
		 *
		 * @param int   $count
		 * @param array $data
		 *
		 * @return Collection
		 */
		public static function createMany( int $count = 10, array $data = [] ): Collection {
			return static::factory()
			             ->count( $count )
			             ->create( $data )
			             ->map( fn( Model $model ) => $model->fresh() );
		}

		/**
		 * Return created model
		 *
		 * @return Model
		 */
		public function getModel(): Model {
			return $this->model->refresh();
		}
	}
