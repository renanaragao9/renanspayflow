<?php

namespace App\Http\V1\Requests\CostCenter;

use App\Http\V1\Requests\Global\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateCostCenterRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $costCenterId = $this->route('cost_center')?->id;

        return [
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:255',
                Rule::unique('cost_centers', 'name')->ignore($costCenterId),
            ],
            'type' => ['sometimes', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'user_id' => ['sometimes', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'name.unique' => 'Já existe um centro de custo com este nome.',
            'type.string' => 'O tipo deve ser um texto.',
            'type.max' => 'O tipo não pode ter mais de 255 caracteres.',
            'due_date.date' => 'A data de vencimento deve ser uma data válida.',
            'user_id.exists' => 'O usuário informado não existe.',
        ];
    }
}
