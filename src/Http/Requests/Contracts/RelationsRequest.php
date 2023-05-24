<?php

	namespace Hans\Valravn\Http\Requests\Contracts;

	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Validation\Rule;
	use Illuminate\Validation\Rules\Exists;

	abstract class RelationsRequest extends FormRequest {

		/**
		 * Get related model class
		 *
		 * @return string
		 */
		abstract protected function model(): string;

		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize(): bool {
			return true;
		}

		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules(): array {
			$rules = [
				'related'      => [ 'array' ],
				'related.*.id' => [
					'required',
					'numeric',
					$this->existence()
				],
			];

			if ( ! empty( $this->pivots() ) ) {
				$rules[ "related.*.pivot" ] = [ 'array:' . implode( ',', array_keys( $this->pivots() ) ) ];
			}
			foreach ( $this->pivots() as $pivot => $validation ) {
				$rules[ "related.*.pivot.$pivot" ] = $validation;
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

		/**
		 * Get pivot columns and their validation rules
		 *
		 * @return array
		 */
		protected function pivots(): array {
			return [];
		}

	}
