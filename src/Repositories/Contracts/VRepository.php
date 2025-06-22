<?php

namespace Hans\Valravn\Repositories\Contracts;

use Hans\Valravn\DTOs\BatchUpdateDto;
use Hans\Valravn\Exceptions\Package\FailedToDeleteException;
use Hans\Valravn\Exceptions\VException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

abstract class VRepository
{
    /**
     * Authorization flag.
     *
     * @var bool
     */
    private bool $authorization = true;

    /**
     * Eloquent builder instance.
     *
     * @var Builder
     */
    private Builder $builder;

    public function __construct()
    {
        $this->builder = $this->getQueryBuilder();
    }

    /**
     * Return the related builder instance.
     *
     * @return Builder
     */
    abstract protected function getQueryBuilder(): Builder;

    /**
     * Return model name using related builder instance.
     *
     * @return string
     */
    protected function getModelClassName(): string
    {
        return get_class($this->getQueryBuilder()->getModel());
    }

    /**
     * Guess the ability to authorize.
     *
     * @return string
     */
    protected function guessAbility(): string
    {
        return debug_backtrace()[2]['function'];
    }

    /**
     * Resolve model.
     *
     * @param Model|int $model
     *
     * @return Model
     */
    protected function resolveModel(Model|int $model): Model
    {
        return $model instanceof Model ? $model : $this->query()->findOrFail($model);
    }

    /**
     * Determine should authorize or not.
     *
     * @return bool
     */
    protected function shouldAuthorize(): bool
    {
        return $this->authorization;
    }

    /**
     * Disable the authorization.
     *
     * @return $this
     */
    public function disableAuthorization(): static
    {
        $this->authorization = false;

        return $this;
    }

    /**
     * Enable the authorization.
     *
     * @return $this
     */
    public function enableAuthorization(): static
    {
        $this->authorization = true;

        return $this;
    }

    /**
     * Call the closure if it should authorize.
     *
     * @throws AuthorizationException
     */
    protected function ifShouldAuthorize(callable $callable): void
    {
        if ($this->shouldAuthorize()) {
            $callable();
        }
    }

    /**
     * Rerun the builder instance and reset it for next usage.
     *
     * @return Builder
     */
    protected function query(): Builder
    {
        $query = $this->builder;
        $this->builder = $this->getQueryBuilder();

        return $query;
    }

    /**
     * Apply a select statement to the current builder instance.
     *
     * @return $this
     */
    public function select(): self
    {
        $this->builder = $this->query()->select(...func_get_args());

        return $this;
    }

    /**
     * Apply an eager load statement to the current builder instance.
     *
     * @return $this
     */
    public function with(): self
    {
        $this->builder = $this->query()->with(...func_get_args());

        return $this;
    }

    /**
     * Authorize an action.
     *
     * @param null  $ability
     * @param mixed ...$params
     *
     * @throws AuthorizationException
     */
    protected function authorize($ability = null, ...$params): void
    {
        if ($ability instanceof Model) {
            $params[] = $ability;
            $ability = $this->guessAbility();
        }
        if (count($params) == 0) {
            $params = [$this->getModelClassName()];
        }
        if (is_null($ability)) {
            $ability = $this->guessAbility();
        }

        $this->ifShouldAuthorize(static fn () => Gate::authorize($ability, $params));
    }

    /**
     * Guess the action and authorize it.
     *
     * @param mixed ...$params
     *
     * @throws AuthorizationException
     */
    protected function authorizeThisAction(...$params): void
    {
        $this->authorize($this->guessAbility(), ...$params);
    }

    /**
     * Return all resource.
     *
     * @throws AuthorizationException
     *
     * @return Builder
     */
    public function all(): Builder
    {
        $this->authorize('viewAny');

        return $this->query();
    }

    /**
     * Find a specific resource.
     *
     * @param int|string $id
     * @param string     $column
     *
     * @throws AuthorizationException
     *
     * @return Model
     */
    public function find(int|string $id, string $column = 'id'): Model
    {
        $query = $this->query()->applyFilters();

        $this->finding($query, $id, $column);
        $model = $query->where($column, $id)->limit(1)->firstOrFail();
        $this->found($model);

        $this->authorize('view', $model);

        return $model;
    }

