<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\PasswordReset\ForgotPasswordRequest;
use App\Http\Requests\PasswordReset\ResetPasswordRequest;
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

        if ($response['status'] === 'error') {
            return $this->errorResponse([], $response['message']);
        }

        return $this->successResponse($response['data'], $response['message']);
    }

    public function resetPassword(
        ResetPasswordRequest $resetPasswordRequest,
        ResetPasswordService $resetPasswordService
    ): JsonResponse {
        $data = $resetPasswordRequest->validated();
        $response = $resetPasswordService->run($data);

        if ($response['status'] === 'error') {
            return $this->errorResponse([], $response['message']);
        }

        return $this->successResponse($response['data'], $response['message']);
    }
}
