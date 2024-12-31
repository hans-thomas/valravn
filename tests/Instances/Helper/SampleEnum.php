<?php

namespace Hans\Valravn\Tests\Instances\Helper;

use Hans\Valravn\Helpers\Traits\VEnumHelper;

enum SampleEnum
{
    use VEnumHelper;

    case FIRST;
    case SECOND;
    case THIRD;
}
