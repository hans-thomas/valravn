<?php

	namespace Hans\Valravn\Http\Requests\Contracts\Relations;

	use Hans\Valravn\Http\Requests\Contracts\RelationsRequest;
	use Illuminate\Validation\Rule;

	abstract class MorphToRequest extends RelationsRequest {

		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules(): array {
			return [
				'related'        => [ 'array:entity' ],
				'related.entity' => [ 'required', 'string', Rule::in( $this->entities() ) ],
			];
		}

		/**
		 * Get Allowed entities for MorphTo relationship
		 *
		 * @return array
		 */
		abstract protected function entities(): array;
	}
