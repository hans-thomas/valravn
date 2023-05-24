<?php

	namespace Hans\Tests\Valravn\Instances\Http\Requests;

	use Hans\Valravn\Http\Requests\Contracts\Relations\MorphToRequest;

	class LikeLikableRequest extends MorphToRequest {

		/**
		 * Get Allowed entities for MorphTo relationship
		 *
		 * @return array
		 */
		protected function entities(): array {
			return [ 'posts', 'comments' ];
		}

	}