<?php

	namespace Hans\Valravn\Facades;

	use Illuminate\Support\Facades\Facade;

	class Cache extends Facade {

		protected static function getFacadeAccessor() {
			return 'caching-service';
		}

	}