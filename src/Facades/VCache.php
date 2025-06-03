<?php

namespace Hans\Valravn\Facades;

use Hans\Valravn\Services\Caching\CachingService;
use Hans\Valravn\Services\Contracts\VService;
use Illuminate\Support\Facades\Facade;
use RuntimeException;

/**
 * @method static mixed          store( string $key, callable $data )
 * @method static int            getInterval()
 * @method static CachingService setInterval( int $minutes )
 * @method static CachingService setService(VService $service )
 *
 * @see CachingService
 */
class VCache extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @throws RuntimeException
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'v-caching-service';
    }
}
