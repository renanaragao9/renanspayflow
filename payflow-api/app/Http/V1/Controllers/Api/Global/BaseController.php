<?php

namespace App\Http\V1\Controllers\Api\Global;

use App\Http\V1\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    public function successResponse(
        $data = null,
        string $message = 'Dados retornados com sucesso',
        int $statusCode = 200
    ): JsonResponse {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public function errorResponse(
        array $errors,
        string $message,
        int $statusCode = 500
    ): JsonResponse {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
