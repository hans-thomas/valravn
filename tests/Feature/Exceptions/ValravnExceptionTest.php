<?php

namespace Hans\Valravn\Tests\Feature\Exceptions;

use Hans\Valravn\Exceptions\VException;
use Hans\Valravn\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ValravnExceptionTest extends TestCase
{
    /**
     * @test
     *
     * @throws VException
     *
     * @return void
     */
    public function make(): void
    {
        $exception = VException::make(
            'she was classy, now she is nasty.',
            1009,
            Response::HTTP_FORBIDDEN
        );

        self::assertEquals(
            [
                'title'  => 'Unexpected error!',
                'detail' => 'she was classy, now she is nasty.',
                'code'   => 1009,
            ],
            $exception->render()->getData(true)
        );

        $this->expectExceptionMessage('she was classy, now she is nasty.');
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        throw $exception;
    }
}
