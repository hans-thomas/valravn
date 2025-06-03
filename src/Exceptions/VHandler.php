<?php

namespace Hans\Valravn\Exceptions;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class VHandler
{
    /**
     * Converts exceptions to Valravn style.
     *
     * @return callable
     */
    public static function convertUsing(): callable
    {
        return static fn (Throwable $e) => env('RAW_ERROR', false) ?
            null :
            match (true) {
                $e instanceof QueryException            => self::throw($e, 9998, $e->getPrevious()->getMessage(), 500),
                $e instanceof NotFoundHttpException     => self::throw($e, 9997),
                $e instanceof AccessDeniedHttpException => self::throw($e, 9996),
                $e instanceof BadRequestHttpException   => self::throw($e, 9995),
                $e instanceof HttpException             => request()->wantsJson() ?
                    self::throw($e, defaultErrorCode: 9994) :
                    null,
                default => self::throw($e)
            };
    }

    /**
     * Convert the given exception to the ValravnException class.
     *
     * @param Throwable   $e
     * @param int         $defaultErrorCode
     * @param string|null $message
     * @param int|null    $responseCode
     *
     * @throws Exception
     *
     * @return JsonResponse
     */
    private static function throw(
        Throwable $e,
        int $defaultErrorCode = 9999,
        ?string $message = null,
        ?int $responseCode = null
    ): JsonResponse {
        if (method_exists($e, $method = 'getErrorCode')) {
            $errorCode = $e->{$method}();
        } elseif ($e->getCode() > 0) {
            $errorCode = $e->getCode();
        } else {
            $errorCode = $defaultErrorCode;
        }

        if ($responseCode == null && method_exists($e, 'getStatusCode')) {
            $responseCode = $e->getStatusCode();
        } else {
            $responseCode = 500;
        }

        $e = new VException(
            $message ?: $e->getMessage(),
            $errorCode,
            $responseCode,
            'LEcx',
        );

        return $e->render();
    }
}
