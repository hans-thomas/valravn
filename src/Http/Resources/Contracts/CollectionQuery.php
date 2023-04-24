<?php

    namespace Hans\Valravn\Http\Resources\Contracts;

    use Illuminate\Http\Resources\Json\JsonResource;

    abstract class CollectionQuery {
        private array $data = [];

        abstract public function apply( JsonResource $json_resource ): array;

        public static function make(): static {
            return new static();
        }

        public function run( JsonResource $json_resource ): self {
            $this->data = $this->apply( $json_resource );

            return $this;
        }

        public function mergeDataInto( JsonResource $json_resource ): void {
            $json_resource->addAdditional( $this->getData() );
        }

        /**
         * @return array
         */
        public function getData(): array {
            return $this->data;
        }
    }
