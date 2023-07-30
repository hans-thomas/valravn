<?php

namespace Hans\Valravn\Http\Resources\Contracts;

use Illuminate\Database\Eloquent\Model;

abstract class ResourceQuery
{
    /**
     * Store processed data.
     *
     * @var array
     */
    private array $data = [];

    /**
     * Implement a custom logic.
     *
     * @param Model $model
     *
     * @return array
     */
    abstract public function apply(Model $model): array;

    /**
     * Create an instance.
     *
     * @return static
     */
    public static function make(): static
    {
        return new static();
    }

    /**
     * Apply the custom logic and store the processed data.
     *
     * @param Model $model
     *
     * @return $this
     */
    public function run(Model $model): self
    {
        $this->data = $this->apply($model);

        return $this;
    }

    /**
     * Merge processed data to given array.
     *
     * @param ValravnJsonResource $resource
     * @param array               $data
     *
     * @return void
     */
    public function mergeDataInto(ValravnJsonResource $resource, array &$data): void
    {
        $data = array_merge($data, $this->getData());
    }

    /**
     * Return the processed data.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
