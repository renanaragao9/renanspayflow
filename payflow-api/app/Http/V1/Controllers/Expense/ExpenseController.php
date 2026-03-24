<?php

namespace App\Http\V1\Controllers\Expense;

use App\Http\V1\Controllers\Global\BaseController;
use App\Http\V1\Requests\Expense\IndexExpenseRequest;
use App\Http\V1\Requests\Expense\StoreExpenseRequest;
use App\Http\V1\Requests\Expense\UpdateExpenseRequest;
use App\Http\V1\Resources\Expense\ExpenseResource;
use App\Models\Expense;
use App\Services\Expense\DeleteExpenseService;
use App\Services\Expense\IndexExpenseService;
use App\Services\Expense\StoreExpenseService;
use App\Services\Expense\UpdateExpenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExpenseController extends BaseController
{
    public function index(
        IndexExpenseRequest $indexExpenseRequest,
        IndexExpenseService $indexExpenseService,
    ): AnonymousResourceCollection {
        $data = $indexExpenseRequest->validated();
        $expenses = $indexExpenseService->run($data);

        return ExpenseResource::collection($expenses);
    }

    public function show(Expense $expense): JsonResponse
    {
        return $this->successResponse(
            new ExpenseResource($expense),
            'Despesa encontrada com sucesso.'
        );
    }

    public function store(
        StoreExpenseRequest $storeExpenseRequest,
        StoreExpenseService $storeExpenseService
    ): JsonResponse {
        $data = $storeExpenseRequest->validated();
        $expense = $storeExpenseService->run($data);

        return $this->successResponse(
            new ExpenseResource($expense),
            'Despesa criada com sucesso.'
        );
    }

    public function update(
        UpdateExpenseRequest $updateExpenseRequest,
        UpdateExpenseService $updateExpenseService,
        Expense $expense
    ): JsonResponse {
        $data = $updateExpenseRequest->validated();
        $expense = $updateExpenseService->run($expense, $data);

        return $this->successResponse(
            new ExpenseResource($expense),
            'Despesa atualizada com sucesso.'
        );
    }

    public function destroy(
        Expense $expense,
        DeleteExpenseService $deleteExpenseService
    ): JsonResponse {
        $deleteExpenseService->run($expense);

        return $this->successResponse(
            null,
            'Despesa removida com sucesso.'
        );
    }
}
