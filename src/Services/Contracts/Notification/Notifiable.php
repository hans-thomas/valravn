<?php

	namespace Hans\Valravn\Services\Contracts\Notification;

	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\Resources\Json\JsonResource;

	abstract class Notifiable {

		/**
		 * Model instance
		 *
		 * @var Model
		 */
		protected Model $model;

		public function __construct( Model $model ) {
			$this->model = $model;
		}

		/**
		 * Create an instance statically
		 *
		 * @param Model $model
		 *
		 * @return static
		 */
		public static function make( Model $model ): self {
			return new static( $model );
		}

		/**
		 * Return notification message as array
		 *
		 * @return array
		 */
		final public function getMessage(): array {
			return [
				'title'   => $this->title(),
				'body'    => $this->body(),
				'related' => $this->getRelatedEntity(),
			];
		}

		/**
		 * Title of the notification
		 *
		 * @return string
		 */
		abstract protected function title(): string;

		/**
		 * Body of the notification
		 *
		 * @return string
		 */
		abstract protected function body(): string;

		/**
		 * Return an entity that relates to the model
		 *
		 * @return BaseJsonResource|null
		 */
		abstract protected function getRelatedEntity(): ?BaseJsonResource;
	}
