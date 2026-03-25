<?php

namespace App\Services\CostCenter;

use App\Models\CostCenter;

class StoreCostCenterService
{
    public function run(array $data): CostCenter
    {
        $data['user_id'] = auth()->id();

        return CostCenter::create($data);
    }
}
