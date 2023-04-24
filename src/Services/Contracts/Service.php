<?php

    namespace Hans\Valravn\Services\Contracts;

    use Hans\Valravn\Services\Caching\CachingService;

    abstract class Service {
        public function cache(): CachingService {
            return app( CachingService::class, [ 'service' => $this ] );
        }

        public function cacheWhen( bool $condition ): CachingService|static {
            if ( $condition ) {
                return $this->cache();
            }

            return $this;
        }

    }
