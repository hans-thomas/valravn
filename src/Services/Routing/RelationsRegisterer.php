<?php

    namespace Hans\Valravn\Services\Routing;

    use Hans\Valravn\Services\Routing\Relations\BelongsTo;
    use Hans\Valravn\Services\Routing\Relations\BelongsToMany;
    use Hans\Valravn\Services\Routing\Relations\HasMany;
    use Hans\Valravn\Services\Routing\Relations\MorphToMany;
    use Illuminate\Routing\RouteRegistrar;

    class RelationsRegisterer {
        private RouteRegistrar $registrar;

        public function __construct( private string $name, private string $controller ) {
            $this->registrar = app( RouteRegistrar::class )
                ->prefix( $this->name )
                ->controller( $this->controller );
        }

        public function belongsTo( string $relation ): BelongsTo {
            return new BelongsTo( $relation, $this->registrar->name( "$this->name.$relation." ) );
        }

        public function hasMany( string $relation ): HasMany {
            return new HasMany( $relation, $this->registrar->name( "$this->name.$relation." ) );
        }

        public function belongsToMany( string $relation ): BelongsToMany {
            return new BelongsToMany( $relation, $this->registrar->name( "$this->name.$relation." ) );
        }

        public function morphToMany( string $relation ): MorphToMany {
            return new MorphToMany( $relation, $this->registrar->name( "$this->name.$relation." ) );
        }

    }
