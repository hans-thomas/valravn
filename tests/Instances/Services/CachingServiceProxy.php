<?php

	namespace Hans\Tests\Valravn\Instances\Services;

	use Hans\Valravn\Services\Caching\CachingService;

	class CachingServiceProxy extends CachingService {

		public function _makeCachingKey() {
			return $this->makeCachingKey( ...func_get_args() );
		}

		public function _calcTtlTime() {
			return $this->calcTtlTime();
		}

	}