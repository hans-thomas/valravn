<?php

    namespace Hans\Valravn\Services\Routing;

    use Illuminate\Support\Str;

    class GatheringRegisterer extends ActionsRegisterer {
        protected int $version = 1;

        protected function makeUri( string $action ): string {
            $uri = "/$this->name/$this->prefix";
            $uri = $this->addIdParameterWhen( $this->withId, $uri );

            return Str::of( $action )->snake()->replace( '_', '-' )->prepend( "$uri/v$this->version/" );
        }

        protected function getRouteNamePrefix(): string {
            return 'gatherings';
        }

        public function version( int $version ): self {
            $this->version = $version;

            return $this;
        }
    }
