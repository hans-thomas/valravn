<?php

    namespace Hans\Valravn\Services\Contracts\Routeing;

    use Illuminate\Routing\Router;
    use Illuminate\Routing\RouteRegistrar;
    use Illuminate\Support\Str;

    abstract class Relations {
        protected bool $registered = false;
        protected array $attributes = [
            'view'   => 'GET',
            'update' => 'POST',
            'attach' => 'PATCH',
            'detach' => 'DELETE'
        ];
        protected array $options;
        protected array $routes;

        public function __construct( protected string $relation, protected RouteRegistrar $registrar ) { }

        protected function register(): void {
            $this->registered = true;
            $action           = Str::of( $this->relation )->camel()->ucfirst();
            $parameter        = Str::lower( $this->relation );
            $this->routes( $parameter, $action );

            $this->registrar->group( function( Router $registrar ) use ( $action ) {
                foreach ( $this->routes as $route ) {
                    if ( in_array( $route[ 'name' ], array_keys( $this->getMethods() ) ) ) {
                        $registrar->{$route[ 'method' ]}( $route[ 'uri' ], $route[ 'action' ] )
                                  ->name( $route[ 'name' ] );
                    }
                }
            } );
        }

        public function __destruct() {
            if ( ! $this->registered ) {
                $this->register();
            }
        }

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

        public function except( $methods ): self {
            $methods = is_array( $methods ) ? $methods : func_get_args();

            $this->options[ 'except' ] = $methods;

            return $this;
        }

        public function only( $methods ): self {
            $methods = is_array( $methods ) ? $methods : func_get_args();

            $this->options[ 'only' ] = $methods;

            return $this;
        }

        protected function get( string $uri, string $action ): void {
            $this->routes[] = [
                'uri'    => $uri,
                'method' => 'get',
                'action' => "view$action",
                'name'   => 'view'
            ];
        }

        protected function post( string $uri, string $action ): void {
            $this->routes[] = [
                'uri'    => $uri,
                'method' => 'post',
                'action' => "update$action",
                'name'   => 'update'
            ];
        }

        protected function attach( string $uri, string $action ): void {
            $this->routes[] = [
                'uri'    => $uri,
                'method' => 'patch',
                'action' => "attach$action",
                'name'   => 'attach'
            ];
        }

        protected function detach( string $uri, string $action ): void {
            $this->routes[] = [
                'uri'    => $uri,
                'method' => 'delete',
                'action' => "detach$action",
                'name'   => 'detach'
            ];
        }

        abstract protected function routes( string $parameter, string $action ): void;
    }
