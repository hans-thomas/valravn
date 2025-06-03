<?php

namespace Hans\Valravn\Tests\Feature\Exceptions;

use Exception;
use Hans\Valravn\Exceptions\VException;
use Hans\Valravn\Tests\Instances\Exceptions\CompactFormException;
use Hans\Valravn\Tests\Instances\Exceptions\FullFormException;
use Hans\Valravn\Tests\Instances\Exceptions\FullFormWithEmptyPrefixException;
use Hans\Valravn\Tests\Instances\Exceptions\FullFormWithoutPrefixException;
use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

class VExceptionTest extends TestCase
{
    #[Test]
    public function runtimeException(): void
    {
        $exception = new VException('Runtime exception', 1, errorCodePrefix: 'RTEcx');

        self::assertEquals(
            [
                'title' => 'Unexpected error!',
                'detail' => 'Runtime exception',
                'code' => 'RTEcx1',
            ],
            $exception->render()->getData(true)
        );

        $this->expectExceptionMessage('Runtime exception');
        $this->expectExceptionCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        throw $exception;
    }

    #[Test]
    public function exceptionWithManipulatingErrorCodePrefix(): void
    {
        $exception = FullFormException::failedWithDifferentPrefix('TEcx3');

        self::assertEquals(
            [
                'title' => 'Unexpected error!',
                'detail' => 'Failed with new prefix',
                'code' => 'FFEcx3',
            ],
            $exception->render()->getData(true)
        );

        $this->expectExceptionMessage('Failed with new prefix');
        $this->expectExceptionCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        self::assertNotEquals('TEcx3', $exception->render()->getData(true)['code']);

        throw $exception;
    }

    #[Test]
    public function exception(): void
    {
        $exception = FullFormException::failedRemove('name');

        self::assertEquals(
            [
                'title' => 'Unexpected error!',
                'detail' => 'Failed to remove name',
                'code' => 'FFEcx1',
            ],
            $exception->render()->getData(true)
        );

        $this->expectExceptionMessage('Failed to remove name');
        $this->expectExceptionCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        throw $exception;
    }

    #[Test]
    public function exceptionWithCustomHttpResponseCode(): void
    {
        $exception = FullFormException::notFount();

        self::assertEquals(
            [
                'title' => 'Unexpected error!',
                'detail' => 'Failed to find your data',
                'code' => 'FFEcx2',
            ],
            $exception->render()->getData(true)
        );

        $this->expectExceptionMessage('Failed to find your data');
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        throw $exception;
    }

    #[Test]
    public function exceptionWithNoPrefix(): void
    {
        $this->expectExceptionMessage('The prefix for error codes is not defined.');
        $this->expectExceptionCode(Response::HTTP_EXPECTATION_FAILED);
        $this->expectException(Exception::class);

        throw FullFormWithoutPrefixException::failed();
    }

    #[Test]
    public function exceptionWithEmptyPrefix(): void
    {
        $this->expectExceptionMessage('The prefix for error codes is not defined.');
        $this->expectExceptionCode(Response::HTTP_EXPECTATION_FAILED);
        $this->expectException(Exception::class);

        throw FullFormWithEmptyPrefixException::failedAgain();
    }

    #[Test]
    public function compactExceptionWithCustomHttpResponseCode(): void
    {
        $exception = new CompactFormException(self::class);

        self::assertEquals(
            [
                'title' => 'Unexpected error!',
                'detail' => "Class 'Hans\Valravn\Tests\Feature\Exceptions\VExceptionTest' does not exist",
                'code' => 'CFEcx1',
            ],
            $exception->render()->getData(true)
        );

        $this->expectExceptionMessage("Class 'Hans\Valravn\Tests\Feature\Exceptions\VExceptionTest' does not exist");
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        throw $exception;
    }
}
