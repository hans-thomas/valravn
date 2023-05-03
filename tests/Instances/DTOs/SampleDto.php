<?php

	namespace Hans\Tests\Valravn\Instances\DTOs;

	use Hans\Valravn\DTOs\Contracts\Dto;
	use Illuminate\Support\Collection;

	class SampleDto extends Dto {

		protected function parse( array $data ): Collection {
			return collect( $data['related'] );
		}

	}