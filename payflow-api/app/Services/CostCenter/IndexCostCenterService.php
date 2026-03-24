<?php

namespace App\Services\CostCenter;

use App\Models\CostCenter;
use App\Traits\OrderByColumnAndDirection;
use App\Traits\ParseRequestParams;

class IndexCostCenterService
{
    use OrderByColumnAndDirection;
    use ParseRequestParams;

    private CostCenter $costCenter;

    public function __construct(CostCenter $costCenter)
    {
        $this->costCenter = $costCenter;
    }

    public function run($data)
    {
        $parseRequestParams = $this->parseRequestParams($data);
        $paginate = $parseRequestParams['paginate'];
        $perPage = $data['per_page'] ?? 10;
        $search = $data['search'] ?? null;
        $orderByColumn = $data['order_by_column'] ?? 'id';
        $orderByDirection = $data['order_by_direction'] ?? 'asc';

        $query = $this->costCenter
            ->with('user')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%'.$search.'%');
            });

        if ($paginate) {
            return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection)
                ->paginateWithSort($perPage)
                ->withQueryString();
        }

        return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection);
    }
}
