<?php

	namespace Hans\Valravn\Services\Routing;

	use BadMethodCallException;
	use Illuminate\Routing\PendingResourceRegistration;
	use Illuminate\Routing\RouteRegistrar;

	class RoutingService {
		private RouteRegistrar $registrar;
		private PendingResourceRegistration $pendingResourceRegistration;
		private string $name;
		private string $controller;

		public function __construct() {
			$this->registrar = app( RouteRegistrar::class );
		}

		/**
		 * If there is no crud controller
		 *
		 * @param string $name
		 *
		 * @return $this
		 */
		public function name( string $name ): self {
			$this->name = $name;

			return $this;
		}

		public function resource( string $name, string $controller ): self {
			$this->name = $name;

			if ( class_exists( $this->controller = $controller ) ) {
				$this->pendingResourceRegistration = $this->registrar->apiResource( $this->name, $this->controller );
			}

			return $this;
		}

		public function relations( string $controller, callable $func ): self {
			$func( app( RelationsRegisterer::class, [ 'name' => $this->name, 'controller' => $controller ] ) );

			return $this;
		}

		public function actions( string $controller, callable $func, string $prefix = '-actions' ): self {
			$func( app( ActionsRegisterer::class,
				[ 'name' => $this->name, 'controller' => $controller, 'prefix' => $prefix ] ) );

			return $this;
		}

		public function gathering( string $controller, callable $func, string $prefix = '-gathering' ): self {
			$func( app( GatheringRegisterer::class,
				[ 'name' => $this->name, 'controller' => $controller, 'prefix' => $prefix ] ) );

			return $this;
		}

		public function withBatchUpdate(): self {
			$this->registrar->patch( $this->name, [ $this->controller, 'batchUpdate' ] )
			                ->name( "$this->name.batchUpdate" );

			return $this;
		}


		public function __call( string $method, array $parameters ) {
			if ( method_exists( $this->pendingResourceRegistration, $method ) ) {
				$this->pendingResourceRegistration->{$method}( ...$parameters );

				return $this;
			}

			throw new BadMethodCallException( sprintf(
				'Method %s::%s does not exist.', static::class, $method
			) );
		}

	}
