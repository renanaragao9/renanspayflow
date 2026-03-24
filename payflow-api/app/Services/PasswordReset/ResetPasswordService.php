<?php

namespace App\Services\PasswordReset;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordService
{
    public function run(array $data): array
    {
        try {
            $status = Password::reset($data, function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            });

            if ($status === Password::PASSWORD_RESET) {
                return [
                    'status' => 'success',
                    'message' => 'Senha redefinida com sucesso.',
                    'data' => [],
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Erro ao redefinir senha.',
                'data' => [],
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erro ao processar solicitação: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }
}
