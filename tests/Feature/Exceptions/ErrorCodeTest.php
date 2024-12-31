<?php

namespace Hans\Valravn\Tests\Feature\Exceptions;

use Hans\Valravn\Exceptions\Package\PackageException;
use Hans\Valravn\Tests\Instances\Exceptions\SampleVErrorCode;
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
            SampleVErrorCode::firstOne()
        );
        self::assertEquals(
            'SamECx1',
            SampleVErrorCode::first_one()
        );

        self::assertEquals(
            'SamECx2',
            SampleVErrorCode::secondOne()
        );
        self::assertEquals(
            'SamECx2',
            SampleVErrorCode::second_one()
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

        SampleVErrorCode::$code();
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
            SampleVErrorCode::make()->first_one
        );
        self::assertEquals(
            'SamECx1',
            SampleVErrorCode::make()->firstOne
        );

        self::assertEquals(
            'SamECx2',
            SampleVErrorCode::make()->second_one
        );
        self::assertEquals(
            'SamECx2',
            SampleVErrorCode::make()->secondOne
        );
    }
}
