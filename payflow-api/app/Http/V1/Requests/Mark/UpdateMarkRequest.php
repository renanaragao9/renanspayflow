<?php

namespace App\Http\V1\Requests\Mark;

use App\Http\V1\Requests\Global\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateMarkRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $markId = $this->route('mark')?->id;

        return [
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:255',
                Rule::unique('marks', 'name')->ignore($markId),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'country' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'name.unique' => 'Já existe uma marca com este nome.',
            'description.string' => 'A descrição deve ser um texto.',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
            'country.string' => 'O país deve ser um texto.',
            'country.max' => 'O país não pode ter mais de 255 caracteres.',
        ];
    }
}
