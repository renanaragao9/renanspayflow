<?php

namespace App\Http\V1\Requests\Expense;

use App\Http\V1\Requests\Global\BaseFormRequest;

class UpdateExpenseRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $expenseId = $this->route('expense')?->id;

        return [
            'description' => ['sometimes', 'string', 'max:1000'],
            'purchase_date' => ['sometimes', 'date'],
            'total_amount' => ['sometimes', 'numeric', 'min:0'],
            'installments' => ['sometimes', 'integer', 'min:1'],
            'cost_center_id' => ['sometimes', 'exists:cost_centers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'description.string' => 'A descrição deve ser um texto.',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
            'purchase_date.date' => 'A data de compra deve ser uma data válida.',
            'total_amount.numeric' => 'O valor total deve ser numérico.',
            'total_amount.min' => 'O valor total deve ser no mínimo 0.',
            'installments.integer' => 'O número de parcelas deve ser um inteiro.',
            'installments.min' => 'O número de parcelas deve ser no mínimo 1.',
            'cost_center_id.exists' => 'O centro de custo informado não existe.',
        ];
    }
}
