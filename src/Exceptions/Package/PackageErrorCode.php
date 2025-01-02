<?php

namespace Hans\Valravn\Exceptions\Package;

use Hans\Valravn\Exceptions\VErrorCode;

class PackageErrorCode extends VErrorCode
{
    protected static string $prefix = 'ValravnECx';

    public static int $failedToDelete = 1;
    public static int $invalidEntity = 2;
    public static int $errorCodeNotFound = 3;
}
