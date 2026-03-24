<?php

use App\Http\V1\Controllers\Auth\AuthController;
use App\Http\V1\Controllers\Contact\ContactController;
use App\Http\V1\Controllers\CostCenter\CostCenterController;
use App\Http\V1\Controllers\Expense\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function (): void {
    Route::prefix('v1/auth')->group(function (): void {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('register', [AuthController::class, 'register']);
        Route::get('me', [AuthController::class, 'me']);
    });

    Route::prefix('v1')->group(function (): void {
        Route::apiResource('cost-centers', CostCenterController::class);
        Route::apiResource('contacts', ContactController::class);
        Route::apiResource('expenses', ExpenseController::class);
    });
});
