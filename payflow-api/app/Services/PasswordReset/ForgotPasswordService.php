<?php

namespace App\Services\PasswordReset;

use App\Events\PasswordResetRequested;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class ForgotPasswordService
{
    public function run(array $data): array
    {
        try {
            $user = User::where('email', $data['email'])->first();

            if (! $user) {
                return [
                    'status' => 'error',
                    'message' => 'Usuário não encontrado.',
                    'data' => [],
                ];
            }

            $token = Password::createToken($user);

            event(new PasswordResetRequested($user, $token));

            return [
                'status' => 'success',
                'message' => 'Link de redefinição enviado para o e-mail.',
                'data' => [],
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erro ao processar solicitação: '.$e->getMessage(),
                'data' => [],
            ];
        }
    }
}
