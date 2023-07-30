<?php

namespace Hans\Valravn\Services\Routing;

    use Hans\Valravn\Services\Routing\Relations\BelongsTo;
    use Hans\Valravn\Services\Routing\Relations\BelongsToMany;
    use Hans\Valravn\Services\Routing\Relations\HasMany;
    use Hans\Valravn\Services\Routing\Relations\HasOne;
    use Hans\Valravn\Services\Routing\Relations\MorphedByMany;
    use Hans\Valravn\Services\Routing\Relations\MorphTo;
    use Hans\Valravn\Services\Routing\Relations\MorphToMany;
    use Illuminate\Routing\RouteRegistrar;

    class RelationsRegisterer
    {
        private RouteRegistrar $registrar;

        public function __construct(private string $name, private string $controller)
        {
            $this->registrar = app(RouteRegistrar::class)
                ->prefix($this->name)
                ->controller($this->controller);
        }

        public function hasOne(string $relation): HasOne
        {
            return new HasOne($this->name, $relation, $this->registrar->name("$this->name.$relation."));
        }

        public function hasMany(string $relation): HasMany
        {
            return new HasMany($this->name, $relation, $this->registrar->name("$this->name.$relation."));
        }

        public function belongsTo(string $relation): BelongsTo
        {
            return new BelongsTo($this->name, $relation, $this->registrar->name("$this->name.$relation."));
        }

        public function belongsToMany(string $relation): BelongsToMany
        {
            return new BelongsToMany($this->name, $relation, $this->registrar->name("$this->name.$relation."));
        }

        public function morphTo(string $relation): MorphTo
        {
            return new MorphTo($this->name, $relation, $this->registrar->name("$this->name.$relation."));
        }

        public function morphToMany(string $relation): MorphToMany
        {
            return new MorphToMany($this->name, $relation, $this->registrar->name("$this->name.$relation."));
        }

        public function morphedByMany(string $relation): MorphedByMany
        {
            return new MorphedByMany($this->name, $relation, $this->registrar->name("$this->name.$relation."));
        }
    }
