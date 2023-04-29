<?php

	namespace Hans\Valravn\Services\Routing;

	use Illuminate\Routing\Router;
	use Illuminate\Support\Str;

	class ActionsRegisterer {
		private Router $router;
		protected bool $withId = false;
		protected array $parameters = [];
		private array $methods = [ 'GET', 'POST', 'PATCH', 'DELETE' ];

		public function __construct( protected string $name, protected string $controller, protected string $prefix ) {
			$this->router = app( Router::class );
		}

		public function withId(): self {
			$this->withId = true;

			return $this;
		}

		public function parameters( ...$parameters ): self {
			$this->parameters = $parameters;

			return $this;
		}

		public function get( string $action ): void {
			$this->addRoute( 'get', $action );
		}

		public function post( string $action ): void {
			$this->addRoute( 'post', $action );
		}

		public function patch( string $action ): void {
			$this->addRoute( 'patch', $action );
		}

		public function delete( string $action ): void {
			$this->addRoute( 'delete', $action );
		}

		protected function addRoute( string $method, string $action ) {
			$this->registerRoute( $this->makeUri( $action ), Str::of( $method )->upper(), Str::camel( $action ) );
		}

		protected function registerRoute( string $uri, string $method, string $action ): void {
			if ( in_array( $method, $this->methods ) ) {
				$this->router->addRoute( $method, $uri, [ $this->controller, $action ] )
				             ->name( "$this->name.{$this->getRouteNamePrefix()}.$action" );
			}
		}

		protected function addIdParameter( string $uri ): string {
			return trim( $uri, '/' ) . "/{" . $this->name . "}";
		}

		protected function addIdParameterWhen( bool $condition, string $uri ): string {
			if ( $condition ) {
				$uri = $this->addIdParameter( $uri );
			}

			return $uri;
		}

		protected function makeUri( string $action ): string {
			$uri = "/$this->name/$this->prefix";
			$uri = $this->addIdParameterWhen( $this->withId, $uri );

			foreach ( $this->parameters as $parameter ) {
				$uri .= is_array( $parameter ) ?
					"/" . key( $parameter ) . "/{" . current( $parameter ) . "}" :
					"/{" . $parameter . "}";
			}

			return Str::of( $action )->snake()->replace( '_', '-' )->prepend( "$uri/" );
		}

		protected function getRouteNamePrefix(): string {
			return 'actions';
		}
	}
