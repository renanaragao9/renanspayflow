<?php

namespace App\Services\CostCenter;

use App\Models\CostCenter;

class UpdateCostCenterService
{
    public function run(CostCenter $costCenter, array $data): CostCenter
    {
        $costCenter->update($data);

        return $costCenter;
    }
}