    /**
     * Create a resource using given data.
     *
     * @param array $data
     *
     * @throws AuthorizationException|Throwable
     *
     * @return Model
     */
    public function create(array $data): Model
    {
        $this->authorize();

        DB::beginTransaction();

        try {
            $this->creating($data);
            $model = $this->query()->create($data);
            $this->created($model);
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
        DB::commit();

        return $model;
    }

    /**
     * Update Model using given data.
     *
     * @param Model|int $model
     * @param array     $data
     *
     * @throws AuthorizationException|Throwable
     *
     * @return bool
     */
    public function update(Model|int $model, array $data): bool
    {
        $model = $this->resolveModel($model);
        $this->authorize($model);

        DB::beginTransaction();

        try {
            $this->updating($model, $data);
            $result = $model->update($data);
            $this->updated($model);
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
        DB::commit();

        return $result;
    }

    /**
     * Update many resources in one query.
     *
     * @param BatchUpdateDto $dto
     *
     * @throws AuthorizationException|Throwable
     *
     * @return bool
     */
    public function batchUpdate(BatchUpdateDto $dto): bool
    {
        $this->authorize('batchUpdate', $this->getModelClassName(), $dto->getData());

        DB::beginTransaction();

        try {
            $this->batchUpdating($dto->getData());
            $result = batch()->update(
                $this->query()->getModel(),
                $dto->getData()->toArray(),
                $this->query()->getModel()->getKeyName()
            );
            $this->batchUpdated($dto->getData());
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
        DB::commit();

        return $result;
    }

    /**
     * Delete a specific resource.
     *
     * @param Model|int $model
     *
     * @throws AuthorizationException
     * @throws VException
     *
     * @return bool
     */
    public function delete(Model|int $model): bool
    {
        $model = $this->resolveModel($model);
        $this->authorize($model);

        DB::beginTransaction();

        try {
            $this->deleting($model);
            $model->delete();
            $this->deleted($model);
        } catch (Throwable $e) {
            throw new FailedToDeleteException($model);
        }
        DB::commit();

        return true;
    }

    /**
     * Finding Hook executes before querying the resource.
     *
     * @param Builder    $query
     * @param int|string $id
     * @param string     $column
     *
     * @return void
     */
    protected function finding(Builder $query, int|string $id, string $column): void
    {
    }

    /**
     * Found Hook executes after querying the resource.
     *
     * @param Model $model
     *
     * @return void
     */
    protected function found(Model $model): void
    {
    }

    /**
     * Creating Hook executes before creating the resource.
     *
     * @param array $data
     *
     * @return void
     */
    protected function creating(array &$data): void
    {
    }

    /**
     * Created Hook executes after the resource created.
     *
     * @param Model $model
     *
     * @return void
     */
    protected function created(Model $model): void
    {
    }

    /**
     * Updating Hook executes before updating the resource using given data.
     *
     * @param Model $model
     * @param array $data
     *
     * @return void
     */
    protected function updating(Model $model, array &$data): void
    {
    }

    /**
     * Updated Hook executes after the resource updated.
     *
     * @param Model $model
     *
     * @return void
     */
    protected function updated(Model $model): void
    {
    }

    /**
     * BatchUpdating Hook executes before the resource updates in batch mode.
     *
     * @param Collection $data
     *
     * @return void
     */
    protected function batchUpdating(Collection $data): void
    {
    }

    /**
     * BatchUpdated Hook executes after the resource updates in batch mode.
     *
     * @param Collection $data
     *
     * @return void
     */
    protected function batchUpdated(Collection $data): void
    {
    }

    /**
     * Deleting Hook executes before deleting the resource.
     *
     * @param Model $model
     */
    protected function deleting(Model $model): void
    {
    }

    /**
     * Deleted Hook executes after the resource deleted.
     *
     * @param Model $model
     */
    protected function deleted(Model $model): void
    {
    }
}
