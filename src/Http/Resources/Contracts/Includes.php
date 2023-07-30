<?php

namespace Hans\Valravn\Http\Resources\Contracts;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Includes
{
    /**
     * Eloquent builder instance.
     *
     * @var Builder
     */
    private Builder $builder;

    /**
     * Registered actions list.
     *
     * @var array
     */
    private array $actions = [];

    /**
     * Create an instance statically.
     *
     * @return static
     */
    public static function make(): static
    {
        return new static();
    }

    /**
     * Implement a custom logic.
     *
     * @param Model $model
     *
     * @return Builder
     */
    abstract public function apply(Model $model): Builder;

    /**
     * Convert processed builder instance to a resource class.
     *
     * @return ValravnJsonResource
     */
    abstract public function toResource(): ValravnJsonResource;

    /**
     * Apply the custom logic and store in builder.
     *
     * @param Model $model
     *
     * @return $this
     */
    public function run(Model $model): self
    {
        $this->builder = $this->apply($model);

        return $this;
    }

    /**
     * Return builder instance.
     *
     * @return Builder
     */
    public function getBuilder(): Builder
    {
        return $this->builder;
    }

    /**
     * Register an action with its parameters.
     *
     * @param string $action
     * @param array  $params
     *
     * @return $this
     */
    public function registerAction(string $action, array $params = []): self
    {
        $this->actions[$action] = $params;

        return $this;
    }

    /**
     * Register actions with their parameters.
     *
     * @param array $actions
     *
     * @return $this
     */
    public function registerActions(array $actions): self
    {
        foreach ($actions as $action => $params) {
            $this->registerAction($action, $params);
        }

        return $this;
    }

    /**
     * Apply registered actions on the builder instance.
     *
     * @return $this
     */
    public function applyActions(): self
    {
        foreach ($this->actions as $action => $params) {
            app($action, ['builder' => $this->getBuilder()])->apply($params);
        }

        return $this;
    }
}
