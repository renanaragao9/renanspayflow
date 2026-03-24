<?php

namespace App\Http\V1\Resources\ExpenseSplit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseSplitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'paid' => $this->paid,
            'expense_installment_id' => $this->expense_installment_id,
            'contact_id' => $this->contact_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
