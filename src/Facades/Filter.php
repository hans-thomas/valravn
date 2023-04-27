<?php

	namespace Hans\Valravn\Facades;

	use Hans\Valravn\Services\Filtering\FilteringService;
	use Illuminate\Contracts\Database\Eloquent\Builder;
	use Illuminate\Support\Facades\Facade;

	class Filter extends Facade {
		/**
		 * @method static FilteringService apply( Builder $builder, array $options )
		 * @method static FilteringService getRegistered()
		 * @method static FilteringService registerFilters( array $filters )
		 * @see FilteringService
		 */
		protected static function getFacadeAccessor() {
			return 'filtering-service';
		}


	}