<?php

	namespace Hans\Valravn\Facades;

	use Hans\Valravn\Services\Filtering\FilteringService;
	use Illuminate\Contracts\Database\Eloquent\Builder;
	use Illuminate\Support\Facades\Facade;
	use RuntimeException;

	/**
	 * @method static FilteringService apply( Builder $builder, array $options )
	 * @method static FilteringService getRegistered()
	 * @see FilteringService
	 */
	class Filter extends Facade {

		/**
		 * Get the registered name of the component.
		 *
		 * @return string
		 *
		 * @throws RuntimeException
		 */
		protected static function getFacadeAccessor() {
			return 'filtering-service';
		}

	}