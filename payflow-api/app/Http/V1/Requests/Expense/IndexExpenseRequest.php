<?php

namespace App\Http\V1\Requests\Expense;

use App\Http\V1\Requests\Global\BaseFormRequest;

class IndexExpenseRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return array_merge(
            $this->paginate(),
            $this->orderBy(),
            [
                'search' => ['sometimes', 'string', 'max:255'],
            ]
        );
    }

    public function messages(): array
    {
        return [
            'search.string' => 'A busca deve ser um texto.',
            'search.max' => 'A busca não pode ter mais de 255 caracteres.',
        ];
    }
}
