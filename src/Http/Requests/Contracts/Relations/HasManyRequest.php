<?php

	namespace Hans\Valravn\Http\Requests\Contracts\Relations;

	use Hans\Valravn\Http\Requests\Contracts\RelationsRequest;
	use Illuminate\Validation\Rule;
	use Illuminate\Validation\Rules\Exists;

	abstract class HasManyRequest extends RelationsRequest {

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
		public function rules(): array {
			return [
				'related'      => [ 'array' ],
				'related.*.id' => [
					'required',
					'numeric',
					$this->existence()
				],
			];
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
