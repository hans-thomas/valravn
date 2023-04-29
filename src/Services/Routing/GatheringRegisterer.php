<?php

	namespace Hans\Valravn\Services\Routing;

	use Illuminate\Support\Str;

	class GatheringRegisterer extends ActionsRegisterer {
		protected int $version = 1;

		protected function resetStates(): void {
			parent::resetStates();
			$this->version = 1;
		}

		protected function getRouteNamePrefix(): string {
			return 'gatherings';
		}

		protected function getPrefix(): string {
			return '-gathering/v' . $this->version;
		}

		public function version( int $version ): self {
			$this->version = $version;

			return $this;
		}
	}
