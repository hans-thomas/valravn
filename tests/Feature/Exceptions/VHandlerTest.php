<?php

namespace Hans\Valravn\Tests\Feature\Exceptions;

use Hans\Valravn\Exceptions\VException;
use Hans\Valravn\Exceptions\VHandler;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\Env;
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
        $e = new VException('test exception.', 27, errorCodePrefix: 'TLEcx');

        self::assertJsonStringEqualsJsonString(
            '{"title":"Unexpected error!","detail":"test exception.","code":"TLEcx27"}',
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
}
