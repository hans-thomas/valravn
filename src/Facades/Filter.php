<?php

	namespace Hans\Valravn\Facades;

	use Illuminate\Support\Facades\Facade;

	class Filter extends Facade {
		protected static function getFacadeAccessor() {
			return 'filtering-service';
		}


	}