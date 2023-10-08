<?php

namespace Hans\Valravn\Tests\Instances\Exceptions;

use Hans\Valravn\Exceptions\ErrorCode;

class SampleErrorCode extends ErrorCode
{
    protected static string $prefix = 'SamECx';

    protected int $firstOne = 1;
    protected int $SECOND_ONE = 2;
}