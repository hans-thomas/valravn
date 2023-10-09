<?php

namespace Hans\Valravn\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param Request   $request
     * @param Throwable $e
     *
     * @throws Throwable
     *
     * @return Response
     */
    public function render($request, Throwable $e)
    {
        if (env('RAW_ERROR', false)) {
            return parent::render($request, $e);
        }

        return match (true) {
            $e instanceof ValidationException    => parent::render($request, $e),
            $e instanceof AuthorizationException => self::throw($e, defaultErrorCode: 9998, responseCode: 403),
            $e instanceof NotFoundHttpException  => self::throw($e, 9997, 'Route not found!', 404),
            $e instanceof QueryException         => self::throw($e, 9996, $e->getPrevious()->getMessage(), 500),
            $e instanceof ModelNotFoundException => self::throw($e, 9995, $e->getMessage(), 404),
            $e instanceof HttpException          => $request->wantsJson() ?
                self::throw($e, defaultErrorCode: 9994) :
                parent::render($request, $e),
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
     * @return JsonResponse
     */
    private static function throw(Throwable $e, int $defaultErrorCode = 9999, string $message = null, int $responseCode = null): JsonResponse
    {
        if (method_exists($e, $method = 'getErrorCode')) {
            $errorCode = $e->{$method}();
        } elseif ($e->getCode() > 0) {
            $errorCode = $e->getCode();
        } else {
            $errorCode = $defaultErrorCode;
        }

        return ValravnException::make(
            $message ?: $e->getMessage(),
            $errorCode,
            $responseCode ?: $e->getCode()
        )
                               ->render();
    }
}
