<?php

	namespace Hans\Valravn\Services\Routing\Relations;

	use Hans\Valravn\Services\Contracts\Routeing\Relations;

	class MorphTo extends Relations {

		protected function routes( string $parameter, string $action ): void {
			$this->get( "{model}/$parameter", $action );
			$this->post( "{model}/$parameter/{related}", $action );
		}
	}