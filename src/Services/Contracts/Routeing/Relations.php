<?php

	namespace Hans\Valravn\Services\Contracts\Routeing;

	use Illuminate\Routing\Router;
	use Illuminate\Routing\RouteRegistrar;
	use Illuminate\Support\Str;

	abstract class Relations {

		/**
		 * Determine the instance is registered or not
		 *
		 * @var bool
		 */
		protected bool $registered = false;

		/**
		 * List of <name,method>
		 *
		 * @var array|string[]
		 */
		protected array $attributes = [
			'view'   => 'GET',
			'update' => 'POST',
			'attach' => 'PATCH',
			'detach' => 'DELETE'
		];

		/**
		 * Options that apply before registration
		 *
		 * @var array
		 */
		protected array $options;

		/**
		 * List of requested routes to be register
		 *
		 * @var array
		 */
		protected array $routes;

		public function __construct( protected string $name, protected string $relation, protected RouteRegistrar $registrar ) { }


		/**
		 * Get needed routes for the relation instance
		 *
		 * @param string $name
		 * @param string $parameter
		 * @param string $action
		 *
		 * @return void
		 */
		abstract protected function routes( string $name, string $parameter, string $action ): void;

		/**
		 * Register requested routes
		 *
		 * @return void
		 */
		protected function register(): void {
			$this->registered = true;

			$name      = Str::of( $this->name )->singular()->camel()->snake()->toString();
			$action    = Str::of( $this->relation )->camel()->ucfirst();
			$parameter = Str::lower( $this->relation );

			$this->routes( $name, $parameter, $action );

			$this->registrar->group( function( Router $registrar ) use ( $action ) {
				foreach ( $this->routes as $route ) {
					if ( in_array( $route[ 'name' ], array_keys( $this->getMethods() ) ) ) {
						$registrar->{$route[ 'method' ]}( $route[ 'uri' ], $route[ 'action' ] )
						          ->name( $route[ 'name' ] );
					}
				}
			} );
		}

		/**
		 * Apply options and return the list of methods
		 *
		 * @return array|string[]
		 */
		protected function getMethods(): array {
			$methods = $this->attributes;

			if ( isset( $this->options[ 'only' ] ) ) {
				$methods = array_intersect_key( $methods, array_flip( (array) $this->options[ 'only' ] ) );
			}

			if ( isset( $this->options[ 'except' ] ) ) {
				$methods = array_diff_key( $methods, array_flip( (array) $this->options[ 'except' ] ) );
			}

			return $methods;
		}

		/**
		 * Except given methods to register
		 *
		 * @param $methods
		 *
		 * @return $this
		 */
		public function except( $methods ): self {
			$methods = is_array( $methods ) ? $methods : func_get_args();

			$this->options[ 'except' ] = $methods;

			return $this;
		}

		/**
		 * Only given methods will register
		 *
		 * @param $methods
		 *
		 * @return $this
		 */
		public function only( $methods ): self {
			$methods = is_array( $methods ) ? $methods : func_get_args();

			$this->options[ 'only' ] = $methods;

			return $this;
		}

		/**
		 * Define a get route
		 *
		 * @param string $uri
		 * @param string $action
		 *
		 * @return void
		 */
		protected function get( string $uri, string $action ): void {
			$this->routes[] = [
				'uri'    => $uri,
				'method' => 'get',
				'action' => "view$action",
				'name'   => 'view'
			];
		}

		/**
		 * Define a post route
		 *
		 * @param string $uri
		 * @param string $action
		 *
		 * @return void
		 */
		protected function post( string $uri, string $action ): void {
			$this->routes[] = [
				'uri'    => $uri,
				'method' => 'post',
				'action' => "update$action",
				'name'   => 'update'
			];
		}

		/**
		 * Define a attach route
		 *
		 * @param string $uri
		 * @param string $action
		 *
		 * @return void
		 */
		protected function attach( string $uri, string $action ): void {
			$this->routes[] = [
				'uri'    => $uri,
				'method' => 'patch',
				'action' => "attach$action",
				'name'   => 'attach'
			];
		}

		/**
		 * Define a detach route
		 *
		 * @param string $uri
		 * @param string $action
		 *
		 * @return void
		 */
		protected function detach( string $uri, string $action ): void {
			$this->routes[] = [
				'uri'    => $uri,
				'method' => 'delete',
				'action' => "detach$action",
				'name'   => 'detach'
			];
		}

		public function __destruct() {
			if ( ! $this->registered ) {
				$this->register();
			}
		}
	}
