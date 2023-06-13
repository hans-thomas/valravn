<?php

	namespace Hans\Valravn\Tests\Instances\Services;

	use Hans\Valravn\Services\Contracts\Service;

	class SampleService extends Service {

		public function addition( int $first, int $second ): int {
			return $first + $second;
		}

		public function subtraction( int $first, int $second ): int {
			return $first - $second;
		}

		public function division( int $first, int $second ): int {
			return $first / $second;
		}

		public function multiplication( int $first, int $second ): int {
			return $first * $second;
		}

	}