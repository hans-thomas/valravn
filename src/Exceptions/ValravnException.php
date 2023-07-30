<?php

namespace Hans\Valravn\Exceptions;

    use Exception;
    use Illuminate\Http\JsonResponse;
    use Throwable;

    class ValravnException extends Exception
    {
        private int|string $errorCode;

        public function __construct(string $message = '', int|string $errorCode = 0, int $responseCode = 500, Throwable $previous = null)
        {
            parent::__construct($message, $responseCode < 100 ? 500 : $responseCode, $previous);
            $this->errorCode = $errorCode;
        }

        /**
         * Make an instance of the exception class.
         *
         * @param string         $message
         * @param int|string     $errorCode
         * @param int            $responseCode
         * @param Throwable|null $previous
         *
         * @return static
         */
        public static function make(string $message = '', int|string $errorCode = 0, int $responseCode = 500, Throwable $previous = null): self
        {
            return new self($message, $errorCode, $responseCode, $previous);
        }

        /**
         * Render the exception as an HTTP response.
         *
         * @return JsonResponse
         */
        public function render(): JsonResponse
        {
            logg(self::class, $this, ['errorCode' => $this->getErrorCode(), 'responseCode' => $this->getCode()]);

            return new JsonResponse([
                'title'  => 'Unexpected error!',
                'detail' => $this->getMessage(),
                'code'   => $this->getErrorCode(),
            ], $this->getCode());
        }

        /**
         * Return the error code of the exception.
         *
         * @return int|string
         */
        public function getErrorCode(): int|string
        {
            return $this->errorCode;
        }
    }
