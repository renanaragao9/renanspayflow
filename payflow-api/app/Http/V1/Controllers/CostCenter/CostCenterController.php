<?php

namespace App\Http\V1\Controllers\CostCenter;

use App\Http\V1\Controllers\Global\BaseController;
use App\Http\V1\Requests\CostCenter\IndexCostCenterRequest;
use App\Http\V1\Requests\CostCenter\StoreCostCenterRequest;
use App\Http\V1\Requests\CostCenter\UpdateCostCenterRequest;
use App\Http\V1\Resources\CostCenter\CostCenterResource;
use App\Models\CostCenter;
use App\Services\CostCenter\DeleteCostCenterService;
use App\Services\CostCenter\IndexCostCenterService;
use App\Services\CostCenter\StoreCostCenterService;
use App\Services\CostCenter\UpdateCostCenterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CostCenterController extends BaseController
{
    public function index(
        IndexCostCenterRequest $indexCostCenterRequest,
        IndexCostCenterService $indexCostCenterService,
    ): AnonymousResourceCollection {
        $data = $indexCostCenterRequest->validated();
        $costCenters = $indexCostCenterService->run($data);

        return CostCenterResource::collection($costCenters);
    }

    public function show(CostCenter $costCenter): JsonResponse
    {
        return $this->successResponse(
            new CostCenterResource($costCenter),
            'Centro de custo encontrado com sucesso.'
        );
    }

    public function store(
        StoreCostCenterRequest $storeCostCenterRequest,
        StoreCostCenterService $storeCostCenterService
    ): JsonResponse {
        $data = $storeCostCenterRequest->validated();
        $costCenter = $storeCostCenterService->run($data);

        return $this->successResponse(
            new CostCenterResource($costCenter),
            'Centro de custo criado com sucesso.'
        );
    }

    public function update(
        UpdateCostCenterRequest $updateCostCenterRequest,
        UpdateCostCenterService $updateCostCenterService,
        CostCenter $costCenter
    ): JsonResponse {
        $data = $updateCostCenterRequest->validated();
        $costCenter = $updateCostCenterService->run($costCenter, $data);

        return $this->successResponse(
            new CostCenterResource($costCenter),
            'Centro de custo atualizado com sucesso.'
        );
    }

    public function destroy(
        CostCenter $costCenter,
        DeleteCostCenterService $deleteCostCenterService
    ): JsonResponse {
        $deleteCostCenterService->run($costCenter);

        return $this->successResponse(
            null,
            'Centro de custo removido com sucesso.'
        );
    }
}
