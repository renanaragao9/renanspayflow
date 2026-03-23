<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public function run(array $data): array
    {
        return DB::transaction(callback: function () use ($data): array {
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);

            return [
                'status' => 'success',
                'message' => 'Usuário registrado com sucesso! Verifique seu e-mail para ativar sua conta.',
                'data' => [
                    'user' => $user,
                ],
            ];
        });
    }
}
