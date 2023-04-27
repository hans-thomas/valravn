<?php

	namespace Hans\Valravn\Exceptions\Valravn;

	use Hans\Valravn\Exceptions\ErrorCode;

	class ValravnErrorCode extends ErrorCode {
		protected static string $prefix = 'ValravnECx';

		protected int $FAILED_TO_EXECUTE_DELETING_HOOK = 1;

	}