<?php

namespace Hans\Valravn\Http\Resources\Contracts;

abstract class CollectionQuery
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
     * @param ValravnJsonResource $resource
     *
     * @return array
     */
    abstract public function apply(ValravnJsonResource $resource): array;

    /**
     * Create an instance in static way.
     *
     * @return static
     */
    public static function make(): static
    {
        return new static();
    }

    /**
     * Apply the custom logic and store processed data.
     *
     * @param ValravnJsonResource $resource
     *
     * @return $this
     */
    public function run(ValravnJsonResource $resource): self
    {
        $this->data = $this->apply($resource);

        return $this;
    }

    /**
     * Merge processed data to a resource class.
     *
     * @param ValravnJsonResource $resource
     *
     * @return void
     */
    public function mergeDataInto(ValravnJsonResource $resource): void
    {
        $resource->addAdditional($this->getData());
    }

    /**
     * Return processed data.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
