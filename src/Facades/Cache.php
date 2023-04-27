<?php

	namespace Hans\Valravn\Facades;

	use Hans\Valravn\Services\Caching\CachingService;
	use Illuminate\Support\Facades\Facade;

	/**
	 * @method static CachingService getInterval()
	 * @method static CachingService setInterval( int $minutes )
	 * @see CachingService
	 */
	class Cache extends Facade {

		protected static function getFacadeAccessor() {
			return 'caching-service';
		}

	}