<?php

namespace Hans\Valravn\Tests\Instances\Helper;

use Hans\Valravn\Helpers\Traits\VEnumHelper;

enum SampleWithStringValueEnum: string
{
    use VEnumHelper;

    case FIRST = 'first value';
    case SECOND = 'second value';
    case THIRD = 'third value';
}
