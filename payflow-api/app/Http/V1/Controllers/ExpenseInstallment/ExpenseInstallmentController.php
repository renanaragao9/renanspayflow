<?php

namespace App\Http\V1\Controllers\ExpenseInstallment;

use App\Http\V1\Controllers\Global\BaseController;
use App\Http\V1\Requests\ExpenseInstallment\IndexExpenseInstallmentRequest;
use App\Http\V1\Requests\ExpenseInstallment\StoreExpenseInstallmentRequest;
use App\Http\V1\Requests\ExpenseInstallment\UpdateExpenseInstallmentRequest;
use App\Http\V1\Resources\ExpenseInstallment\ExpenseInstallmentResource;
use App\Models\ExpenseInstallment;
use App\Services\ExpenseInstallment\DeleteExpenseInstallmentService;
use App\Services\ExpenseInstallment\IndexExpenseInstallmentService;
use App\Services\ExpenseInstallment\StoreExpenseInstallmentService;
use App\Services\ExpenseInstallment\UpdateExpenseInstallmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExpenseInstallmentController extends BaseController
{
    public function index(
        IndexExpenseInstallmentRequest $indexRequest,
        IndexExpenseInstallmentService $indexService
    ): AnonymousResourceCollection {
        $data = $indexRequest->validated();
        $list = $indexService->run($data);

        return ExpenseInstallmentResource::collection($list);
    }

    public function show(ExpenseInstallment $expenseInstallment): JsonResponse
    {
        return $this->successResponse(
            new ExpenseInstallmentResource($expenseInstallment),
            'Parcela encontrada com sucesso.'
        );
    }

    public function store(
        StoreExpenseInstallmentRequest $storeRequest,
        StoreExpenseInstallmentService $storeService
    ): JsonResponse {
        $data = $storeRequest->validated();
        $item = $storeService->run($data);

        return $this->successResponse(
            new ExpenseInstallmentResource($item),
            'Parcela criada com sucesso.'
        );
    }

    public function update(
        UpdateExpenseInstallmentRequest $updateRequest,
        UpdateExpenseInstallmentService $updateService,
        ExpenseInstallment $expenseInstallment
    ): JsonResponse {
        $data = $updateRequest->validated();
        $item = $updateService->run($expenseInstallment, $data);

        return $this->successResponse(
            new ExpenseInstallmentResource($item),
            'Parcela atualizada com sucesso.'
        );
    }

    public function destroy(
        ExpenseInstallment $expenseInstallment,
        DeleteExpenseInstallmentService $deleteService
    ): JsonResponse {
        $deleteService->run($expenseInstallment);

        return $this->successResponse(
            null,
            'Parcela removida com sucesso.'
        );
    }
}
