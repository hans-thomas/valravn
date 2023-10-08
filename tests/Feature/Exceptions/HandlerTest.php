<?php

namespace Hans\Valravn\Tests\Feature\Exceptions;

use Hans\Valravn\Exceptions\Handler;
use Hans\Valravn\Exceptions\ValravnException;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Env;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class HandlerTest extends TestCase
{
    private Handler $handler;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->handler = $this->app->make(Handler::class);
        request()->headers->set('Accept', 'application/json');
        Env::getRepository()->set('RAW_ERROR', false);
    }

    /**
     * @test
     *
     * @return void
     * @throws Throwable
     */
    public function rawErrorEnv(): void
    {
        Env::getRepository()->set('RAW_ERROR', false);
        $e = new ModelNotFoundException('test exception.');

        self::assertJsonStringEqualsJsonString(
            '{"title":"Unexpected error!","detail":"test exception.","code":9995}',
            $this->handler->render(request(), $e)->content()
        );

        Env::getRepository()->set('RAW_ERROR', true);

        self::assertJsonStringEqualsJsonString(
            '{"message": "test exception."}',
            $this->handler->render(request(), $e)->content()
        );
    }

    /**
     * @test
     *
     * @return void
     * @throws Throwable
     */
    public function HttpExceptionMatchExpressionTest(): void
    {
        $e = new HttpException(500, 'test exception.');

        self::assertJsonStringEqualsJsonString(
            '{"title":"Unexpected error!","detail":"test exception.","code":9994}',
            $this->handler->render(request(), $e)->content()
        );

        request()->initialize();
        request()->headers->set('Accept', 'text/html');

        self::assertStringStartsWith(
            '<!DOCTYPE html>',
            $this->handler->render(request(), $e)->content()
        );
    }

    /**
     * @test
     *
     * @return void
     * @throws Throwable
     */
    public function getErrorCodeFromErrorInstance(): void {
        $e = ValravnException::make('test exception.', 27);

        self::assertJsonStringEqualsJsonString(
            '{"title":"Unexpected error!","detail":"test exception.","code":27}',
            $this->handler->render(request(), $e)->content()
        );
    }
    /**
     * @test
     *
     * @return void
     * @throws Throwable
     */
    public function getCodeFromErrorInstance(): void {
        $e = new NotFoundHttpException(code: 4040);

        self::assertJsonStringEqualsJsonString(
            '{"title":"Unexpected error!","detail":"Route not found!","code":4040}',
            $this->handler->render(request(), $e)->content()
        );
        self::assertEquals(
            4040,
            $this->handler->render(request(), $e)->getOriginalContent()['code']
        );
    }
}