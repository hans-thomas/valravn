<?php

namespace Hans\Valravn\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class VException extends Exception
{
    /**
     * A unique code for each error
     *
     * @var int
     */
    private int $errorCode;

    /**
     * A unique string acts as a namespace
     *
     * @var string
     */
    protected string $errorCodePrefix;

    /**
     * @param  string          $message
     * @param  int             $errorCode
     * @param  int             $responseCode
     * @param  Throwable|null  $previous
     */
    public function __construct(string $message, int $errorCode, int $responseCode = 500, Throwable $previous = null)
    {
        parent::__construct($message, $responseCode, $previous);
        $this->errorCode = $errorCode;
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @return JsonResponse
     * @throws Exception
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
     * @return string
     * @throws Exception
     */
    public function getErrorCode(): string
    {
        if (!isset($this->errorCodePrefix)) {
            throw new Exception('The prefix for error codes is not defined.');
        }

        return $this->errorCodePrefix.$this->errorCode;
    }
}
