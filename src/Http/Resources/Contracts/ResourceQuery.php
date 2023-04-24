<?php

    namespace Hans\Valravn\Http\Resources\Contracts;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Resources\Json\JsonResource;

    abstract class ResourceQuery {
        private array $data = [];

        abstract public function apply( Model $model ): array;

        public static function make(): static {
            return new static();
        }

        public function run( Model $model ): self {
            $this->data = $this->apply( $model );

            return $this;
        }

        public function mergeDataInto( JsonResource $json_resource, array &$data ): void {
            $data = array_merge( $data, $this->getData() );
        }

        /**
         * @return array
         */
        public function getData(): array {
            return $this->data;
        }

    }
