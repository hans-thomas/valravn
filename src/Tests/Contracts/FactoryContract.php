<?php

	namespace Hans\Valravn\Tests\Contracts;

	use App\Repositories\Contracts\Repository;
	use Illuminate\Database\Eloquent\Factories\Factory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Collection;

	abstract class FactoryContract {
		protected Model $model;

		public function __construct( array $data = [] ) {
			$this->model = static::factory()->create( $data );
		}

		abstract protected static function getFactory(): Factory;

		abstract public static function getRepository(): Repository;

		protected static function preCreateHook(): void { }

		protected static function factory(): Factory {
			static::preCreateHook();

			return static::getFactory();
		}

		public static function create( array $data = [] ): static {
			return new static( $data );
		}

		public static function make( ?int $count = null, array $data = [] ): Collection|Model {
			return static::factory()->count( $count )->make( $data );
		}

		public static function createMany( int $count = 10, array $data = [] ): Collection {
			return static::factory()
			             ->count( $count )
			             ->create( $data )
			             ->map( fn( Model $model ) => $model->fresh() );
		}

		public function getModel(): Model {
			return $this->model->refresh();
		}
	}
