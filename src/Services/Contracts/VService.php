<?php

namespace Hans\Valravn\Services\Contracts;

use Hans\Valravn\Facades\VCache;
use Hans\Valravn\Services\Caching\CachingService;

abstract class VService
{
    /**
     * Cache method's result called on service instance.
     *
     * @return CachingService
     */
    public function cache(): CachingService
    {
        return VCache::setService($this);
    }

    /**
     * Cache methods when condition is true.
     *
     * @param bool $condition
     *
     * @return CachingService|$this
     */
    public function cacheWhen(bool $condition): CachingService|static
    {
        if ($condition) {
            return $this->cache();
        }

        return $this;
    }
}
