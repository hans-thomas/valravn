<?php

	namespace Hans\Valravn\Facades;

	use Hans\Valravn\Services\Routing\RoutingService;
	use Illuminate\Support\Facades\Facade;

	/**
	 * @method static RoutingService resource( string $name, string $controller )
	 * @method static RoutingService relations( string $controller, callable $func )
	 * @method static RoutingService actions( string $controller, callable $func, string $prefix = '-actions' )
	 * @method static RoutingService gathering( string $controller, callable $func, string $prefix = '-gathering' )
	 * @method static RoutingService withBatchUpdate()
	 * @see RoutingService
	 */
	class Router extends Facade {

		protected static function getFacadeAccessor() {
			return 'routing-service';
		}

	}