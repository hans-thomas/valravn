<?php

namespace Hans\Valravn\Tests\Feature\Exceptions;

use Hans\Valravn\Exceptions\VHandler;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\Env;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class VHandlerTest extends TestCase
{
    private Handler $handler;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = $this->app->make(Handler::class);
        $this->handler->renderable(VHandler::convertUsing());

        request()->headers->set('Accept', 'application/json');
        Env::getRepository()->set('RAW_ERROR', false);
    }

    #[Test]
    public function rawErrorEnv(): void
    {
        Env::getRepository()->set('RAW_ERROR', false);
        $e = new ModelNotFoundException('test exception.');

        self::assertJsonStringEqualsJsonString(
            '{"title":"Unexpected error!","detail":"test exception.","code":"LEcx9997"}',
            $this->handler->render(request(), $e)->getContent()
        );

        Env::getRepository()->set('RAW_ERROR', true);

        self::assertJsonStringEqualsJsonString(
            '{"message": "test exception."}',
            $this->handler->render(request(), $e)->getContent()
        );
    }

    #[Test]
    public function HttpExceptionMatchExpressionTest(): void
    {
        $e = new HttpException(500, 'test exception.');

        self::assertJsonStringEqualsJsonString(
            '{"title":"Unexpected error!","detail":"test exception.","code":"LEcx9994"}',
            $this->handler->render(request(), $e)->getContent()
        );

        request()->initialize();
        request()->headers->set('Accept', 'text/html');

        self::assertStringStartsWith(
            '<!DOCTYPE html>',
            $this->handler->render(request(), $e)->getContent()
        );
    }

    #[Test]
    public function getErrorCodeFromErrorInstance(): void
    {
        $e = new class(85, 'test exception.') extends \Exception {
            private int $errorCode;

            public function __construct(int $errorCode, string $message = '', int $code = 0, ?Throwable $previous = null)
            {
                parent::__construct($message, $code, $previous);
                $this->errorCode = $errorCode;
            }

            public function getErrorCode(): int
            {
                return $this->errorCode;
            }
        };

        self::assertJsonStringEqualsJsonString(
            '{"title":"Unexpected error!","detail":"test exception.","code":"LEcx85"}',
            $this->handler->render(request(), $e)->getContent()
        );
    }

    #[Test]
    public function getCodeFromErrorInstance(): void
    {
        $e = new NotFoundHttpException('Route not found!', code: 4040);

        self::assertJsonStringEqualsJsonString(
            '{"title":"Unexpected error!","detail":"Route not found!","code":"LEcx4040"}',
            $this->handler->render(request(), $e)->getContent()
        );
        self::assertEquals(
            'LEcx4040',
            $this->handler->render(request(), $e)->getOriginalContent()['code']
        );
    }

    #[Test]
    public function getStatusCodeFromValidationErrorInstance(): void
    {
        $e = ValidationException::withMessages(
            [
                'name' => 'The name is required',
            ]
        );

        self::assertEquals(
            422,
            $this->handler->render(request(), $e)->getStatusCode()
        );
    }

    #[Test]
    public function handlingBindingResolutionException(): void
    {
        $e = new BindingResolutionException('Class IA in not instantiable.', code: 4050);

        self::assertJsonStringEqualsJsonString(
            '{"title":"Unexpected error!","detail":"Class IA in not instantiable.","code":"LEcx4050"}',
            $this->handler->render(request(), $e)->getContent()
        );
        self::assertEquals(
            'LEcx4050',
            $this->handler->render(request(), $e)->getOriginalContent()['code']
        );
    }
}
