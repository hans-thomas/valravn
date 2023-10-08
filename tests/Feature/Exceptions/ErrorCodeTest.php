<?php

namespace Hans\Valravn\Tests\Feature\Exceptions;

use Hans\Valravn\Exceptions\Package\PackageException;
use Hans\Valravn\Tests\Instances\Exceptions\SampleErrorCode;
use Hans\Valravn\Tests\TestCase;

class ErrorCodeTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function hasPrefix(): void
    {
        self::assertEquals(
            'SamECx1',
            SampleErrorCode::firstOne()
        );
        self::assertEquals(
            'SamECx1',
            SampleErrorCode::first_one()
        );

        self::assertEquals(
            'SamECx2',
            SampleErrorCode::secondOne()
        );
        self::assertEquals(
            'SamECx2',
            SampleErrorCode::second_one()
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function callNotExistedErrorCode(): void
    {
        $code = 'third_one';

        $this->expectExceptionObject(PackageException::errorCodeNotFound($code));

        SampleErrorCode::$code();
    }

    /**
     * @test
     *
     * @return void
     */
    public function getMagicMethod(): void
    {
        self::assertEquals(
            'SamECx1',
            SampleErrorCode::make()->first_one
        );
        self::assertEquals(
            'SamECx1',
            SampleErrorCode::make()->firstOne
        );

        self::assertEquals(
            'SamECx2',
            SampleErrorCode::make()->second_one
        );
        self::assertEquals(
            'SamECx2',
            SampleErrorCode::make()->secondOne
        );
    }
}