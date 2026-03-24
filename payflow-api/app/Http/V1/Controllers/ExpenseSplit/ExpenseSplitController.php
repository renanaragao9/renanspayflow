<?php

namespace App\Http\V1\Controllers\ExpenseSplit;

use App\Http\V1\Controllers\Global\BaseController;
use App\Http\V1\Requests\ExpenseSplit\IndexExpenseSplitRequest;
use App\Http\V1\Requests\ExpenseSplit\StoreExpenseSplitRequest;
use App\Http\V1\Requests\ExpenseSplit\UpdateExpenseSplitRequest;
use App\Http\V1\Resources\ExpenseSplit\ExpenseSplitResource;
use App\Models\ExpenseSplit;
use App\Services\ExpenseSplit\DeleteExpenseSplitService;
use App\Services\ExpenseSplit\IndexExpenseSplitService;
use App\Services\ExpenseSplit\StoreExpenseSplitService;
use App\Services\ExpenseSplit\UpdateExpenseSplitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExpenseSplitController extends BaseController
{
    public function index(
        IndexExpenseSplitRequest $indexRequest,
        IndexExpenseSplitService $indexService
    ): AnonymousResourceCollection {
        $data = $indexRequest->validated();
        $splits = $indexService->run($data);

        return ExpenseSplitResource::collection($splits);
    }

    public function show(ExpenseSplit $expenseSplit): JsonResponse
    {
        return $this->successResponse(
            new ExpenseSplitResource($expenseSplit),
            'Split de despesa encontrado com sucesso.'
        );
    }

    public function store(
        StoreExpenseSplitRequest $storeRequest,
        StoreExpenseSplitService $storeService
    ): JsonResponse {
        $data = $storeRequest->validated();
        $split = $storeService->run($data);

        return $this->successResponse(
            new ExpenseSplitResource($split),
            'Split de despesa criado com sucesso.'
        );
    }

    public function update(
        UpdateExpenseSplitRequest $updateRequest,
        UpdateExpenseSplitService $updateService,
        ExpenseSplit $expenseSplit
    ): JsonResponse {
        $data = $updateRequest->validated();
        $split = $updateService->run($expenseSplit, $data);

        return $this->successResponse(
            new ExpenseSplitResource($split),
            'Split de despesa atualizado com sucesso.'
        );
    }

    public function destroy(
        ExpenseSplit $expenseSplit,
        DeleteExpenseSplitService $deleteService
    ): JsonResponse {
        $deleteService->run($expenseSplit);

        return $this->successResponse(
            null,
            'Split de despesa removido com sucesso.'
        );
    }
}
