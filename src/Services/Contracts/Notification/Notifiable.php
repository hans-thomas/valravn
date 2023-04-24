<?php

    namespace App\Services\Contracts\Notification;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Resources\Json\JsonResource;

    abstract class Notifiable {
        protected Model $model;

        public function __construct( Model $model ) {
            $this->model = $model;
        }

        public static function make( Model $model ): self {
            return new static( $model );
        }

        final public function getMessage(): array {
            return [
                'title'   => $this->title(),
                'body'    => $this->body(),
                'related' => $this->getRelatedEntity(),
            ];
        }

        abstract protected function title(): string;

        abstract protected function body(): string;

        abstract protected function getRelatedEntity(): JsonResource;
    }
