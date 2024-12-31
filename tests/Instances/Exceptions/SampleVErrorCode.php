<?php

namespace Hans\Valravn\Tests\Instances\Exceptions;

use Hans\Valravn\Exceptions\VErrorCode;

class SampleVErrorCode extends VErrorCode
{
    protected static string $prefix = 'SamECx';

    protected int $firstOne = 1;
    protected int $SECOND_ONE = 2;
}
