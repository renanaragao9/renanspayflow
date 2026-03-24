<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends BaseModel
{
    protected $table = 'expenses';

    protected $fillable = [
        'description',
        'purchase_date',
        'total_amount',
        'installments',
        'cost_center_id',
    ];

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }
}
