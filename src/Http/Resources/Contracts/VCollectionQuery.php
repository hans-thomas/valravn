<?php

namespace Hans\Valravn\Http\Resources\Contracts;

abstract class VCollectionQuery
{
    /**
     * Store processed data.
     *
     * @var array
     */
    private array $data = [];

    final public function __construct()
    {
    }

    /**
     * Implement a custom logic.
     *
     * @param VJsonResource $resource
     *
     * @return array
     */
    abstract public function apply(VJsonResource $resource): array;

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
     * @param VJsonResource $resource
     *
     * @return $this
     */
    public function run(VJsonResource $resource): self
    {
        $this->data = $this->apply($resource);

        return $this;
    }

    /**
     * Merge processed data to a resource class.
     *
     * @param VJsonResource $resource
     *
     * @return void
     */
    public function mergeDataInto(VJsonResource $resource): void
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
