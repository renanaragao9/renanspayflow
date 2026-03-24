<?php

namespace App\Traits;

use Carbon\Carbon;

trait ParseRequestParams
{
    protected function parseRequestParams(array $data): array
    {
        $startDate = isset($data['filters']['start_date']) ? Carbon::parse($data['filters']['start_date'])->format('Y-m-d H:i:s') : null;
        $endDate = isset($data['filters']['end_date']) ? Carbon::parse($data['filters']['end_date'].'23:59:59')->format('Y-m-d H:i:s') : null;
        $paginate = isset($data['paginate']) && filter_var($data['paginate'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'paginate' => $paginate,
        ];
    }
}
