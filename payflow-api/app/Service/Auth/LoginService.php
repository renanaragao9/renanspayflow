<?php

namespace App\Services\Auth;

use App\Models\User;

class LoginService
{
    public function run(array $data): array
    {
        $user = User::where('email', $data['email'])->first();
        $user->update([
            'last_access' => now(),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'status' => 'success',
            'message' => 'Login realizado com sucesso.',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ];
    }
}
