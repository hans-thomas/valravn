<?php

namespace Hans\Valravn\Http\Requests\Contracts;

    use Illuminate\Foundation\Http\FormRequest;

    abstract class RelationsRequest extends FormRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        abstract public function rules(): array;

        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize(): bool
        {
            return true;
        }
    }
