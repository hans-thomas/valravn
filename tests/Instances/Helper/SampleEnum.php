<?php

namespace Hans\Valravn\Tests\Instances\Helper;

use Hans\Valravn\Helpers\Traits\EnumHelper;

enum SampleEnum
{
    use EnumHelper;

    case FIRST;
    case SECOND;
    case THIRD;
}
