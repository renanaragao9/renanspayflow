<?php

namespace App\Services\PasswordReset;

use App\Events\PasswordResetRequested;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetService
{
    public function forgotPassword(array $data): array
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

    public function resetPassword(array $data): array
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
                'message' => 'Erro ao processar solicitação: '.$e->getMessage(),
                'data' => [],
            ];
        }
    }
}
