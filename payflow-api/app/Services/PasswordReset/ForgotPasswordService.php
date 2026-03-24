<?php

namespace App\Services\PasswordReset;

use App\Events\PasswordResetRequested;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class ForgotPasswordService
{
    public function run(array $data): array
    {
        $user = User::where('email', $data['email'])->first();
        $token = Password::createToken($user);

        event(new PasswordResetRequested($user, $token));

        return [
            'status' => 'success',
            'message' => 'Link de redefinição enviado para o e-mail.',
            'data' => [],
        ];
    }
}
