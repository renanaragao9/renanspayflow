<?php

namespace App\Http\V1\Requests\ExpenseSplit;

use App\Http\V1\Requests\Global\BaseFormRequest;

class UpdateExpenseSplitRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'paid' => ['sometimes', 'boolean'],
            'expense_installment_id' => ['sometimes', 'exists:expense_installments,id'],
            'contact_id' => ['sometimes', 'exists:contacts,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.numeric' => 'O valor deve ser numérico.',
            'amount.min' => 'O valor deve ser no mínimo 0.',
            'expense_installment_id.exists' => 'A parcela de despesa informada não existe.',
            'contact_id.exists' => 'O contato informado não existe.',
        ];
    }
}
