<?php

namespace App\Http\V1\Controllers\Auth;

use App\Http\V1\Resources\Auth\AuthResource;
use App\Http\V1\Controllers\Global\BaseController;
use App\Http\V1\Requests\Auth\LoginRequest;
use App\Http\V1\Requests\Auth\RegisterRequest;
use App\Services\Auth\LoginService;
use App\Services\Auth\LogoutService;
use App\Services\Auth\RegisterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    public function me(Request $request): JsonResponse
    {
        return $this->successResponse(
            new AuthResource($request->user()),
            'Usuário autenticado.',
        );
    }

    public function login(
        LoginRequest $loginRequest,
        LoginService $loginService
    ): JsonResponse {
        $data = $loginRequest->validated();
        $response = $loginService->run($data);

        return $this->successResponse([
            'token' => $response['data']['token'],
            'me' => new AuthResource($response['data']['user']),
        ], $response['message']);
    }

    public function register(
        RegisterRequest $registerRequest,
        RegisterService $registerService,
    ): JsonResponse {
        $data = $registerRequest->validated();
        $response = $registerService->run($data);

        return $this->successResponse($response['data'], $response['message']);
    }

    public function logout(
        Request $request,
        LogoutService $logoutService,
    ): JsonResponse {
        $data = $request->user();
        $logoutService->run($data);

        return $this->successResponse(null, 'Logout realizado com sucesso.');
    }
}
