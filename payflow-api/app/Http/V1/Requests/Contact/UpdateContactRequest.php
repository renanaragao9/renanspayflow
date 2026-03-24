<?php

namespace App\Http\V1\Requests\Contact;

use App\Http\V1\Requests\Global\BaseFormRequest;

class UpdateContactRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:3', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'user_id' => ['sometimes', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.email' => 'O email deve ser um email válido.',
            'email.max' => 'O email não pode ter mais de 255 caracteres.',
            'user_id.exists' => 'O usuário informado não existe.',
        ];
    }
}
