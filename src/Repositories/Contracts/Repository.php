<?php

	namespace Hans\Valravn\Repositories\Contracts;

	use Hans\Valravn\DTOs\BatchUpdateDto;
	use Hans\Valravn\Exceptions\BaseException;
	use Batch;
	use Illuminate\Auth\Access\AuthorizationException;
	use Illuminate\Contracts\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Gate;
	use Throwable;

	abstract class Repository {
		private bool $authorization = true;
		private Builder $builder;

		public function __construct() {
			$this->builder = $this->getQueryBuilder();
		}

		abstract protected function getQueryBuilder(): Builder;

		protected function getModelClassName(): string {
			return get_class( $this->getQueryBuilder()->getModel() );
		}

		protected function guessAbility(): string {
			return debug_backtrace()[ 2 ][ 'function' ];
		}

		protected function resolveModel( Model|int $model ): Model {
			return $model instanceof Model ? $model : $this->query()->findOrFail( $model );
		}

		public function shouldAuthorize(): bool {
			return $this->authorization;
		}

		public function disableAuthorization(): static {
			$this->authorization = false;

			return $this;
		}

		public function enableAuthorization(): static {
			$this->authorization = true;

			return $this;
		}

		public function ifShouldAuthorize( callable $callable ): void {
			if ( $this->shouldAuthorize() ) {
				$callable();
			}
		}

		protected function query(): Builder {
			$query         = $this->builder;
			$this->builder = $this->getQueryBuilder();

			return $query;
		}

		public function select(): self {
			$this->builder = $this->query()->select( ...func_get_args() );

			return $this;
		}

		protected function authorize( $ability = null, ...$params ): void {
			if ( $ability instanceof Model ) {
				$params[] = $ability;
				$ability  = $this->guessAbility();
			}
			if ( count( $params ) == 0 ) {
				$params = [ $this->getModelClassName() ];
			}
			if ( is_null( $ability ) ) {
				$ability = $this->guessAbility();
			}

			$this->ifShouldAuthorize( fn() => Gate::authorize( $ability, $params ) );
		}

		/**
		 * @throws AuthorizationException
		 */
		protected function authorizeThisAction( ...$params ): void {
			$this->authorize( $this->guessAbility(), ...$params );
		}

		/**
		 * @throws AuthorizationException
		 */
		public function all(): Builder {
			$this->authorize( 'viewAny' );

			return $this->query();
		}

		/**
		 * @throws AuthorizationException
		 */
		public function find( int|string $id, string $column = 'id' ): Model {
			$model = $this->query()->applyFilters()->where( $column, $id )->limit( 1 )->firstOrFail();
			$this->authorize( 'view', $model );

			return $model;
		}

		/**
		 * @throws AuthorizationException
		 * @throws BaseException
		 */
		public function delete( Model|int $model ): bool {
			$model = $this->resolveModel( $model );
			$this->authorize( $model );
			// execute deleting hook
			DB::beginTransaction();
			try {
				$this->deleting( $model );
				$model->delete();
			} catch ( Throwable $e ) {
				// TODO: replace AppException with package exception
				throw AppException::failedToExecuteDeletingHook( $model );
			}
			DB::commit();

			return true;
		}

		/**
		 * Deleting Hook
		 *
		 * @param Model $model
		 */
		protected function deleting( Model $model ) {
			//
		}

		public function create( array $data ): Model {
			$this->authorize();

			return $this->query()->create( $data );
		}

		public function update( Model|int $model, array $data ): bool {
			$model = $this->resolveModel( $model );
			$this->authorize( $model );

			return $model->update( $data );
		}

		public function batchUpdate( BatchUpdateDto $dto ): bool {
			$this->authorize( 'batchUpdate', $this->getModelClassName(), $dto->getData() );

			return Batch::update(
				$this->query()->getModel(),
				$dto->getData()->toArray(),
				$this->query()->getModel()->getKeyName()
			);
		}

	}
