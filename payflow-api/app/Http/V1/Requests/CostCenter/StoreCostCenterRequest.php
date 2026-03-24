<?php

namespace App\Http\V1\Requests\CostCenter;

use App\Http\V1\Requests\Global\BaseFormRequest;

class StoreCostCenterRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:cost_centers,name'],
            'type' => ['required', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'name.unique' => 'Já existe um centro de custo com este nome.',
            'type.required' => 'O tipo é obrigatório.',
            'type.string' => 'O tipo deve ser um texto.',
            'type.max' => 'O tipo não pode ter mais de 255 caracteres.',
            'due_date.date' => 'A data de vencimento deve ser uma data válida.',
            'user_id.required' => 'O usuário é obrigatório.',
            'user_id.exists' => 'O usuário informado não existe.',
        ];
    }
}
