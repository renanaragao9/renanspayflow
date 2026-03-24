<?php

namespace App\Http\V1\Requests\Message;

use App\Http\V1\Requests\Global\BaseFormRequest;

class StoreMessageRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'subject' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'read_at' => ['nullable', 'date'],
            'user_id' => ['required', 'exists:users,id'],
            'contact_id' => ['required', 'exists:contacts,id'],
            'expense_installment_id' => ['nullable', 'exists:expense_installments,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'O tipo é obrigatório.',
            'channel.required' => 'O canal é obrigatório.',
            'message.required' => 'A mensagem é obrigatória.',
            'user_id.required' => 'O usuário é obrigatório.',
            'user_id.exists' => 'O usuário informado não existe.',
            'contact_id.required' => 'O contato é obrigatório.',
            'contact_id.exists' => 'O contato informado não existe.',
            'expense_installment_id.exists' => 'A parcela de despesa informada não existe.',
        ];
    }
}
