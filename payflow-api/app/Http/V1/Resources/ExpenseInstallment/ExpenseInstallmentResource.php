<?php

namespace App\Http\V1\Resources\ExpenseInstallment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseInstallmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'due_date' => $this->due_date,
            'amount' => $this->amount,
            'installment_number' => $this->installment_number,
            'paid' => $this->paid,
            'expense_id' => $this->expense_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
