<?php

	namespace Hans\Valravn\Exceptions\Valravn;

	use Hans\Valravn\Exceptions\ErrorCode;

	class ValravnErrorCode extends ErrorCode {
		protected static string $prefix = 'ValravnECx';

		protected int $failedToDelete = 1;

	}