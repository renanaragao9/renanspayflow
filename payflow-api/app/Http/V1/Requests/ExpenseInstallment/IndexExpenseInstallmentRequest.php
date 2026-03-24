<?php

namespace App\Http\V1\Requests\ExpenseInstallment;

use App\Http\V1\Requests\Global\BaseFormRequest;

class IndexExpenseInstallmentRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return array_merge(
            $this->paginate(),
            $this->orderBy(),
            [
                'search' => ['sometimes', 'string', 'max:255'],
                'expense_id' => ['sometimes', 'exists:expenses,id'],
            ]
        );
    }

    public function messages(): array
    {
        return [
            'search.string' => 'A busca deve ser um texto.',
            'search.max' => 'A busca não pode ter mais de 255 caracteres.',
            'expense_id.exists' => 'A despesa informada não existe.',
        ];
    }
}
