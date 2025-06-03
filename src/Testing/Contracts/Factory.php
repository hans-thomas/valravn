<?php

namespace Hans\Valravn\Testing\Contracts;

use Hans\Valravn\Repositories\Contracts\VRepository;
use Illuminate\Database\Eloquent\Factories\Factory as EloquentFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class Factory
{
    /**
     * Store the created model.
     *
     * @var Model
     */
    protected Model $model;

    final public function __construct(array $data = [])
    {
        $this->model = static::factory()->create($data);
    }

    /**
     * Create an instance.
     *
     * @param array $data
     *
     * @return static
     */
    public static function create(array $data = []): static
    {
        return new static($data);
    }

    /**
     * Execute hooks and then factory.
     *
     * @return EloquentFactory
     */
    protected static function factory(): EloquentFactory
    {
        static::preCreateHook();

        return static::getFactory();
    }

    /**
     * PreCreate hook executes before factory ran.
     *
     * @return void
     */
    protected static function preCreateHook(): void
    {
    }

    /**
     * Return related factory instance.
     *
     * @return EloquentFactory
     */
    abstract protected static function getFactory(): EloquentFactory;

    /**
     * Return related repository instance.
     *
     * @return VRepository
     */
    abstract public static function getRepository(): VRepository;

    /**
     * Create an instance but don't store.
     *
     * @param int|null $count
     * @param array $data
     *
     * @return Collection|Model
     */
    public static function make(?int $count = null, array $data = []): Collection|Model
    {
        return static::factory()->count($count)->make($data);
    }

    /**
     * Create many fake data at once.
     *
     * @param int $count
     * @param array $data
     *
     * @return Collection
     */
    public static function createMany(int $count = 10, array $data = []): Collection
    {
        return static::factory()
            ->count($count)
            ->create($data)
            ->map(static fn(Model $model) => $model->fresh());
    }

    /**
     * Return created model.
     *
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model->refresh();
    }
}
