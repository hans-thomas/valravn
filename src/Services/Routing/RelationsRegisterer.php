<?php

namespace Hans\Valravn\Services\Routing;

use Hans\Valravn\Services\Contracts\Routeing\VRelations;
use Hans\Valravn\Services\Routing\Relations\BelongsTo;
use Hans\Valravn\Services\Routing\Relations\BelongsToMany;
use Hans\Valravn\Services\Routing\Relations\HasMany;
use Hans\Valravn\Services\Routing\Relations\HasOne;
use Hans\Valravn\Services\Routing\Relations\MorphedByMany;
use Hans\Valravn\Services\Routing\Relations\MorphTo;
use Hans\Valravn\Services\Routing\Relations\MorphToMany;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Arr;

class RelationsRegisterer
{
    private RouteRegistrar $registrar;

    public function __construct(private string $name, private string $controller, private array $options = [])
    {
        $this->registrar = app(RouteRegistrar::class)
            ->prefix($this->name)
            ->controller($this->controller);
    }

    public function hasOne(string $relation): HasOne
    {
        return tap(
            new HasOne($this->name, $relation, $this->registrar->name("$this->name.$relation."), $this->options),
            fn (VRelations $relation) => $this->flushOptions()
        );
    }

    public function hasMany(string $relation): HasMany
    {
        return tap(
            new HasMany($this->name, $relation, $this->registrar->name("$this->name.$relation."), $this->options),
            fn (VRelations $relation) => $this->flushOptions()
        );
    }

    public function belongsTo(string $relation): BelongsTo
    {
        return tap(
            new BelongsTo($this->name, $relation, $this->registrar->name("$this->name.$relation."), $this->options),
            fn (VRelations $relation) => $this->flushOptions()
        );
    }

    public function belongsToMany(string $relation): BelongsToMany
    {
        return tap(
            new BelongsToMany(
                $this->name,
                $relation,
                $this->registrar->name("$this->name.$relation."),
                $this->options
            ),
            fn (VRelations $relation) => $this->flushOptions()
        );
    }

    public function morphTo(string $relation): MorphTo
    {
        return tap(
            new MorphTo($this->name, $relation, $this->registrar->name("$this->name.$relation."), $this->options),
            fn (VRelations $relation) => $this->flushOptions()
        );
    }

    public function morphToMany(string $relation): MorphToMany
    {
        return tap(
            new MorphToMany(
                $this->name,
                $relation,
                $this->registrar->name("$this->name.$relation."),
                $this->options
            ),
            fn (VRelations $relation) => $this->flushOptions()
        );
    }

    public function morphedByMany(string $relation): MorphedByMany
    {
        return tap(
            new MorphedByMany(
                $this->name,
                $relation,
                $this->registrar->name("$this->name.$relation."),
                $this->options
            ),
            fn (VRelations $relation) => $this->flushOptions()
        );
    }

    private function flushOptions(): void
    {
        $this->options = [];
    }


    /**
     * @deprecated Use middleware() instead
     */
    public function withMiddleware(...$middleware): self
    {
        return $this->middleware(...$middleware);
    }

    public function middleware(...$middleware): self
    {
        $this->options['middleware'] = Arr::wrap($middleware);

        return $this;
    }

    public function withoutMiddleware(...$middleware): self
    {
        $this->options['excluded_middleware'] = Arr::wrap($middleware);

        return $this;
    }
}
