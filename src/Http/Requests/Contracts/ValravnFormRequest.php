<?php

namespace Hans\Valravn\Http\Requests\Contracts;

    use Illuminate\Foundation\Http\FormRequest;

    abstract class ValravnFormRequest extends FormRequest
    {
        /**
         * Get fields and their validation rules.
         *
         * @return array
         */
        abstract protected function fields(): array;

        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize()
        {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules()
        {
            return $this->fields();
        }
    }
