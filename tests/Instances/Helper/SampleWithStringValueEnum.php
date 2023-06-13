<?php

	namespace Hans\Valravn\Tests\Instances\Helper;

	use Hans\Valravn\Helpers\Traits\EnumHelper;

	enum SampleWithStringValueEnum: string {
		use EnumHelper;

		case FIRST = 'first value';
		case SECOND = 'second value';
		case THIRD = 'third value';
	}