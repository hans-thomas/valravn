<?php

namespace Hans\Valravn\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class VException extends Exception
{
    /**
     * A unique code for each error.
     *
     * @var int
     */
    private int $errorCode;

    /**
     * A unique string acts as a namespace.
     *
     * @var string
     */
    protected string $errorCodePrefix;

    /**
     * @param  string          $message
     * @param  int             $errorCode
     * @param  int             $responseCode
     * @param  string          $errorCodePrefix
     * @param  Throwable|null  $previous
     *
     * @throws Exception
     */
    public function __construct(
        string $message,
        int $errorCode,
        int $responseCode = 500,
        string $errorCodePrefix = '',
        Throwable $previous = null,
    ) {
        parent::__construct($message, $responseCode, $previous);
        $this->errorCode = $errorCode;

        if (empty($this->errorCodePrefix) && empty($errorCodePrefix)) {
            throw new Exception('The prefix for error codes is not defined.', Response::HTTP_EXPECTATION_FAILED);
        } elseif (empty($this->errorCodePrefix) && !empty($errorCodePrefix)) {
            $this->errorCodePrefix = $errorCodePrefix;
        }
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        vlog($this);

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
     */
    public function getErrorCode(): string
    {
        return $this->errorCodePrefix.$this->errorCode;
    }
}
