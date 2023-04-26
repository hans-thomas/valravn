<?php

	namespace Hans\Valravn\Exceptions;

	use Illuminate\Auth\Access\AuthorizationException;
	use Illuminate\Database\Eloquent\ModelNotFoundException;
	use Illuminate\Database\QueryException;
	use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
	use Illuminate\Http\JsonResponse;
	use Illuminate\Validation\ValidationException;
	use Symfony\Component\HttpKernel\Exception\HttpException;
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
	use Throwable;

	class Handler extends ExceptionHandler {
		/**
		 * A list of the exception types that are not reported.
		 *
		 * @var array<int, class-string<Throwable>>
		 */
		protected $dontReport = [
			//
		];

		/**
		 * A list of the inputs that are never flashed for validation exceptions.
		 *
		 * @var array<int, string>
		 */
		protected $dontFlash = [
			'current_password',
			'password',
			'password_confirmation',
		];

		/**
		 * Register the exception handling callbacks for the application.
		 *
		 * @return void
		 */
		public function register() {
			$this->reportable( function( Throwable $e ) {
				//
			} );
		}

		public function render( $request, Throwable $e ) {
			if ( env( 'RAW_ERROR', false ) ) {
				return parent::render( $request, $e );
			}

			return match ( true ) {
				$e instanceof ValidationException => parent::render( $request, $e ),
				$e instanceof AuthorizationException => self::throw( $e, defaultErrorCode: 9998, responseCode: 403 ),
				$e instanceof NotFoundHttpException => self::throw( $e, 9997, 'Route not found!', 404 ),
				$e instanceof QueryException => self::throw( $e, 9996, $e->getPrevious()->getMessage(), 500 ),
				$e instanceof ModelNotFoundException => self::throw( $e, 9995, $e->getMessage(), 404 ),
				$e instanceof HttpException => $request->wantsJson() ?
					self::throw( $e, defaultErrorCode: 9994 ) :
					parent::render( $request, $e ),
				default => self::throw( $e )
			};
		}

		private static function throw( Throwable $e, int $defaultErrorCode = 9999, string $message = null, int $responseCode = null ): JsonResponse {
			if ( method_exists( $e, $method = 'getErrorCode' ) ) {
				$errorCode = $e->{$method}();
			} elseif ( $e->getCode() > 0 ) {
				$errorCode = $e->getCode();
			} else {
				$errorCode = $defaultErrorCode;
			}

			return BaseException::make(
				$message ? : $e->getMessage(),
				$errorCode,
				$responseCode ? : $e->getCode()
			)
			                    ->render();
		}
	}
