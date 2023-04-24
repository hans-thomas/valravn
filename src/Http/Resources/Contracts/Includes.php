<?php

    namespace Hans\Valravn\Http\Resources\Contracts;

    use Illuminate\Contracts\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Resources\Json\JsonResource;

    abstract class Includes {
        private Builder $builder;
        private array $actions = [];

        public static function make(): static {
            return new static();
        }

        abstract public function apply( Model $model ): Builder;

        abstract public function toResource(): JsonResource;

        public function run( Model $model ): self {
            $this->builder = $this->apply( $model );

            return $this;
        }

        public function getBuilder(): Builder {
            return $this->builder;
        }

        public function registerAction( string $action, array $params = [] ): self {
            $this->actions[ $action ] = $params;

            return $this;
        }

        public function registerActions( array $actions ): self {
            foreach ( $actions as $action => $params ) {
                $this->registerAction( $action, $params );
            }

            return $this;
        }

        public function applyActions(): self {
            foreach ( $this->actions as $action => $params ) {
                app( $action, [ 'builder' => $this->getBuilder() ] )->apply( $params );
            }

            return $this;
        }
    }
