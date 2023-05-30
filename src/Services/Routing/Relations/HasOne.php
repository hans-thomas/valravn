<?php

	namespace Hans\Valravn\Services\Routing\Relations;

	use Hans\Valravn\Services\Contracts\Routeing\Relations;

	class HasOne extends Relations {

		protected function routes( string $name, string $parameter, string $action ): void {
			$this->get( "{" . $name . "}/$parameter", $action );
			$this->post( "{" . $name . "}/$parameter/{related}", $action );
		}
	}