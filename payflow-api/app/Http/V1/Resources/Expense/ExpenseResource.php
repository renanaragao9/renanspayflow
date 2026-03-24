<?php

namespace App\Http\V1\Resources\Expense;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'purchase_date' => $this->purchase_date,
            'total_amount' => $this->total_amount,
            'installments' => $this->installments,
            'cost_center_id' => $this->cost_center_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
