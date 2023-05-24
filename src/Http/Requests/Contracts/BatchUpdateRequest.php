<?php

	namespace Hans\Valravn\Http\Requests\Contracts;

	use Illuminate\Validation\Rule;
	use Illuminate\Validation\Rules\Exists;

	abstract class BatchUpdateRequest extends ValravnFormRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
		abstract protected function model(): string;

		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules() {
			$rules = [
				'batch'      => [ 'array' ],
				'batch.*.id' => [ 'required', 'numeric', $this->existence() ],
			];

			foreach ( $this->fields() as $field => $validation ) {
				$rules[ "batch.*.$field" ] = $validation;
			}

			return $rules;
		}

		/**
		 * Check requested ids are exist
		 *
		 * @return Exists
		 */
		protected function existence(): Exists {
			return Rule::exists( $this->model(), 'id' );
		}


	}
