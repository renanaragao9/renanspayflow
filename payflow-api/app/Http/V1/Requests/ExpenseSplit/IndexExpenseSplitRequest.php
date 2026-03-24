<?php

namespace App\Http\V1\Requests\ExpenseSplit;

use App\Http\V1\Requests\Global\BaseFormRequest;

class IndexExpenseSplitRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return array_merge(
            $this->paginate(),
            $this->orderBy(),
            [
                'search' => ['sometimes', 'string', 'max:255'],
                'expense_installment_id' => ['sometimes', 'exists:expense_installments,id'],
                'contact_id' => ['sometimes', 'exists:contacts,id'],
            ]
        );
    }

    public function messages(): array
    {
        return [
            'search.string' => 'A busca deve ser um texto.',
            'search.max' => 'A busca não pode ter mais de 255 caracteres.',
            'expense_installment_id.exists' => 'A parcela de despesa informada não existe.',
            'contact_id.exists' => 'O contato informado não existe.',
        ];
    }
}
