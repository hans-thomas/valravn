<?php

namespace Hans\Valravn\Services\Contracts\Notification;

use Hans\Valravn\Http\Resources\Contracts\VJsonResource;
use Illuminate\Database\Eloquent\Model;

abstract class Notifiable
{
    final public function __construct(protected Model $model)
    {
    }

    /**
     * Create an instance statically.
     *
     * @param Model $model
     *
     * @return static
     */
    public static function make(Model $model): static
    {
        return new static($model);
    }

    /**
     * Return notification message as array.
     *
     * @return array
     */
    final public function getMessage(): array
    {
        return [
            'title'   => $this->title(),
            'body'    => $this->body(),
            'related' => $this->getRelatedEntity(),
        ];
    }

    /**
     * Title of the notification.
     *
     * @return string
     */
    abstract protected function title(): string;

    /**
     * Body of the notification.
     *
     * @return string
     */
    abstract protected function body(): string;

    /**
     * Return an entity that relates to the model.
     *
     * @return VJsonResource|null
     */
    abstract protected function getRelatedEntity(): ?VJsonResource;
}
