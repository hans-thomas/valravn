<?php

	namespace Hans\Valravn\Facades;

	use Hans\Valravn\Services\Caching\CachingService;
	use Hans\Valravn\Services\Contracts\Service;
	use Illuminate\Support\Facades\Facade;
	use RuntimeException;

	/**
	 * @method static mixed store( string $key, callable $data )
	 * @method static int getInterval()
	 * @method static CachingService setInterval( int $minutes )
	 * @method static CachingService setService( Service $service )
	 *
	 * @see CachingService
	 */
	class Cache extends Facade {

		/**
		 * Get the registered name of the component.
		 *
		 * @return string
		 *
		 * @throws RuntimeException
		 */
		protected static function getFacadeAccessor() {
			return 'caching-service';
		}

	}