<?php

	namespace Hans\Valravn\Http\Requests\Contracts;

	abstract class BatchUpdateRequest extends ValravnFormRequest {

		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules() {
			$rules = [
				'batch' => [ 'array' ],
			];

			foreach ( $this->fields() as $field => $validation ) {
				$rules[ "batch.*.$field" ] = $validation;
			}

			return $rules;
		}
	}
