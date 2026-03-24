<?php

namespace App\Services\CostCenter;

use App\Models\CostCenter;

class DeleteCostCenterService
{
    public function run(CostCenter $costCenter): void
    {
        $costCenter->delete();
    }
}
