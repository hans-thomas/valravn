<?php

namespace Hans\Valravn\Http\Requests\Contracts\Relations;

use Hans\Valravn\Http\Requests\Contracts\RelationsRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

abstract class BelongsToManyRequest extends RelationsRequest
{
    /**
     * Get related model class.
     *
     * @return string
     */
    abstract protected function model(): string;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'related'      => ['array'],
            'related.*.id' => [
                'required',
                'numeric',
                $this->existence(),
            ],
        ];

        if (!empty($this->pivots())) {
            $rules['related.*.pivot'] = ['array:'.implode(',', array_keys($this->pivots()))];
        }
        foreach ($this->pivots() as $pivot => $validation) {
            $rules["related.*.pivot.$pivot"] = $validation;
        }

        return $rules;
    }

    /**
     * Check requested ids are exist.
     *
     * @return Exists
     */
    protected function existence(): Exists
    {
        return Rule::exists($this->model(), 'id');
    }

    /**
     * Get pivot columns and their validation rules.
     *
     * @return array
     */
    protected function pivots(): array
    {
        return [
            //
        ];
    }
}
