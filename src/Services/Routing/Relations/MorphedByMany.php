<?php

	namespace Hans\Valravn\Services\Routing\Relations;

	use Hans\Valravn\Services\Contracts\Routeing\Relations;

	class MorphedByMany extends Relations {

		protected function routes( string $parameter, string $action ): void {
			$this->get( "/{model}/$parameter", $action );
			$this->post( "/{model}/$parameter", $action );
			$this->attach( "/{model}/$parameter", $action );
			$this->detach( "/{model}/$parameter", $action );
		}
	}