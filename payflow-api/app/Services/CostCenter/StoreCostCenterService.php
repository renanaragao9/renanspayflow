<?php

namespace App\Services\CostCenter;

use App\Models\CostCenter;

class StoreCostCenterService
{
    public function run(array $data): CostCenter
    {
        return CostCenter::create($data);
    }
}
