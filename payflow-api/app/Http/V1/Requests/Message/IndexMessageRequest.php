<?php

namespace App\Http\V1\Requests\Message;

use App\Http\V1\Requests\Global\BaseFormRequest;

class IndexMessageRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return array_merge(
            $this->paginate(),
            $this->orderBy(),
            [
                'search' => ['sometimes', 'string', 'max:255'],
                'user_id' => ['sometimes', 'exists:users,id'],
                'contact_id' => ['sometimes', 'exists:contacts,id'],
                'expense_installment_id' => ['sometimes', 'exists:expense_installments,id'],
            ]
        );
    }

    public function messages(): array
    {
        return [
            'search.string' => 'A busca deve ser um texto.',
            'search.max' => 'A busca não pode ter mais de 255 caracteres.',
            'user_id.exists' => 'O usuário informado não existe.',
            'contact_id.exists' => 'O contato informado não existe.',
            'expense_installment_id.exists' => 'A parcela de despesa informada não existe.',
        ];
    }
}
