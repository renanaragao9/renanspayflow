<?php

namespace App\Http\V1\Requests\Expense;

use App\Http\V1\Requests\Global\BaseFormRequest;

class StoreExpenseRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'max:1000'],
            'purchase_date' => ['required', 'date'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'installments' => ['required', 'integer', 'min:1'],
            'cost_center_id' => ['required', 'exists:cost_centers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'A descrição é obrigatória.',
            'description.string' => 'A descrição deve ser um texto.',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
            'purchase_date.required' => 'A data de compra é obrigatória.',
            'purchase_date.date' => 'A data de compra deve ser uma data válida.',
            'total_amount.required' => 'O valor total é obrigatório.',
            'total_amount.numeric' => 'O valor total deve ser numérico.',
            'total_amount.min' => 'O valor total deve ser no mínimo 0.',
            'installments.required' => 'O número de parcelas é obrigatório.',
            'installments.integer' => 'O número de parcelas deve ser um inteiro.',
            'installments.min' => 'O número de parcelas deve ser no mínimo 1.',
            'cost_center_id.required' => 'O centro de custo é obrigatório.',
            'cost_center_id.exists' => 'O centro de custo informado não existe.',
        ];
    }
}
