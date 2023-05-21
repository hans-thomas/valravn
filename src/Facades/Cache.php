<?php

	namespace Hans\Valravn\Facades;

	use Hans\Valravn\Services\Caching\CachingService;
	use Hans\Valravn\Services\Contracts\Service;
	use Illuminate\Support\Facades\Facade;
	use RuntimeException;

	/**
	 * @method static cache( mixed $data )
	 * @method static getInterval()
	 * @method static setInterval( int $minutes )
	 * @method static setService( Service $service )
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