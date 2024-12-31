<?php

namespace Hans\Valravn\Exceptions\Package;

use Hans\Valravn\Exceptions\VErrorCode;

class PackageErrorCode extends VErrorCode
{
    protected static string $prefix = 'ValravnECx';

    protected int $failedToDelete = 1;
    protected int $invalidEntity = 2;
    protected int $errorCodeNotFound = 3;
}
