<?php

namespace App\Http\V1\Controllers\CostCenter;

use App\Http\V1\Controllers\Global\BaseController;
use App\Http\V1\Requests\Mark\IndexMarkRequest;
use App\Http\V1\Requests\Mark\StoreMarkRequest;
use App\Http\V1\Requests\Mark\UpdateMarkRequest;
use App\Http\V1\Resources\Mark\MarkResource;
use App\Models\Mark;
use App\Services\Mark\DeleteMarkService;
use App\Services\Mark\IndexMarkService;
use App\Services\Mark\StoreMarkService;
use App\Services\Mark\UpdateMarkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CostCenterController extends BaseController
{
    public function index(
        IndexMarkRequest $indexMarkRequest,
        IndexMarkService $indexMarkService,
    ): AnonymousResourceCollection {
        $data = $indexMarkRequest->validated();
        $marks = $indexMarkService->run($data);

        return MarkResource::collection($marks);
    }

    public function show(Mark $mark): JsonResponse
    {
        return $this->successResponse(
            new MarkResource($mark),
            'Marca encontrada com sucesso.'
        );
    }

    public function store(
        StoreMarkRequest $storeMarkRequest,
        StoreMarkService $storeMarkService
    ): JsonResponse {
        $data = $storeMarkRequest->validated();
        $mark = $storeMarkService->run($data);

        return $this->successResponse(
            new MarkResource($mark),
            'Marca criada com sucesso.'
        );
    }

    public function update(
        UpdateMarkRequest $updateMarkRequest,
        UpdateMarkService $updateMarkService,
        Mark $mark
    ): JsonResponse {
        $data = $updateMarkRequest->validated();
        $mark = $updateMarkService->run($mark, $data);

        return $this->successResponse(
            new MarkResource($mark),
            'Marca atualizada com sucesso.'
        );
    }

    public function destroy(
        Mark $mark,
        DeleteMarkService $deleteMarkService
    ): JsonResponse {
        $deleteMarkService->run($mark);

        return $this->successResponse(
            null,
            'Marca removida com sucesso.'
        );
    }
}
