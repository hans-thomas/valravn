<?php

	namespace Hans\Valravn\Services\Routing;

	use BadMethodCallException;
	use Illuminate\Contracts\Foundation\Application;
	use Illuminate\Routing\PendingResourceRegistration;
	use Illuminate\Routing\RouteRegistrar;

	class RoutingService {

		/**
		 * Route registerer
		 *
		 * @var RouteRegistrar|Application|\Illuminate\Foundation\Application|mixed
		 */
		protected RouteRegistrar $registrar;

		/**
		 * @var PendingResourceRegistration
		 */
		protected PendingResourceRegistration $pendingResourceRegistration;

		/**
		 * Name of the entity
		 *
		 * @var string
		 */
		private string $name;

		/**
		 * Related controller class
		 *
		 * @var string
		 */
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

		/**
		 * Register CRUD routes for api
		 *
		 * @param string $name
		 * @param string $controller
		 *
		 * @return $this
		 */
		public function apiResource( string $name, string $controller ): self {
			$this->name = $name;

			if ( class_exists( $this->controller = $controller ) ) {
				$this->pendingResourceRegistration = $this->registrar->apiResource( $this->name, $this->controller );
			}

			return $this;
		}


		/**
		 * Register CRUD routes for MPA
		 *
		 * @param string $name
		 * @param string $controller
		 *
		 * @return $this
		 */
		public function resource( string $name, string $controller ): self {
			$this->name = $name;

			if ( class_exists( $this->controller = $controller ) ) {
				$this->pendingResourceRegistration = $this->registrar->resource( $this->name, $this->controller );
			}

			return $this;
		}

		/**
		 * Register relations routes
		 *
		 * @param string   $controller
		 * @param callable $func
		 *
		 * @return $this
		 */
		public function relations( string $controller, callable $func ): self {
			$func( app( RelationsRegisterer::class, [ 'name' => $this->name, 'controller' => $controller ] ) );

			return $this;
		}

		/**
		 * Register custom actions routes
		 *
		 * @param string   $controller
		 * @param callable $func
		 *
		 * @return $this
		 */
		public function actions( string $controller, callable $func ): self {
			$func(
				app(
					ActionsRegisterer::class,
					[ 'name' => $this->name, 'controller' => $controller ]
				)
			);

			return $this;
		}

		/**
		 * Register gathering routes
		 *
		 * @param string   $controller
		 * @param callable $func
		 *
		 * @return $this
		 */
		public function gathering( string $controller, callable $func ): self {
			$func(
				app(
					GatheringRegisterer::class,
					[ 'name' => $this->name, 'controller' => $controller ]
				)
			);

			return $this;
		}

		/**
		 * Register a batch update route
		 *
		 * @return $this
		 */
		public function withBatchUpdate(): self {
			$this->registrar->patch( $this->name, [ $this->controller, 'batchUpdate' ] )
			                ->name( "$this->name.batch-update" );

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
