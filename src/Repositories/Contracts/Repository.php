<?php

	namespace Hans\Valravn\Repositories\Contracts;

	use Hans\Valravn\DTOs\BatchUpdateDto;
	use Hans\Valravn\Exceptions\BaseException;
	use Hans\Valravn\Exceptions\Valravn\ValravnException;
	use Illuminate\Auth\Access\AuthorizationException;
	use Illuminate\Contracts\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Gate;
	use Throwable;

	abstract class Repository {

		/**
		 * Authorization flag
		 *
		 * @var bool
		 */
		private bool $authorization = true;

		/**
		 * Eloquent builder instance
		 *
		 * @var Builder
		 */
		private Builder $builder;

		public function __construct() {
			$this->builder = $this->getQueryBuilder();
		}

		/**
		 * Return the related builder instance
		 *
		 * @return Builder
		 */
		abstract protected function getQueryBuilder(): Builder;

		/**
		 * Return model name using related builder instance
		 *
		 * @return string
		 */
		protected function getModelClassName(): string {
			return get_class( $this->getQueryBuilder()->getModel() );
		}

		/**
		 * Guess the ability to authorize
		 *
		 * @return string
		 */
		protected function guessAbility(): string {
			return debug_backtrace()[ 2 ][ 'function' ];
		}

		/**
		 * Resolve model
		 *
		 * @param Model|int $model
		 *
		 * @return Model
		 */
		protected function resolveModel( Model|int $model ): Model {
			return $model instanceof Model ? $model : $this->query()->findOrFail( $model );
		}

		/**
		 * Determine should authorize or not
		 *
		 * @return bool
		 */
		protected function shouldAuthorize(): bool {
			return $this->authorization;
		}

		/**
		 * Disable the authorization
		 *
		 * @return $this
		 */
		public function disableAuthorization(): static {
			$this->authorization = false;

			return $this;
		}

		/**
		 * Enable the authorization
		 *
		 * @return $this
		 */
		public function enableAuthorization(): static {
			$this->authorization = true;

			return $this;
		}

		/**
		 * Call the closure if it should authorize
		 *
		 * @throws AuthorizationException
		 */
		protected function ifShouldAuthorize( callable $callable ): void {
			if ( $this->shouldAuthorize() ) {
				$callable();
			}
		}

		/**
		 * Rerun the builder instance and reset it for next usage
		 *
		 * @return Builder
		 */
		protected function query(): Builder {
			$query         = $this->builder;
			$this->builder = $this->getQueryBuilder();

			return $query;
		}

		/**
		 * Set a select statement to the current builder instance
		 *
		 * @return $this
		 */
		public function select(): self {
			$this->builder = $this->query()->select( ...func_get_args() );

			return $this;
		}

		/**
		 * Authorize an action
		 *
		 * @param null  $ability
		 * @param mixed ...$params
		 *
		 * @throws AuthorizationException
		 */
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
		 * Guess the action and authorize it
		 *
		 * @param mixed ...$params
		 *
		 * @throws AuthorizationException
		 */
		protected function authorizeThisAction( ...$params ): void {
			$this->authorize( $this->guessAbility(), ...$params );
		}

		/**
		 * Return all resource
		 *
		 * @return Builder
		 * @throws AuthorizationException
		 */
		public function all(): Builder {
			$this->authorize( 'viewAny' );

			return $this->query();
		}

		/**
		 * Find a specific resource
		 *
		 * @param int|string $id
		 * @param string     $column
		 *
		 * @return Model
		 * @throws AuthorizationException
		 */
		public function find( int|string $id, string $column = 'id' ): Model {
			$model = $this->query()->applyFilters()->where( $column, $id )->limit( 1 )->firstOrFail();
			$this->authorize( 'view', $model );

			return $model;
		}

		/**
		 * Create a resource using given data
		 *
		 * @param array $data
		 *
		 * @return Model
		 * @throws AuthorizationException
		 */
		public function create( array $data ): Model {
			$this->authorize();

			return $this->query()->create( $data );
		}

		/**
		 * Update Model using given data
		 *
		 * @param Model|int $model
		 * @param array     $data
		 *
		 * @return bool
		 * @throws AuthorizationException
		 */
		public function update( Model|int $model, array $data ): bool {
			$model = $this->resolveModel( $model );
			$this->authorize( $model );

			return $model->update( $data );
		}

		/**
		 * Update many resources in one query
		 *
		 * @param BatchUpdateDto $dto
		 *
		 * @return bool
		 * @throws AuthorizationException
		 */
		public function batchUpdate( BatchUpdateDto $dto ): bool {
			$this->authorize( 'batchUpdate', $this->getModelClassName(), $dto->getData() );

			return batch()->update(
				$this->query()->getModel(),
				$dto->getData()->toArray(),
				$this->query()->getModel()->getKeyName()
			);
		}

		/**
		 * Delete a specific resource
		 *
		 * @param Model|int $model
		 *
		 * @return bool
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
				$this->deleted( $model );
			} catch ( Throwable $e ) {
				throw ValravnException::failedToDelete( $model );
			}
			DB::commit();

			return true;
		}

		/**
		 * Deleting Hook executes before the resource deleted
		 *
		 * @param Model $model
		 */
		protected function deleting( Model $model ) {
			//
		}

		/**
		 * Deleted Hook executes after the resource deleted
		 *
		 * @param Model $model
		 */
		protected function deleted( Model $model ) {
			//
		}

	}
