<?php

	namespace Hans\Valravn\Exceptions\Valravn;

	use Hans\Valravn\Exceptions\BaseException;
	use Illuminate\Database\Eloquent\Model;
	use Symfony\Component\HttpFoundation\Response;

	class ValravnException extends BaseException {
		public static function failedToDelete( Model $model ): BaseException {
			return self::make(
				"Failed to delete [" . get_class( $model ) . "] $model->id",
				ValravnErrorCode::failedToDelete(),
				Response::HTTP_INTERNAL_SERVER_ERROR
			);
		}
	}