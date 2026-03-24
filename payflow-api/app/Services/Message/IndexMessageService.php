<?php

namespace App\Services\Message;

use App\Models\Message;
use App\Traits\OrderByColumnAndDirection;
use App\Traits\ParseRequestParams;

class IndexMessageService
{
    use OrderByColumnAndDirection;
    use ParseRequestParams;

    private Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function run($data)
    {
        $parseRequestParams = $this->parseRequestParams($data);
        $paginate = $parseRequestParams['paginate'];
        $perPage = $data['per_page'] ?? 10;
        $search = $data['search'] ?? null;
        $userId = $data['user_id'] ?? null;
        $contactId = $data['contact_id'] ?? null;
        $expenseInstallmentId = $data['expense_installment_id'] ?? null;
        $orderByColumn = $data['order_by_column'] ?? 'id';
        $orderByDirection = $data['order_by_direction'] ?? 'asc';

        $query = $this->message
            ->when($userId, fn ($q) => $q->where('user_id', $userId))
            ->when($contactId, fn ($q) => $q->where('contact_id', $contactId))
            ->when($expenseInstallmentId, fn ($q) => $q->where('expense_installment_id', $expenseInstallmentId))
            ->when($search, fn ($q) => $q->where('message', 'like', '%'.$search.'%'));

        if ($paginate) {
            return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection)
                ->paginateWithSort($perPage)
                ->withQueryString();
        }

        return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection);
    }
}
