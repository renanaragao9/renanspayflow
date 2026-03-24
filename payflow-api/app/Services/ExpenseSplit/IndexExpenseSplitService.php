<?php

namespace App\Services\ExpenseSplit;

use App\Models\ExpenseSplit;
use App\Traits\OrderByColumnAndDirection;
use App\Traits\ParseRequestParams;

class IndexExpenseSplitService
{
    use OrderByColumnAndDirection;
    use ParseRequestParams;

    private ExpenseSplit $expenseSplit;

    public function __construct(ExpenseSplit $expenseSplit)
    {
        $this->expenseSplit = $expenseSplit;
    }

    public function run($data)
    {
        $parseRequestParams = $this->parseRequestParams($data);
        $paginate = $parseRequestParams['paginate'];
        $perPage = $data['per_page'] ?? 10;
        $search = $data['search'] ?? null;
        $expenseInstallmentId = $data['expense_installment_id'] ?? null;
        $contactId = $data['contact_id'] ?? null;
        $orderByColumn = $data['order_by_column'] ?? 'id';
        $orderByDirection = $data['order_by_direction'] ?? 'asc';

        $query = $this->expenseSplit
            ->when($expenseInstallmentId, fn ($q) => $q->where('expense_installment_id', $expenseInstallmentId))
            ->when($contactId, fn ($q) => $q->where('contact_id', $contactId))
            ->when($search, fn ($q) => $q->where('amount', 'like', '%'.$search.'%'));

        if ($paginate) {
            return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection)
                ->paginateWithSort($perPage)
                ->withQueryString();
        }

        return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection);
    }
}
