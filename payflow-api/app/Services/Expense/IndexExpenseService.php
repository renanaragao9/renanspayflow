<?php

namespace App\Services\Expense;

use App\Models\Expense;
use App\Traits\OrderByColumnAndDirection;
use App\Traits\ParseRequestParams;

class IndexExpenseService
{
    use OrderByColumnAndDirection;
    use ParseRequestParams;

    private Expense $expense;

    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }

    public function run($data)
    {
        $parseRequestParams = $this->parseRequestParams($data);
        $paginate = $parseRequestParams['paginate'];
        $perPage = $data['per_page'] ?? 10;
        $search = $data['search'] ?? null;
        $orderByColumn = $data['order_by_column'] ?? 'id';
        $orderByDirection = $data['order_by_direction'] ?? 'asc';

        $query = $this->expense
            ->with('costCenter')
            ->when($search, function ($query) use ($search) {
                return $query->where('description', 'like', '%'.$search.'%');
            });

        if ($paginate) {
            return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection)
                ->paginateWithSort($perPage)
                ->withQueryString();
        }

        return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection);
    }
}
