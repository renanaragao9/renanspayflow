<?php

namespace App\Http\V1\Requests\Message;

use App\Http\V1\Requests\Global\BaseFormRequest;

class UpdateMessageRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'subject' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', 'string', 'max:255'],
            'channel' => ['sometimes', 'string', 'max:255'],
            'message' => ['sometimes', 'string'],
            'read_at' => ['sometimes', 'date'],
            'user_id' => ['sometimes', 'exists:users,id'],
            'contact_id' => ['sometimes', 'exists:contacts,id'],
            'expense_installment_id' => ['sometimes', 'exists:expense_installments,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'O usuário informado não existe.',
            'contact_id.exists' => 'O contato informado não existe.',
            'expense_installment_id.exists' => 'A parcela de despesa informada não existe.',
        ];
    }
}
