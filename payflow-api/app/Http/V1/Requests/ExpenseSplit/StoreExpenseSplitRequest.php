<?php

namespace App\Http\V1\Requests\ExpenseSplit;

use App\Http\V1\Requests\Global\BaseFormRequest;

class StoreExpenseSplitRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0'],
            'paid' => ['sometimes', 'boolean'],
            'expense_installment_id' => ['required', 'exists:expense_installments,id'],
            'contact_id' => ['required', 'exists:contacts,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'O valor é obrigatório.',
            'amount.numeric' => 'O valor deve ser numérico.',
            'amount.min' => 'O valor deve ser no mínimo 0.',
            'expense_installment_id.required' => 'A parcela de despesa é obrigatória.',
            'expense_installment_id.exists' => 'A parcela de despesa informada não existe.',
            'contact_id.required' => 'O contato é obrigatório.',
            'contact_id.exists' => 'O contato informado não existe.',
        ];
    }
}
