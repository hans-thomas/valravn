<?php

    namespace Hans\Valravn\Exceptions;

    use Exception;
    use Illuminate\Http\JsonResponse;
    use Throwable;

    class BaseException extends Exception {
        private int|string $errorCode;

        public function __construct( string $message = "", int|string $errorCode = 0, int $responseCode = 500, Throwable $previous = null ) {
            parent::__construct( $message, $responseCode < 100 ? 500 : $responseCode, $previous );
            $this->errorCode = $errorCode;
        }

        public static function make( string $message = "", int|string $ErrorCode = 0, int $responseCode = 500, Throwable $previous = null ): self {
            return new self( $message, $ErrorCode, $responseCode, $previous );
        }

        /**
         * Render the exception as an HTTP response.
         *
         * @return JsonResponse
         */
        public function render(): JsonResponse {
            logg( self::class, $this, [ 'errorCode' => $this->getErrorCode(), 'responseCode' => $this->getCode() ] );

            return new JsonResponse( [
                'title'  => 'Unexpected error!',
                'detail' => $this->getMessage(),
                'code'   => $this->getErrorCode(),
            ], $this->getCode() );
        }

        public function getErrorCode(): int|string {
            return $this->errorCode;
        }
    }
