<?php

namespace App\Http\V1\Requests\ExpenseInstallment;

use App\Http\V1\Requests\Global\BaseFormRequest;

class UpdateExpenseInstallmentRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'due_date' => ['sometimes', 'date'],
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'installment_number' => ['sometimes', 'integer', 'min:1'],
            'paid' => ['sometimes', 'boolean'],
            'expense_id' => ['sometimes', 'exists:expenses,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'due_date.date' => 'A data de vencimento deve ser uma data válida.',
            'amount.numeric' => 'O valor deve ser numérico.',
            'amount.min' => 'O valor deve ser no mínimo 0.',
            'installment_number.integer' => 'O número da parcela deve ser inteiro.',
            'installment_number.min' => 'O número da parcela deve ser no mínimo 1.',
            'expense_id.exists' => 'A despesa informada não existe.',
        ];
    }
}
