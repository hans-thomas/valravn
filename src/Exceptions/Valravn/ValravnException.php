<?php

	namespace Hans\Valravn\Exceptions\Valravn;

	use App\Exceptions\App\AppErrorCode;
	use Hans\Valravn\Exceptions\BaseException;
	use Illuminate\Database\Eloquent\Model;
	use Symfony\Component\HttpFoundation\Response;

	class ValravnException extends BaseException {
		public static function failedToExecuteDeletingHook( Model $model ): BaseException {
			return self::make(
				"Failed to execute deleting hook on [" . get_class( $model ) . "] $model->id",
				ValravnErrorCode::failedToExecuteDeletingHook(),
				Response::HTTP_INTERNAL_SERVER_ERROR
			);
		}
	}