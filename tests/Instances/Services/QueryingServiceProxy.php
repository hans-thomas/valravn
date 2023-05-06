<?php

	namespace Hans\Tests\Valravn\Instances\Services;

	use Hans\Valravn\Services\Queries\QueryingService;

	class QueryingServiceProxy extends QueryingService {

		public function _getExecutedQueries(): array {
			return $this->getExecutedQueries();
		}

	}