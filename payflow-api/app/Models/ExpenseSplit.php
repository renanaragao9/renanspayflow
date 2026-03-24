<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseSplit extends BaseModel
{
    protected $table = 'expense_splits';

    protected $fillable = [
        'amount',
        'paid',
        'expense_installment_id',
        'contact_id',
    ];

    public function expenseInstallment(): BelongsTo
    {
        return $this->belongsTo(ExpenseInstallment::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
