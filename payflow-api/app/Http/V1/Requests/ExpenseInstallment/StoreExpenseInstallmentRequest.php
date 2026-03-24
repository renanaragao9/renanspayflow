<?php

namespace App\Http\V1\Requests\ExpenseInstallment;

use App\Http\V1\Requests\Global\BaseFormRequest;

class StoreExpenseInstallmentRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'due_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'installment_number' => ['required', 'integer', 'min:1'],
            'paid' => ['sometimes', 'boolean'],
            'expense_id' => ['required', 'exists:expenses,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'due_date.required' => 'A data de vencimento é obrigatória.',
            'due_date.date' => 'A data de vencimento deve ser uma data válida.',
            'amount.required' => 'O valor é obrigatório.',
            'amount.numeric' => 'O valor deve ser numérico.',
            'amount.min' => 'O valor deve ser no mínimo 0.',
            'installment_number.required' => 'O número da parcela é obrigatório.',
            'installment_number.integer' => 'O número da parcela deve ser inteiro.',
            'installment_number.min' => 'O número da parcela deve ser no mínimo 1.',
            'expense_id.required' => 'A despesa é obrigatória.',
            'expense_id.exists' => 'A despesa informada não existe.',
        ];
    }
}
