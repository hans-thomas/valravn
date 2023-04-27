<?php

	namespace Hans\Valravn\Facades;

	use Illuminate\Support\Facades\Facade;

	class Router extends Facade {

		protected static function getFacadeAccessor() {
			return 'routing-service';
		}

	}