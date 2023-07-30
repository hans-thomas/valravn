<?php

namespace Hans\Valravn\Exceptions\Package;

    use Hans\Valravn\Exceptions\ErrorCode;

    class PackageErrorCode extends ErrorCode
    {
        protected static string $prefix = 'ValravnECx';

        protected int $failedToDelete = 1;
        protected int $invalidEntity = 2;
    }
