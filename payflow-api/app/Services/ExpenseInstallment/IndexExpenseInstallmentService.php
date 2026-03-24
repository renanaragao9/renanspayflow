<?php

namespace App\Services\ExpenseInstallment;

use App\Models\ExpenseInstallment;
use App\Traits\OrderByColumnAndDirection;
use App\Traits\ParseRequestParams;

class IndexExpenseInstallmentService
{
    use OrderByColumnAndDirection;
    use ParseRequestParams;

    private ExpenseInstallment $expenseInstallment;

    public function __construct(ExpenseInstallment $expenseInstallment)
    {
        $this->expenseInstallment = $expenseInstallment;
    }

    public function run($data)
    {
        $parseRequestParams = $this->parseRequestParams($data);
        $paginate = $parseRequestParams['paginate'];
        $perPage = $data['per_page'] ?? 10;
        $search = $data['search'] ?? null;
        $expenseId = $data['expense_id'] ?? null;
        $orderByColumn = $data['order_by_column'] ?? 'id';
        $orderByDirection = $data['order_by_direction'] ?? 'asc';

        $query = $this->expenseInstallment
            ->when($expenseId, fn ($query) => $query->where('expense_id', $expenseId))
            ->when($search, fn ($query) => $query->where('installment_number', 'like', '%'.$search.'%'));

        if ($paginate) {
            return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection)
                ->paginateWithSort($perPage)
                ->withQueryString();
        }

        return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection);
    }
}
