<?php

namespace App\Http\V1\Controllers\PasswordReset;

use App\Http\V1\Controllers\Global\BaseController;
use App\Http\V1\Requests\PasswordReset\ForgotPasswordRequest;
use App\Http\V1\Requests\PasswordReset\ResetPasswordRequest;
use App\Services\PasswordReset\ForgotPasswordService;
use App\Services\PasswordReset\ResetPasswordService;
use Illuminate\Http\JsonResponse;

class PasswordResetController extends BaseController
{
    public function forgotPassword(
        ForgotPasswordRequest $forgotPasswordRequest,
        ForgotPasswordService $forgotPasswordService
    ): JsonResponse {
        $data = $forgotPasswordRequest->validated();
        $response = $forgotPasswordService->run($data);

        return $this->successResponse($response['data'], $response['message']);
    }

    public function resetPassword(
        ResetPasswordRequest $resetPasswordRequest,
        ResetPasswordService $resetPasswordService
    ): JsonResponse {
        $data = $resetPasswordRequest->validated();
        $resetPasswordService->run($data);

        return $this->successResponse([], 'Senha redefinida com sucesso.');
    }
}
