<?php

	namespace Hans\Tests\Valravn\Instances\DTOs;

	use Hans\Valravn\DTOs\Contracts\Dto;
	use Illuminate\Support\Collection;

	class SampleDto extends Dto {

		/**
		 * Process the received data
		 *
		 * @param array $data
		 *
		 * @return Collection
		 */
		protected function parse( array $data ): Collection {
			return collect( $data['related'] );
		}

	}