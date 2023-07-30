<?php

namespace Hans\Valravn\Tests\Instances\Services;

use Hans\Valravn\Services\Queries\QueryingService;

class QueryingServiceProxy extends QueryingService
{
    public function _getExecutedQueries(): array
    {
        return $this->getExecutedQueries();
    }
}
